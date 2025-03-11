<?php

namespace plugin\admin\app\controller;

use plugin\admin\app\common\Util;
use plugin\admin\app\model\Option;
use support\exception\BusinessException;
use support\Request;
use support\Response;
use Throwable;

/**
 * 系统设置
 */
class SystemConfigController extends Base
{
    /**
     * 不需要验证权限的方法
     * @var string[]
     */
    protected $noNeedAuth = ['get'];

    /**
     * 账户设置
     * @return Response
     * @throws Throwable
     */
    public function index(): Response
    {
        return raw_view('system_config/index');
    }

    /**
     * 获取配置
     * @return Response
     */
    public function get(): Response
    {
        return json($this->getByDefault());
    }

    /**
     * 基于配置文件获取默认权限
     * @return mixed
     */
    protected function getByDefault()
    {
        $name = 'config';
        $config = Option::where('name', $name)->value('value');
        if (empty($config)) {
            $config = file_get_contents(base_path('plugin/admin/public/config/system.config.json'));
            if ($config) {
                $option = new Option();
                $option->name = $name;
                $option->value = $config;
                $option->save();
            }
        }
        return json_decode($config, true);
    }

    /**
     * 更改
     * @param Request $request
     * @return Response
     * @throws BusinessException
     */
    public function update(Request $request): Response
    {
        $post = $request->post();
        $config = $this->getByDefault();
        $data = [];

        foreach ($post as $section => $items) {
            if (!isset($config[$section])) {
                continue;
            }
            switch ($section) {
                case 'base_info':
                    $data[$section]['maintenance_mode'] = $items['maintenance_mode'] ?? false;
                    $data[$section]['maintenance_message'] = $items['maintenance_message'] ?? '';
                    $data[$section]['web_url'] = $items['web_url'] ?? '';
                    $data[$section]['share_url'] = $items['share_url'] ?? '';
                    $data[$section]['wallet_address'] = $items['wallet_address'] ?? '';
                    $data[$section]['ckb_min_number'] = $items['ckb_min_number'] ?? 500;
                    $data[$section]['sol_min_number'] = $items['sol_min_number'] ?? 500;
                    $data[$section]['exchange_min_number'] = $items['exchange_min_number'] ?? 1;
                    $data[$section]['withdraw_min_number'] = $items['withdraw_min_number'] ?? 100;
                    $data[$section]['withdraw_fee_rate'] = $items['withdraw_fee_rate'] ?? 0;
                    break;
                case 'ckb':
                    $flatArray = json_decode($post[$section], true);
                    $items = parse_nested_array($flatArray);
                    $data[$section] = $items;
                    break;
                case 'sol':
                    $flatArray = json_decode($post[$section], true);
                    $items = parse_nested_array($flatArray);
                    $data[$section] = $items;
            }
        }
        $config = array_merge($config, $data);
        $name = 'config';
        Option::where('name', $name)->update([
            'value' => json_encode($config)
        ]);
        return $this->json(0);
    }

    /**
     * 颜色检查
     * @param string $color
     * @return string
     * @throws BusinessException
     */
    protected function filterColor(string $color): string
    {
        if (!preg_match('/\#[a-zA-Z]6/', $color)) {
            throw new BusinessException('参数错误');
        }
        return $color;
    }

}
