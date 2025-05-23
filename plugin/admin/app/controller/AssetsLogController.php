<?php

namespace plugin\admin\app\controller;

use app\enums\TransactionTypes;
use app\model\AssetsLog;
use app\model\Banner;
use app\model\Exchange;
use app\model\Recharge;
use app\model\Transaction;
use app\model\TransactionLog;
use support\Request;
use support\Response;
use Throwable;

class AssetsLogController extends Crud
{

    /**
     * @var Banner
     */
    protected $model = null;

    /**
     * 构造函数
     * @return void
     */
    public function __construct()
    {
        $this->model = new AssetsLog;
    }

    /**
     * 查询
     * @param Request $request
     * @return Response
     */
    public function select(Request $request): Response
    {
        [$where, $format, $limit, $field, $order] = $this->selectInput($request);
        $query = $this->doSelect($where, $field, $order);
        $identity = $request->get('identity');
        $query = $query->when($identity, function ($query) use ($identity) {
            return $query->whereHas('user', function ($query) use ($identity) {
                return $query->where('identity', $identity);
            });
        });

        $query = $query->with('user');
        return $this->doFormat($query, $format, $limit);
    }

    /**
     * 浏览
     * @return Response
     * @throws Throwable
     */
    public function index(): Response
    {
        return raw_view('assets_log/index');
    }

}
