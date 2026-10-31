#!/bin/bash
set -euo pipefail

# ============================================
# R2 to AWS S3 Backup Script
# ============================================
# Cloudflare R2からAWS S3へバックアップを実行します
#
# Required environment variables:
# - R2_ENDPOINT          : Cloudflare R2 endpoint
# - R2_ACCESS_KEY        : R2 access key
# - R2_SECRET_KEY        : R2 secret key
# - R2_BUCKET            : R2 bucket name
# - S3_REGION            : AWS region (e.g., ap-northeast-1)
# - S3_ACCESS_KEY        : AWS IAM access key
# - S3_SECRET_KEY        : AWS IAM secret key
# - S3_BUCKET            : S3 bucket name
# - ENVIRONMENT          : Environment name (local, staging, production)
# - S3_STORAGE_CLASS     : Storage class (default: GLACIER)
#                          Options: GLACIER, STANDARD
# ============================================

# Configuration
DATE=$(date +%Y%m%d_%H%M%S)
ENVIRONMENT=${ENVIRONMENT:-production}
BACKUP_PREFIX="${ENVIRONMENT}/backup-${DATE}-${ENVIRONMENT}"
S3_STORAGE_CLASS=${S3_STORAGE_CLASS:-GLACIER}

# Logging function
log() {
    echo "[$(date +'%Y-%m-%d %H:%M:%S')] $*"
}

# Error handling
error_exit() {
    log "ERROR: $1"
    exit 1
}

# Validate required environment variables
log "Validating environment variables..."

required_vars=(
    "R2_ENDPOINT"
    "R2_ACCESS_KEY"
    "R2_SECRET_KEY"
    "R2_BUCKET"
    "S3_REGION"
    "S3_ACCESS_KEY"
    "S3_SECRET_KEY"
    "S3_BUCKET"
    "ENVIRONMENT"
)

for var in "${required_vars[@]}"; do
    if [ -z "${!var:-}" ]; then
        error_exit "Required environment variable $var is not set"
    fi
done

log "Environment variables validated successfully"
log "Environment: ${ENVIRONMENT}"
log "Storage class: ${S3_STORAGE_CLASS}"

# Configure rclone via environment variables
log "Configuring rclone via environment variables..."

export RCLONE_CONFIG_R2_TYPE=${RCLONE_CONFIG_R2_TYPE:-s3}
export RCLONE_CONFIG_R2_PROVIDER=${RCLONE_CONFIG_R2_PROVIDER:-Cloudflare}
export RCLONE_CONFIG_R2_ACCESS_KEY_ID=${R2_ACCESS_KEY}
export RCLONE_CONFIG_R2_SECRET_ACCESS_KEY=${R2_SECRET_KEY}
export RCLONE_CONFIG_R2_ENDPOINT=${R2_ENDPOINT}
export RCLONE_CONFIG_R2_ACL=${RCLONE_CONFIG_R2_ACL:-private}
export RCLONE_CONFIG_R2_NO_CHECK_BUCKET=${RCLONE_CONFIG_R2_NO_CHECK_BUCKET:-true}

export RCLONE_CONFIG_S3_TYPE=${RCLONE_CONFIG_S3_TYPE:-s3}
export RCLONE_CONFIG_S3_PROVIDER=${RCLONE_CONFIG_S3_PROVIDER:-AWS}
export RCLONE_CONFIG_S3_ACCESS_KEY_ID=${S3_ACCESS_KEY}
export RCLONE_CONFIG_S3_SECRET_ACCESS_KEY=${S3_SECRET_KEY}
export RCLONE_CONFIG_S3_REGION=${S3_REGION}
export RCLONE_CONFIG_S3_ACL=${RCLONE_CONFIG_S3_ACL:-private}
export RCLONE_CONFIG_S3_STORAGE_CLASS=${S3_STORAGE_CLASS}

log "rclone configuration set via environment variables"

# Perform backup
log "Starting backup: r2:${R2_BUCKET} -> s3:${S3_BUCKET}/${BACKUP_PREFIX}"

if rclone sync \
    "r2:${R2_BUCKET}" \
    "s3:${S3_BUCKET}/${BACKUP_PREFIX}" \
    --progress \
    --stats 1m \
    --log-level INFO \
    --s3-storage-class "${S3_STORAGE_CLASS}"; then
    log "Backup completed successfully"
else
    error_exit "Backup failed"
fi

# Get backup size
BACKUP_SIZE=$(rclone size "s3:${S3_BUCKET}/${BACKUP_PREFIX}" --json | grep -o '"bytes":[0-9]*' | cut -d':' -f2)
BACKUP_SIZE_MB=$((BACKUP_SIZE / 1024 / 1024))
log "Backup size: ${BACKUP_SIZE_MB} MB"

# Summary
log "============================================"
log "Backup Summary:"
log "  Environment: ${ENVIRONMENT}"
log "  Source: r2:${R2_BUCKET}"
log "  Destination: s3:${S3_BUCKET}/${BACKUP_PREFIX}"
log "  Size: ${BACKUP_SIZE_MB} MB"
log "  Storage Class: ${S3_STORAGE_CLASS}"
log "============================================"
log "Backup process completed successfully"
log ""
log "NOTE: Lifecycle management is handled by S3 Lifecycle Policy"
log "      Please ensure the S3 bucket has lifecycle rules configured"
log "      to delete objects after 90 days."

exit 0
