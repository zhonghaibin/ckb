#!/bin/bash

# 要检查和复制的目标目录，例如 /var/www:::::
#TARGET_DIR="/var/www/"
# 设置正确的权限
#chown -R www-data:www-data $TARGET_DIR&& chmod 755 -Rf /var/www/   && chmod 777 -Rf /var/www/public
echo "启动应用服务"
echo "请通过 http://127.0.0.1:8080 进行访问"
# 启动 Supervisor
exec supervisord -c /etc/supervisor/conf.d/supervisord.conf

