# PHP の公式イメージを使用
FROM php:8.1-apache

# 作業ディレクトリを設定
WORKDIR /var/www/html

# 必要な PHP 拡張機能をインストール
RUN docker-php-ext-install mysqli pdo pdo_mysql

# プロジェクトのコードをコンテナにコピー
COPY . /var/www/html

# アパッチの設定を調整
RUN chown -R www-data:www-data /var/www/html \
    && a2enmod rewrite
