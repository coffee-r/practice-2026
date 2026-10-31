#!/usr/bin/env bash
# CI3 shop の export と Laravel12 shop の export を比較して diff を生成する。
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "$0")/../../../.." && pwd)"
SCENARIO_DIR="${ROOT_DIR}/experiments/shop-migration"
LEGACY="${SCENARIO_DIR}/ci3-shop/var/export.json"
TARGET="${SCENARIO_DIR}/laravel12-shop/var/export.json"

if [ ! -f "${LEGACY}" ]; then
  echo "Error: ${LEGACY} not found. Run ci3-shop E2E + analyze first." >&2
  exit 1
fi
if [ ! -f "${TARGET}" ]; then
  echo "Error: ${TARGET} not found. Run laravel12-shop E2E + analyze.sh first." >&2
  exit 1
fi

php "${ROOT_DIR}/tools/tekagami-data/bin/tekagami" diff "${LEGACY}" "${TARGET}" --format md   > "${SCENARIO_DIR}/diff/diff.md"
echo "wrote ${SCENARIO_DIR}/diff/diff.md"

php "${ROOT_DIR}/tools/tekagami-data/bin/tekagami" diff "${LEGACY}" "${TARGET}" --format json > "${SCENARIO_DIR}/diff/diff.json"
echo "wrote ${SCENARIO_DIR}/diff/diff.json"
