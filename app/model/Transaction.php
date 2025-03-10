<?php

namespace app\model;

use \app\model\Base;

class Transaction extends Base
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transactions';

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

    public function user(){

        return $this->belongsTo(User::class,'user_id','id');

    }
}