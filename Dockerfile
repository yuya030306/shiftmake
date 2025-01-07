# ベースイメージとしてPHPとApacheを使用
FROM php:8.1-apache

# 必要なPHP拡張機能をインストール
RUN docker-php-ext-install mysqli pdo pdo_mysql

# 作業ディレクトリを設定
WORKDIR /var/www/html

# プロジェクトのコードをコンテナにコピー
COPY ./shift-app /var/www/html

# Apache設定ファイルをコピー
COPY ./000-default.conf /etc/apache2/sites-available/000-default.conf

# 権限を調整
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# ApacheのRewriteモジュールを有効化
RUN a2enmod rewrite

# 必要なApacheサイト設定を有効化
RUN a2ensite 000-default.conf

# ServerName を設定し警告を抑制
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Apache設定をテストし、問題がない場合のみ起動
CMD ["apache2-foreground"]
