<?php

namespace app\model;

use app\utils\AesUtil;
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
        return $this->hasMany(Assets::class, 'user_id', 'id');
    }

    protected $appends = ['share_link']; // 自动追加 full_name

    public function getShareLinkAttribute()
    {
        $code = AesUtil::encrypt($this->id);
        $config = get_system_config();
        $share_url = $config['base_info']['share_url'];
        return $share_url . $code;
    }
}