# PHPの公式イメージを使用
FROM php:8.1-apache

# 作業ディレクトリを設定
WORKDIR /var/www/html

# 必要なPHP拡張機能をインストール
RUN docker-php-ext-install mysqli pdo pdo_mysql

# プロジェクトのコードをコンテナにコピー
COPY ./shift-app/public /var/www/html
COPY ./shift-app/includes /var/www/includes

# Apacheの設定を調整
RUN chown -R www-data:www-data /var/www/html /var/www/includes \
    && chmod -R 755 /var/www/html /var/www/includes \
    && a2enmod rewrite

# DirectoryIndexの設定を追加
RUN echo "<IfModule dir_module>\n    DirectoryIndex index.php index.html\n</IfModule>" > /etc/apache2/conf-available/custom-directory-index.conf \
    && a2enconf custom-directory-index

# ServerNameの設定を追加
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# サーバーを起動
CMD ["apache2-foreground"]
