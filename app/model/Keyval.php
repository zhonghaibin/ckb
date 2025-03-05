<?php

namespace app\model;

use \app\model\Base;

class Keyval extends Base
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'keyval';

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