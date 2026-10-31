#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "$0")/../../../.." && pwd)"
EXAMPLE_DIR="${ROOT_DIR}/experiments/shop-migration/laravel12-shop"
TRACE="${EXAMPLE_DIR}/var/tekagami.jsonl"
EXPORT="${EXAMPLE_DIR}/var/export.json"

if [ ! -f "${TRACE}" ]; then
  echo "Error: ${TRACE} not found. Run run-e2e.sh first." >&2
  exit 1
fi

php "${ROOT_DIR}/tools/tekagami-data/bin/tekagami" export "${TRACE}" --format json > "${EXPORT}"
echo "wrote ${EXPORT}"

php "${ROOT_DIR}/tools/tekagami-data/bin/tekagami" report "${TRACE}" --format md > "${EXAMPLE_DIR}/var/analysis.md"
echo "wrote ${EXAMPLE_DIR}/var/analysis.md"
