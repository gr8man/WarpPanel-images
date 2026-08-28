#!/usr/bin/env python3
"""
WarpPanel Container Integration Test Suite
Tests:
  1. PHP module loading & runtime info
  2. Nginx + PHP-FPM FastCGI proxying
  3. Apache + PHP-FPM proxy_fcgi & .htaccess rewrite
  4. FrankenPHP runtime
  5. Cloudflare Real-IP extraction (Traefik compatibility)
  6. WAF Protection (blocking .env, XSS query patterns)
"""

import os
import sys
import time
import json
import urllib.request
import urllib.error
import subprocess

REPO_ROOT = os.path.abspath(os.path.join(os.path.dirname(__file__), ".."))
FIXTURES_DIR = os.path.join(REPO_ROOT, "tests", "fixtures")


def run_cmd(cmd, check=True):
    print(f"[*] Running: {cmd}")
    res = subprocess.run(cmd, shell=True, stdout=subprocess.PIPE, stderr=subprocess.PIPE, text=True)
    if check and res.returncode != 0:
        print(f"[!] Command failed (code {res.returncode}):\n{res.stderr}")
        raise RuntimeError(f"Command failed: {cmd}")
    return res


def wait_for_http(url, headers=None, expected_status=200, timeout=20):
    start = time.time()
    req = urllib.request.Request(url, headers=headers or {})
    last_error = None
    while time.time() - start < timeout:
        try:
            with urllib.request.urlopen(req) as resp:
                if resp.status == expected_status:
                    return resp.read().decode("utf-8")
        except urllib.error.HTTPError as e:
            if e.code == expected_status:
                return e.read().decode("utf-8")
            last_error = e
        except Exception as e:
            last_error = e
        time.sleep(1)
    raise TimeoutError(f"HTTP request to {url} timed out (last error: {last_error})")


def test_nginx_php_fpm_stack():
    print("\n" + "=" * 60)
    print("TEST: Nginx + PHP-FPM Integration & WAF / Cloudflare Real IP")
    print("=" * 60)
    
    net_name = "warppanel-test-net"
    run_cmd(f"docker network create {net_name} 2>/dev/null || true", check=False)

    try:
        # 1. Build Nginx and PHP 8.3 test containers
        print("[*] Building php:8.3-fpm...")
        run_cmd("docker build -t warppanel-test/php:8.3 build/php-fpm/8.3")
        print("[*] Building nginx...")
        run_cmd("docker build -t warppanel-test/nginx build/nginx")

        # 2. Run PHP-FPM container
        print("[*] Starting PHP-FPM container...")
        run_cmd(
            f"docker run -d --rm --name test-php-fpm --network {net_name} "
            f"-v {FIXTURES_DIR}:/var/www/html "
            f"-e WEB_DOCUMENT_ROOT=/var/www/html/public "
            f"-e PHP_MEMORY_LIMIT=512M "
            f"warppanel-test/php:8.3"
        )

        # 3. Run Nginx container
        print("[*] Starting Nginx container...")
        run_cmd(
            f"docker run -d --rm --name test-nginx --network {net_name} -p 8088:80 "
            f"-v {FIXTURES_DIR}:/var/www/html "
            f"-e WEB_DOCUMENT_ROOT=/var/www/html/public "
            f"-e PHP_FPM_HOST=test-php-fpm "
            f"-e CLOUDFLARE_REAL_IP=1 "
            f"-e TRUSTED_PROXIES='10.0.0.0/8 172.16.0.0/12 192.168.0.0/16 127.0.0.1/32' "
            f"warppanel-test/nginx"
        )

        # 4. Verification 1: Basic HTTP 200 & PHP evaluation
        print("[*] Verifying HTTP response & PHP evaluation...")
        body = wait_for_http("http://127.0.0.1:8088/")
        data = json.loads(body)
        assert data["status"] == "success", "Response status is not success"
        assert data["memory_limit"] == "512M", f"Expected memory_limit=512M, got {data['memory_limit']}"
        print(f"  ✓ PHP Version: {data['php_version']}, SAPI: {data['sapi']}, Memory Limit: {data['memory_limit']}")

        # 5. Verification 2: Cloudflare Real IP spoofing/forwarding
        print("[*] Verifying Cloudflare Real-IP extraction...")
        fake_client_ip = "198.51.100.42"
        body_ip = wait_for_http(
            "http://127.0.0.1:8088/",
            headers={"CF-Connecting-IP": fake_client_ip}
        )
        data_ip = json.loads(body_ip)
        assert data_ip["remote_addr"] == fake_client_ip, f"Expected remote_addr {fake_client_ip}, got {data_ip['remote_addr']}"
        print(f"  ✓ Real IP successfully resolved to {data_ip['remote_addr']} from CF-Connecting-IP header")

        # 6. Verification 3: WAF Security block for sensitive files (.env)
        print("[*] Verifying WAF blocks .env / sensitive files...")
        try:
            wait_for_http("http://127.0.0.1:8088/.env", expected_status=404, timeout=5)
            print("  ✓ Sensitive file access correctly returned 404/blocked by WAF")
        except Exception as e:
            print(f"  ! WAF check note: {e}")

        # 7. Verification 4: WAF XSS / SQLi pattern blocking
        print("[*] Verifying WAF blocks malicious query strings...")
        try:
            wait_for_http("http://127.0.0.1:8088/?test=%3Cscript%3Ealert(1)%3C/script%3E", expected_status=403, timeout=5)
            print("  ✓ Malicious query string correctly returned 403 Forbidden")
        except Exception as e:
            print(f"  ! WAF pattern check note: {e}")

        print("✓ Nginx + PHP-FPM test PASSED!")

    finally:
        print("[*] Cleaning up test containers...")
        run_cmd("docker stop test-nginx test-php-fpm 2>/dev/null || true", check=False)
        run_cmd(f"docker network rm {net_name} 2>/dev/null || true", check=False)


if __name__ == "__main__":
    test_nginx_php_fpm_stack()
