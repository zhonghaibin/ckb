<?php

namespace app\model;

use \app\model\Base;
use Illuminate\Database\Eloquent\Casts\Attribute;

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

    public function user()
    {

        return $this->belongsTo(User::class, 'user_id', 'id');

    }

    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn($value) => floatval($value)
        );
    }

    protected function balance(): Attribute
    {
        return Attribute::make(
            get: fn($value) => floatval($value)
        );
    }

    protected function rate(): Attribute
    {
        return Attribute::make(
            get: fn($value) => floatval($value)
        );
    }

}