<?php

namespace app\model;

use \app\model\Base;

class AssetsLog extends Base
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'assets_logs';

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