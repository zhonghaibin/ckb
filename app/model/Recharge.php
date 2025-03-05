<?php

namespace app\model;

use \app\model\Base;

class Recharge extends Base
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'logined_log';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
}