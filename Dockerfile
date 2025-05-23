FROM php:8.2-cli

# 替换阿里云镜像源（确认基础镜像是Debian bookworm）
RUN echo "deb http://mirrors.aliyun.com/debian/ bookworm main contrib non-free" > /etc/apt/sources.list && \
    echo "deb http://mirrors.aliyun.com/debian/ bookworm-updates main contrib non-free" >> /etc/apt/sources.list && \
    echo "deb http://mirrors.aliyun.com/debian-security/ bookworm-security main contrib non-free" >> /etc/apt/sources.list

# 更新并安装依赖
RUN apt-get update && apt-get install -y \
    # 添加编译工具
    build-essential \
    autoconf \
    make \
    g++ \
    # 基础工具
    nginx \
    supervisor \
    vim \
    git \
    curl \
    wget \
    unzip \
    zip \
    # GD库依赖
    libpng-dev \
    libwebp-dev \
    libjpeg62-turbo-dev \
    libxpm-dev \
    libfreetype6-dev \
    # 其他PHP扩展依赖
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libcurl4-openssl-dev \
    libssl-dev \
    libicu-dev \
    libpq-dev \
    libedit-dev \
    libxslt-dev \
    default-mysql-client \
    # 清理缓存
    && rm -rf /var/lib/apt/lists/*

# 配置GD库（PHP 8.2必须指定路径）
RUN docker-php-ext-configure gd \
    --with-freetype=/usr/include/freetype2 \
    --with-jpeg=/usr/include \
    --with-webp=/usr/include

# 安装PHP扩展（移除xmlrpc）
RUN docker-php-ext-install \
    pdo pdo_mysql mysqli \
    mbstring zip gd exif \
    pcntl bcmath intl \
    opcache soap xml \
    curl sockets xsl

# 其他优化（可选）
RUN docker-php-source delete

# 安装必要扩展
RUN pecl install redis && docker-php-ext-enable redis


# 安装 Composer
RUN curl -sS https://getcomposer.org/installer | php -- --version=2.8.8 --install-dir=/usr/local/bin --filename=composer
RUN composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/


# 配置工作目录
WORKDIR /var/www

# 拷贝项目文件
COPY . /var/www

# 安装依赖
RUN composer install --working-dir=/var/www --no-dev --optimize-autoloader

RUN mkdir -p /var/log/webman && \
    touch /var/log/webman/webman_stdout.log && \
    touch /var/log/webman/webman_stderr.log && \
    chmod 777 /var/log/webman/webman_stdout.log /var/log/webman/webman_stderr.log
# 配置 Nginx
RUN rm /etc/nginx/sites-enabled/default
COPY ./nginx.conf /etc/nginx/nginx.conf

# 配置 Supervisor 来管理 Nginx
COPY ./supervisord.conf /etc/supervisor/conf.d/supervisord.conf

COPY init.sh /usr/local/bin/init.sh
RUN  chmod +x /usr/local/bin/init.sh

# 配置 Opcache
RUN { \
      echo 'opcache.memory_consumption=128'; \
      echo 'opcache.interned_strings_buffer=8'; \
      echo 'opcache.max_accelerated_files=4000'; \
      echo 'opcache.revalidate_freq=2'; \
      echo 'opcache.fast_shutdown=1'; \
      echo 'opcache.enable_cli=1'; \
    } > /usr/local/etc/php/conf.d/opcache.ini

EXPOSE 80

CMD ["/usr/local/bin/init.sh"]