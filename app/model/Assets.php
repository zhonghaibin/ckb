<?php

namespace app\model;

use \app\model\Base;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Assets extends Base
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'assets';

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

    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn($value) => floatval($value)
        );
    }

    protected function bonus(): Attribute
    {
        return Attribute::make(
            get: fn($value) => floatval($value)
        );
    }

}