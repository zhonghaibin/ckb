<?php
return [
//    'consumer'  => [
//        'handler'     => Webman\RedisQueue\Process\Consumer::class,
//        'count'       => 8, // 可以设置多进程同时消费
//        'constructor' => [
//            // 消费者类目录
//            'consumer_dir' => app_path() . '/queue/redis'
//        ]
//    ],
    'redis_consumer_fast'  => [ // key是自定义的，没有格式限制，这里取名redis_consumer_fast
        'handler'     => Webman\RedisQueue\Process\Consumer::class,
        'count'       => 8,
        'constructor' => [
            // 消费者类目录
            'consumer_dir' => app_path() . '/queue/redis/fast'
        ]
    ],
    'redis_consumer_slow'  => [  // key是自定义的，没有格式限制，这里取名redis_consumer_slow
        'handler'     => Webman\RedisQueue\Process\Consumer::class,
        'count'       => 8,
        'constructor' => [
            // 消费者类目录
            'consumer_dir' => app_path() . '/queue/redis/slow'
        ]
    ]
];