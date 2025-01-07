# ベースイメージとしてPHPとApacheを使用
FROM php:8.1-apache

# 必要なPHP拡張機能をインストール
RUN docker-php-ext-install mysqli pdo pdo_mysql

# 作業ディレクトリを設定
WORKDIR /var/www/html

# プロジェクトのコードをコンテナにコピー
COPY ./shift-app /var/www/html

# 権限を調整
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# ApacheのRewriteモジュールを有効化
RUN a2enmod rewrite

# Apacheのカスタム設定を追加
RUN echo "<VirtualHost *:80>
    DocumentRoot /var/www/html
    <Directory /var/www/html>
        Options FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    DirectoryIndex index.php index.html
</VirtualHost>" > /etc/apache2/sites-available/000-default.conf

# Apache設定を有効化
RUN a2ensite 000-default.conf
