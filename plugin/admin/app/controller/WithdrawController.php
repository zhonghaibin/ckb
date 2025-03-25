<?php

namespace plugin\admin\app\controller;

use app\enums\TransactionTypes;
use app\enums\WithdrawStatus;
use app\model\Banner;
use app\model\Exchange;
use app\model\Recharge;
use app\model\Transaction;
use app\model\TransactionLog;
use app\model\Withdraw;
use app\services\AssetsService;
use support\exception\BusinessException;
use support\Request;
use support\Response;
use Throwable;

class WithdrawController extends Crud
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
        $this->model = new Withdraw();
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
     */
    public function index(): Response
    {
        return raw_view('withdraw/index');
    }

    /**
     * 审核
     */
    public function update(Request $request): Response
    {
        if ($request->method() === 'POST') {

            $withdraw = $this->model::query()->find($request->post('id'));
            $status = $request->post('status');
            $signature = $request->post('signature');
            $remark = $request->post('remark');
            if ($withdraw->status > WithdrawStatus::PENDING->value && $withdraw->status != $status) {
                throw new BusinessException('不能重复修改审核状态', 2);
            }

            if ($status == WithdrawStatus::FAILED->value && $withdraw->status == WithdrawStatus::PENDING->value) {
                $assets = new AssetsService();
                try {
                    $assets->refund($withdraw->id, $withdraw->coin);
                } catch (\Throwable $e) {
                    throw new BusinessException($e->getMessage(), 2);
                }
            }
            $withdraw->signature=$signature;
            $withdraw->status=$status;
            $withdraw->remark=$remark;
            $withdraw->save();
            return $this->json(0);

        }
        return raw_view('withdraw/update');
    }

}
