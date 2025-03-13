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

    /**
     * 查询
     */
    public function select(Request $request): Response
    {
        [$where, $format, $limit, $field, $order] = $this->selectInput($request);
        $query = $this->doSelect($where, $field, $order);
        $identity = $request->get('identity');
        $query = $query->whereHas('user', function ($query) use ($identity) {
            return $query->when($identity, function ($query) use ($identity) {
                return $query->where('identity', $identity);
            });
        });

        $query = $query->with('user');
        return $this->doFormat($query, $format, $limit);
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
