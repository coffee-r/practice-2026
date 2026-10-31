#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "$0")/../../../.." && pwd)"
EXAMPLE_DIR="${ROOT_DIR}/experiments/shop-migration/ci3-shop"
TRACE="${EXAMPLE_DIR}/var/tekagami.jsonl"

php "${ROOT_DIR}/tools/tekagami-data/bin/tekagami" report "${TRACE}" > "${EXAMPLE_DIR}/var/report.md"
php "${ROOT_DIR}/tools/tekagami-data/bin/tekagami" report "${TRACE}" --format json > "${EXAMPLE_DIR}/var/report.json"
php "${ROOT_DIR}/tools/tekagami-data/bin/tekagami" export "${TRACE}" --format json > "${EXAMPLE_DIR}/var/export.json"
php "${ROOT_DIR}/tools/tekagami-data/bin/tekagami" summary "${TRACE}" > "${EXAMPLE_DIR}/var/summary.md"
php "${ROOT_DIR}/tools/tekagami-data/bin/tekagami" summary "${TRACE}" --format json > "${EXAMPLE_DIR}/var/summary.json"

echo "wrote ${EXAMPLE_DIR}/var/report.md"
echo "wrote ${EXAMPLE_DIR}/var/report.json"
echo "wrote ${EXAMPLE_DIR}/var/export.json"
echo "wrote ${EXAMPLE_DIR}/var/summary.md"
echo "wrote ${EXAMPLE_DIR}/var/summary.json"
