# PHPの公式イメージを使用
FROM php:8.1-apache

# 作業ディレクトリを設定
WORKDIR /var/www/html

# 必要なPHP拡張機能をインストール
RUN docker-php-ext-install mysqli pdo pdo_mysql

# プロジェクトのコードをコンテナにコピー
COPY ./public /var/www/html

# アパッチの設定を調整
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && a2enmod rewrite

# サーバーを起動
CMD ["apache2-foreground"]
