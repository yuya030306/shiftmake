# PHPの公式イメージを使用
FROM php:8.1-apache

# 作業ディレクトリを設定
WORKDIR /var/www/html

# 必要なPHP拡張機能をインストール
RUN docker-php-ext-install mysqli pdo pdo_mysql

# プロジェクトのコードをコンテナにコピー
COPY ./shift-app /var/www/html

# Apacheの設定を調整
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && a2enmod rewrite

# ApacheのDirectory設定を追加
RUN echo '<Directory /var/www/html>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' >> /etc/apache2/apache2.conf

# カスタムディレクトリインデックスを設定
RUN echo "<IfModule dir_module>\n    DirectoryIndex index.php index.html\n</IfModule>" > /etc/apache2/conf-available/custom-directory-index.conf \
    && a2enconf custom-directory-index

# サーバーネームを設定
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# サーバーを起動
CMD ["apache2-foreground"]
