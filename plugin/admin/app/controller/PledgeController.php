<?php

namespace plugin\admin\app\controller;

use app\enums\AssetsLogTypes;
use app\enums\TransactionTypes;
use app\model\AssetsLog;
use app\model\Banner;
use app\model\Transaction;
use app\model\TransactionLog;
use support\Request;
use support\Response;
use Throwable;

class PledgeController extends Crud
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
        $this->model = new Transaction;
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
        $query = $query->whereHas('user', function ($query) use ($identity) {
            return $query->when($identity, function ($query) use ($identity) {
                return $query->where('identity', $identity);
            });
        });

        $query = $query->with('user')->where('transaction_type', TransactionTypes::PLEDGE);
        return $this->doFormat($query, $format, $limit);
    }

    /**
     * 浏览
     * @return Response
     * @throws Throwable
     */
    public function index(): Response
    {
        return raw_view('pledge/index');
    }


    /**
     * 浏览收益
     * @return Response
     * @throws Throwable
     */
    public function show(): Response
    {
        return raw_view('pledge/show');
    }

    /**
     * 查询收益
     * @param Request $request
     * @return Response
     */
    public function ckbLogs(Request $request)
    {

        $this->model = new TransactionLog;
        [$where, $format, $limit, $field, $order] = $this->selectInput($request);
        $query = $this->doSelect($where, $field, $order);
        return $this->doFormat($query, $format, $limit);
    }

    /**
     * 浏览静态收益
     * @return Response
     * @throws Throwable
     */
    public function staticIncome(): Response
    {
        return raw_view('pledge/staticIncome');
    }

    /**
     * 查询静态收益
     * @param Request $request
     * @return Response
     */
    public function staticIncomes(Request $request)
    {

        $this->model = new TransactionLog;
        [$where, $format, $limit, $field, $order] = $this->selectInput($request);
        $query = $this->doSelect($where, $field, $order);
        return $this->doFormat($query, $format, $limit);
    }

    /**
     * 浏览动态收益
     * @return Response
     * @throws Throwable
     */
    public function dynamicIncome(): Response
    {
        return raw_view('pledge/dynamicIncome');
    }

    /**
     * 查询动态收益
     * @param Request $request
     * @return Response
     */
    public function dynamicIncomes(Request $request)
    {

        $this->model = new AssetsLog;
        [$where, $format, $limit, $field, $order] = $this->selectInput($request);
        $query = $this->doSelect($where, $field, $order);
        $identity = $request->get('identity');
        $query = $query->whereHas('user', function ($query) use ($identity) {
            return $query->when($identity, function ($query) use ($identity) {
                return $query->where('identity', $identity);
            });
        });

        $query = $query->with('user');
        $query = $query->whereIn('type', [AssetsLogTypes::DIRECTBONUS, AssetsLogTypes::LEVELDIFFBONUS, AssetsLogTypes::SAMELEVELBONUS]);
        return $this->doFormat($query, $format, $limit);
    }
}
