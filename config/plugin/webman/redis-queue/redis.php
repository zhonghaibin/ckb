<?php
return [
    'default' => [
        'host' => 'redis://192.168.191.24:6379',
        'options' => [
            'auth' => '123456',       // 密码，字符串类型，可选参数
            'db' => 1,            // 数据库
            'prefix' => '',       // key 前缀
            'max_attempts'  => 5, // 消费失败后，重试次数
            'retry_seconds' => 5, // 重试间隔，单位秒
        ]
    ],
];
