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

class SolController extends Crud
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

        $query = $query->with('user')->where('transaction_type', TransactionTypes::SOL);
        return $this->doFormat($query, $format, $limit);
    }

    public function index(): Response
    {
        return raw_view('sol/index');
    }


    public function staticIncome(): Response
    {
        return raw_view('sol/staticIncome');
    }

    public function staticIncomes(Request $request)
    {

        $this->model = new TransactionLog;
        [$where, $format, $limit, $field, $order] = $this->selectInput($request);
        $query = $this->doSelect($where, $field, $order);
        return $this->doFormat($query, $format, $limit);
    }

    public function dynamicIncome(): Response
    {
        return raw_view('sol/dynamicIncome');
    }

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
