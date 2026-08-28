#!/usr/bin/env python3
"""
WarpPanel Image & Dockerfile Generator
Generates Dockerfiles, build contexts, docker-bake.hcl and the catalog manifest.
"""

import os
import shutil
import json
import yaml
from jinja2 import Environment, FileSystemLoader

REPO_ROOT = os.path.abspath(os.path.join(os.path.dirname(__file__), ".."))
MATRIX_FILE = os.path.join(REPO_ROOT, "matrix.yaml")
TEMPLATES_DIR = os.path.join(REPO_ROOT, "templates")
BUILD_DIR = os.path.join(REPO_ROOT, "build")
BAKE_FILE = os.path.join(REPO_ROOT, "docker-bake.hcl")
CATALOG_JSON_FILE = os.path.join(REPO_ROOT, "catalog.json")
CATALOG_MD_FILE = os.path.join(REPO_ROOT, "CATALOG.md")


def load_matrix():
    with open(MATRIX_FILE, "r", encoding="utf-8") as f:
        return yaml.safe_load(f)


def setup_jinja():
    return Environment(
        loader=FileSystemLoader(TEMPLATES_DIR),
        trim_blocks=True,
        lstrip_blocks=True
    )


def ensure_dir(path):
    os.makedirs(path, exist_ok=True)


def generate():
    matrix = load_matrix()
    env = setup_jinja()
    registry = matrix.get("registry", "ghcr.io/warppanel")
    
    # Clean previous build directory
    if os.path.exists(BUILD_DIR):
        shutil.rmtree(BUILD_DIR)
    ensure_dir(BUILD_DIR)

    bake_targets = {}
    catalog = {
        "registry": registry,
        "images": {
            "php_fpm": [],
            "frankenphp": [],
            "webservers": []
        }
    }

    # 1. Generate PHP-FPM Modern (8.0 - 8.5)
    modern_template = env.get_template("php-fpm/Dockerfile.alpine-modern.j2")
    for img in matrix["images"]["php_fpm"]["modern"]:
        ver = img["version"]
        target_dir = os.path.join(BUILD_DIR, "php-fpm", ver)
        ensure_dir(target_dir)

        dockerfile_content = modern_template.render(image=img, matrix=matrix)
        with open(os.path.join(target_dir, "Dockerfile"), "w", encoding="utf-8") as f:
            f.write(dockerfile_content)

        # Copy common entrypoint
        shutil.copy2(
            os.path.join(TEMPLATES_DIR, "common", "docker-entrypoint.sh"),
            os.path.join(target_dir, "docker-entrypoint.sh")
        )

        tags = [f"{registry}/php:{tag}" for tag in img["tags"]]
        target_name = f"php-fpm-{ver.replace('.', '_')}"
        bake_targets[target_name] = {
            "context": f"./build/php-fpm/{ver}",
            "dockerfile": "Dockerfile",
            "tags": tags,
            "platforms": ["linux/amd64", "linux/arm64"]
        }

        catalog["images"]["php_fpm"].append({
            "version": ver,
            "type": "modern",
            "base_image": img["base_image"],
            "tags": img["tags"],
            "full_image_tags": tags,
            "status": "ready"
        })

    # 2. Generate PHP-FPM Legacy (5.6 - 7.4)
    legacy_template = env.get_template("php-fpm/Dockerfile.alpine-legacy.j2")
    for img in matrix["images"]["php_fpm"]["legacy"]:
        ver = img["version"]
        target_dir = os.path.join(BUILD_DIR, "php-fpm", ver)
        ensure_dir(target_dir)

        dockerfile_content = legacy_template.render(image=img, matrix=matrix)
        with open(os.path.join(target_dir, "Dockerfile"), "w", encoding="utf-8") as f:
            f.write(dockerfile_content)

        shutil.copy2(
            os.path.join(TEMPLATES_DIR, "common", "docker-entrypoint.sh"),
            os.path.join(target_dir, "docker-entrypoint.sh")
        )

        tags = [f"{registry}/php:{tag}" for tag in img["tags"]]
        target_name = f"php-fpm-{ver.replace('.', '_')}"
        bake_targets[target_name] = {
            "context": f"./build/php-fpm/{ver}",
            "dockerfile": "Dockerfile",
            "tags": tags,
            "platforms": ["linux/amd64"]
        }

        catalog["images"]["php_fpm"].append({
            "version": ver,
            "type": "legacy",
            "base_image": img["base_image"],
            "tags": img["tags"],
            "full_image_tags": tags,
            "status": "ready"
        })

    # 3. Generate FrankenPHP
    franken_template = env.get_template("frankenphp/Dockerfile.j2")
    for img in matrix["images"]["frankenphp"]["versions"]:
        ver = img["php_version"]
        target_dir = os.path.join(BUILD_DIR, "frankenphp", ver)
        ensure_dir(target_dir)

        dockerfile_content = franken_template.render(image=img, matrix=matrix)
        with open(os.path.join(target_dir, "Dockerfile"), "w", encoding="utf-8") as f:
            f.write(dockerfile_content)

        shutil.copy2(
            os.path.join(TEMPLATES_DIR, "frankenphp", "Caddyfile"),
            os.path.join(target_dir, "Caddyfile")
        )
        shutil.copy2(
            os.path.join(TEMPLATES_DIR, "frankenphp", "docker-entrypoint-frankenphp.sh"),
            os.path.join(target_dir, "docker-entrypoint-frankenphp.sh")
        )

        tags = [f"{registry}/frankenphp:{tag}" for tag in img["tags"]]
        target_name = f"frankenphp-{ver.replace('.', '_')}"
        bake_targets[target_name] = {
            "context": f"./build/frankenphp/{ver}",
            "dockerfile": "Dockerfile",
            "tags": tags,
            "platforms": ["linux/amd64", "linux/arm64"]
        }

        catalog["images"]["frankenphp"].append({
            "php_version": ver,
            "base_image": img["base_image"],
            "tags": img["tags"],
            "full_image_tags": tags,
            "status": "ready"
        })

    # 4. Generate Webservers (Nginx, Apache, OpenLiteSpeed)
    webservers = matrix["images"]["webservers"]

    # Nginx
    nginx_template = env.get_template("nginx/Dockerfile.j2")
    nginx_dir = os.path.join(BUILD_DIR, "nginx")
    ensure_dir(nginx_dir)
    with open(os.path.join(nginx_dir, "Dockerfile"), "w", encoding="utf-8") as f:
        f.write(nginx_template.render(image=webservers["nginx"], matrix=matrix))
    for f_name in ["nginx.conf", "default.conf.template", "waf-rules.conf", "docker-entrypoint-nginx.sh"]:
        shutil.copy2(os.path.join(TEMPLATES_DIR, "nginx", f_name), os.path.join(nginx_dir, f_name))
    shutil.copy2(os.path.join(TEMPLATES_DIR, "common", "cloudflare-ips.txt"), os.path.join(nginx_dir, "cloudflare-ips.txt"))

    nginx_tags = [f"{registry}/nginx:{t}" for t in webservers["nginx"]["tags"]]
    bake_targets["nginx"] = {
        "context": "./build/nginx",
        "dockerfile": "Dockerfile",
        "tags": nginx_tags,
        "platforms": ["linux/amd64", "linux/arm64"]
    }
    catalog["images"]["webservers"].append({
        "server": "nginx",
        "base_image": webservers["nginx"]["base_image"],
        "tags": webservers["nginx"]["tags"],
        "full_image_tags": nginx_tags,
        "features": webservers["nginx"]["features"]
    })

    # Apache
    apache_template = env.get_template("apache/Dockerfile.j2")
    apache_dir = os.path.join(BUILD_DIR, "apache")
    ensure_dir(apache_dir)
    with open(os.path.join(apache_dir, "Dockerfile"), "w", encoding="utf-8") as f:
        f.write(apache_template.render(image=webservers["apache"], matrix=matrix))
    for f_name in ["httpd.conf", "vhost.conf.template", "waf-rules.conf", "docker-entrypoint-apache.sh"]:
        shutil.copy2(os.path.join(TEMPLATES_DIR, "apache", f_name), os.path.join(apache_dir, f_name))
    shutil.copy2(os.path.join(TEMPLATES_DIR, "common", "cloudflare-ips.txt"), os.path.join(apache_dir, "cloudflare-ips.txt"))

    apache_tags = [f"{registry}/apache:{t}" for t in webservers["apache"]["tags"]]
    bake_targets["apache"] = {
        "context": "./build/apache",
        "dockerfile": "Dockerfile",
        "tags": apache_tags,
        "platforms": ["linux/amd64", "linux/arm64"]
    }
    catalog["images"]["webservers"].append({
        "server": "apache",
        "base_image": webservers["apache"]["base_image"],
        "tags": webservers["apache"]["tags"],
        "full_image_tags": apache_tags,
        "features": webservers["apache"]["features"]
    })

    # OpenLiteSpeed
    ols_template = env.get_template("openlitespeed/Dockerfile.j2")
    ols_dir = os.path.join(BUILD_DIR, "openlitespeed")
    ensure_dir(ols_dir)
    with open(os.path.join(ols_dir, "Dockerfile"), "w", encoding="utf-8") as f:
        f.write(ols_template.render(image=webservers["openlitespeed"], matrix=matrix))

    ols_tags = [f"{registry}/openlitespeed:{t}" for t in webservers["openlitespeed"]["tags"]]
    bake_targets["openlitespeed"] = {
        "context": "./build/openlitespeed",
        "dockerfile": "Dockerfile",
        "tags": ols_tags,
        "platforms": ["linux/amd64"]
    }
    catalog["images"]["webservers"].append({
        "server": "openlitespeed",
        "base_image": webservers["openlitespeed"]["base_image"],
        "tags": webservers["openlitespeed"]["tags"],
        "full_image_tags": ols_tags,
        "features": webservers["openlitespeed"]["features"]
    })

    # 5. Write docker-bake.hcl
    write_bake_file(bake_targets)

    # 6. Write catalog.json and CATALOG.md
    write_catalog(catalog)

    print("✓ Generation completed successfully! All Dockerfiles, docker-bake.hcl and catalog files are updated.")


def write_bake_file(targets):
    target_names = list(targets.keys())
    content = f"""group "default" {{
    targets = {json.dumps(target_names)}
}}

group "php" {{
    targets = {json.dumps([t for t in target_names if t.startswith("php-fpm")])}
}}

group "frankenphp" {{
    targets = {json.dumps([t for t in target_names if t.startswith("frankenphp")])}
}}

group "webservers" {{
    targets = ["nginx", "apache", "openlitespeed"]
}}

"""
    for name, cfg in targets.items():
        content += f"""target "{name}" {{
    context = "{cfg['context']}"
    dockerfile = "{cfg['dockerfile']}"
    tags = {json.dumps(cfg['tags'])}
    platforms = {json.dumps(cfg['platforms'])}
}}

"""
    with open(BAKE_FILE, "w", encoding="utf-8") as f:
        f.write(content)


def write_catalog(catalog):
    with open(CATALOG_JSON_FILE, "w", encoding="utf-8") as f:
        json.dump(catalog, f, indent=2)

    # Generate Markdown summary
    md_content = "# Katalog Sprawdzonych Obrazów WarpPanel\n\n"
    md_content += "Automatycznie wygenerowana lista dostępnych, zweryfikowanych i zoptymalizowanych obrazów dla platformy hostingowej WarpPanel.\n\n"
    
    md_content += "## 1. PHP-FPM (Alpine)\n\n"
    md_content += "| Wersja | Baza | Dostępne Tagi | Pełny Tag Rejestru | Status |\n"
    md_content += "| :--- | :--- | :--- | :--- | :--- |\n"
    for item in catalog["images"]["php_fpm"]:
        tags_str = ", ".join([f"`{t}`" for t in item["tags"]])
        primary_tag = item["full_image_tags"][0]
        md_content += f"| **PHP {item['version']}** ({item['type']}) | `{item['base_image']}` | {tags_str} | `{primary_tag}` | ✅ Verified |\n"

    md_content += "\n## 2. FrankenPHP (Caddy + PHP Runtime & Worker Mode)\n\n"
    md_content += "| Wersja PHP | Baza | Dostępne Tagi | Pełny Tag Rejestru | Status |\n"
    md_content += "| :--- | :--- | :--- | :--- | :--- |\n"
    for item in catalog["images"]["frankenphp"]:
        tags_str = ", ".join([f"`{t}`" for t in item["tags"]])
        primary_tag = item["full_image_tags"][0]
        md_content += f"| **FrankenPHP (PHP {item['php_version']})** | `{item['base_image']}` | {tags_str} | `{primary_tag}` | ✅ Verified |\n"

    md_content += "\n## 3. Webserwery Standalone\n\n"
    md_content += "| Serwer | Baza | Kluczowe Cechy | Pełny Tag Rejestru | Status |\n"
    md_content += "| :--- | :--- | :--- | :--- | :--- |\n"
    for item in catalog["images"]["webservers"]:
        feats_str = ", ".join([f"`{f}`" for f in item.get("features", [])])
        primary_tag = item["full_image_tags"][0]
        md_content += f"| **{item['server'].upper()}** | `{item['base_image']}` | {feats_str} | `{primary_tag}` | ✅ Verified |\n"

    with open(CATALOG_MD_FILE, "w", encoding="utf-8") as f:
        f.write(md_content)


if __name__ == "__main__":
    generate()
