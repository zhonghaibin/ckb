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

    function parseNestedArray($flatArray) {
        $nestedArray = [];

        foreach ($flatArray as $flatKey => $value) {
            $keys = [];
            if (preg_match_all('/\[(.*?)\]/', $flatKey, $matches)) {
                $mainKey = strstr($flatKey, '[', true);
                $keys = $matches[1];
            } else {
                $mainKey = $flatKey;
            }

            $ref = &$nestedArray;
            if (!empty($keys)) {
                if (!isset($nestedArray[$mainKey])) {
                    $nestedArray[$mainKey] = [];
                }
                $ref = &$nestedArray[$mainKey];

                foreach ($keys as $i => $key) {
                    if (!isset($ref[$key])) {
                        $ref[$key] = [];
                    }
                    if ($i === count($keys) - 1) {
                        $ref[$key] = $value;
                    } else {
                        $ref = &$ref[$key];
                    }
                }
            } else {
                $nestedArray[$mainKey] = $value;
            }
        }

        return $nestedArray;
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
                    $data[$section]['withdraw_fee_rate'] = $items['withdraw_fee_rate'] ?? 0;
                    break;
                case 'ckb':
                    $flatArray = json_decode($post[$section], true);
                    $items = $this->parseNestedArray($flatArray);
                    $data[$section]=$items;
                    break;
                case 'sol':
                    $flatArray = json_decode($post[$section], true);
                    $items = $this->parseNestedArray($flatArray);
                    $data[$section]=$items;
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
