# ベースイメージ
FROM php:8.3-fpm

# 必要な依存関係のインストール
RUN apt-get update && apt-get install -y \
    curl \
    gnupg \
    unixodbc-dev \
    gcc \
    g++ \
    make \
    && curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add - \
    && curl https://packages.microsoft.com/config/debian/11/prod.list > /etc/apt/sources.list.d/mssql-release.list \
    && apt-get update \
    && ACCEPT_EULA=Y apt-get install -y msodbcsql18 \
    && pecl install pdo_sqlsrv sqlsrv xdebug \
    && docker-php-ext-enable pdo_sqlsrv sqlsrv

# 作業ディレクトリの設定
WORKDIR /var/www/html

# アプリケーションコードのコピー
COPY src/ .

# Composerのインストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Composer install
RUN composer install

# 最適化
RUN if [ "$ENVIRONMENT" = "production" -o "$ENVIRONMENT" = "staging" ]; then \
    composer install --optimize-autoloader --no-dev \
;fi


# /var/www/storage ディレクトリとそのサブディレクトリの所有者を www-data に変更
# www-data は、通常、PHP-FPM が実行されるユーザーであり、書き込み権限を与えるために設定
RUN chown -R www-data:www-data /var/www/html/storage

# iniファイルをコンテナ内にコピー
COPY opcache.ini /usr/local/etc/php/conf.d/xdebug.ini
COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

RUN if [ "$ENVIRONMENT" != "local" ]; then \
    rm /usr/local/etc/php/conf.d/xdebug.ini  \
;fi

# pmをstaticに設定
RUN echo "pm = dynamic" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "pm.max_children = 50" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "pm.start_servers = 10" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "pm.min_spare_servers = 5" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "pm.max_spare_servers = 20" >> /usr/local/etc/php-fpm.d/www.conf