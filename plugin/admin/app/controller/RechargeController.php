<?php

namespace plugin\admin\app\controller;

use app\enums\TransactionTypes;
use app\model\Banner;
use app\model\Exchange;
use app\model\Recharge;
use app\model\Transaction;
use app\model\TransactionLog;
use support\Request;
use support\Response;
use Throwable;

class RechargeController extends Crud
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
        $this->model = new Recharge();
    }

    protected function doFormats($query, $format, $limit, $totalData): Response
    {
        $paginator = $query->paginate($limit);
        $total = $paginator->total();
        $items = $paginator->items();
        if (method_exists($this, "afterQuery")) {
            $items = call_user_func([$this, "afterQuery"], $items);
        }
        $format_function = 'formatNormals';
        return call_user_func([$this, $format_function], $items, $total, $totalData);
    }

    protected function formatNormals($items, $total, $total_data): Response
    {
        return json(['code' => 0, 'msg' => 'ok', 'count' => $total, 'data' => $items, 'total' => $total_data]);
    }

    /**
     * 查询
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

        $cloneQuery1 = clone $query;
        $total_amount = $cloneQuery1->sum('total_amount');
        $cloneQuery2 = clone $query;
        $amount = $cloneQuery2->sum('amount');
        $query = $query->with('user');


        $totalData = [
            "amount" => floatval($amount),
            "total_amount" => floatval($total_amount)
        ];
        return $this->doFormats($query, $format, $limit, $totalData);

    }

    /**
     * 浏览
     * @return Response
     * @throws Throwable
     */
    public function index(): Response
    {
        return raw_view('recharge/index');
    }


}
