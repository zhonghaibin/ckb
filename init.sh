#!/bin/bash
echo "启动应用服务"
echo "请通过 http://127.0.0.1:8080 进行访问"
# 启动 Supervisor
exec supervisord -c /etc/supervisor/conf.d/supervisord.conf

