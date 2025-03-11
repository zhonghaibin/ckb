<?php

namespace plugin\admin\app\controller;


use app\model\User;
use support\exception\BusinessException;
use support\Request;
use support\Response;
use Throwable;

/**
 * 用户管理
 */
class UserController extends Crud
{

    /**
     * @var User
     */
    protected $model = null;

    /**
     * 构造函数
     * @return void
     */
    public function __construct()
    {
        $this->model = new User;
    }

    /**
     * 浏览
     * @return Response
     * @throws Throwable
     */
    public function index(): Response
    {
        return raw_view('user/index');
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
        $query = $query->with('assets');
        return $this->doFormat($query, $format, $limit);
    }

    /**
     * 插入
     * @param Request $request
     * @return Response
     * @throws BusinessException|Throwable
     */
    public function insert(Request $request): Response
    {
        if ($request->method() === 'POST') {
            return parent::insert($request);
        }
        return raw_view('user/insert');
    }

    /**
     * 更新
     * @param Request $request
     * @return Response
     * @throws BusinessException|Throwable
     */
    public function update(Request $request): Response
    {
        if ($request->method() === 'POST') {
            return parent::update($request);
        }
        return raw_view('user/update');
    }

    /**
     * 浏览直推
     */
    public function direct(): Response
    {
        return raw_view('user/direct');
    }

    /**
     * 查询直推
     */
    public function directs(Request $request)
    {

        $this->model = new User;
        [$where, $format, $limit, $field, $order] = $this->selectInput($request);
        $query = $this->doSelect($where, $field, $order);
        $query = $query->with('assets');
        return $this->doFormat($query, $format, $limit);
    }

    /**
     * 浏览团队
     */
    public function team(): Response
    {
        return raw_view('user/team');
    }

    /**
     * 查询团队
     */
    public function teams(Request $request)
    {
        $user_id = $request->get('user_id');
        $user_ids = get_team_user_ids($user_id);
        $this->model = new User;
        [$where, $format, $limit, $field, $order] = $this->selectInput($request);
        $where['id'] = ['in', $user_ids];
        $query = $this->doSelect($where, $field, $order);
        $query = $query->with('assets');
        return $this->doFormat($query, $format, $limit);
    }
}
