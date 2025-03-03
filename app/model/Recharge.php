<?php

namespace app\model;

use support\Model;

class Recharge extends Model
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
    public $timestamps = false;
}