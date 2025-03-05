<?php

namespace app\model;

use Illuminate\Database\Eloquent\Relations\HasMany;
use app\model\Base;

class User extends Base
{


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

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

    public function assets(): HasMany
    {
        return $this->hasMany(Assets::class,'user_id','id');
    }
}