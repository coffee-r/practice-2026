FROM rclone/rclone:latest

# Install AWS CLI for S3 operations and bash
RUN apk add --no-cache \
    bash \
    aws-cli \
    tzdata

# Set working directory
WORKDIR /app

# Copy backup script
COPY backup.sh /app/backup.sh
RUN chmod +x /app/backup.sh

# rclone config is dynamically generated from environment variables
# For local development, you can create rclone.conf manually (git-ignored)

# Override rclone's default ENTRYPOINT and set our script as the command
ENTRYPOINT []
CMD ["/app/backup.sh"]
