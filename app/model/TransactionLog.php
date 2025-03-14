<?php

namespace app\model;

use \app\model\Base;
use Illuminate\Database\Eloquent\Casts\Attribute;

class TransactionLog extends Base
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transaction_logs';

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

    protected function bonus(): Attribute
    {
        return Attribute::make(
            get: fn($value) => floatval($value)
        );
    }
}