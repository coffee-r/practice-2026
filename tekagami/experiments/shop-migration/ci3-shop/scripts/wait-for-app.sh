#!/usr/bin/env bash
set -euo pipefail

BASE_URL="${BASE_URL:-http://localhost:8088}"

for i in $(seq 1 120); do
  if curl -fsS "${BASE_URL}/api/health" >/dev/null 2>&1; then
    echo "app is ready: ${BASE_URL}"
    exit 0
  fi
  sleep 2
done

echo "app did not become ready: ${BASE_URL}" >&2
exit 1
