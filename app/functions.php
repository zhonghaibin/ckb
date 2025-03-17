<?php
/**
 * Here is your custom functions.
 */

use support\Db;

if (!function_exists('json_success')) {
    /**
     * 返回成功的 JSON 响应
     *
     * @param mixed $data 返回的数据
     * @param string $message 提示信息
     * @param int $code 状态码
     * @return \support\Response
     */
    function json_success($data = null, string $message = null, int $code = 200)
    {
        return json([
            'code' => $code,
            'message' => $message ?? \app\support\Lang::get('success'),
            'data' => $data,
        ]);
    }
}

if (!function_exists('json_fail')) {
    /**
     * 返回失败的 JSON 响应
     *
     * @param string $message 错误信息
     * @param int $code 状态码
     * @param mixed $data 额外数据
     * @return \support\Response
     */
    function json_fail(string $message = '操作失败', int $code = 400, $data = null)
    {
        return json([
            'code' => $code,
            'message' => $message,
            'data' => $data,
        ]);
    }
}

if (!function_exists('get_time_in_month')) {
    function get_time_in_month($timestamp)
    {
        $month = date('n', $timestamp); // 获取月份（1-12）
        $year = date('Y', $timestamp);  // 获取年份
        $days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        return $days_in_month;
    }
}

if (!function_exists('get_team_count')) {
    function get_team_count($userId, $isReal = false)
    {
        $ids = [$userId]; // 记录所有下级用户ID
        $team_count = 0;

        do {
            // 查询当前层级的下级用户
            $subUsers = Db::table('users')
                ->whereIn('pid', $ids)
                ->pluck('id')
                ->toArray();
            if ($isReal) {
                $count = Db::table('users')
                    ->whereIn('pid', $ids)
                    ->where('is_real', 1)
                    ->pluck('id')
                    ->count();
                $team_count += $count;
            } else {
                $team_count += count($subUsers);
            }
            $ids = $subUsers; // 继续查询下一层级
        } while (!empty($ids));

        return $team_count;
    }
}

if (!function_exists('get_team_user_ids')) {
    function get_team_user_ids($user_id)
    {
        $team_ids = [];

        // 查询所有直接下级
        $sub_users = Db::table('users')->where('pid', $user_id)->pluck('id')->toArray();

        foreach ($sub_users as $sub_id) {
            $team_ids[] = $sub_id;
            // 递归查找下级的下级
            $team_ids = array_merge($team_ids, get_team_user_ids($sub_id));
        }

        return $team_ids;
    }
}
if (!function_exists('parse_nested_array')) {
    function parse_nested_array($flatArray)
    {
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

}

if (!function_exists('get_system_config')) {

    function get_system_config()
    {
        $config = Db::table('options')->where('name', 'config')->value('value');
        return json_decode($config, true);
    }
}

if (!function_exists('get_transaction_by_signature')) {
    function get_transaction_by_signature($signature)
    {
        $url = "https://api.mainnet-beta.solana.com";

        $data = [
            "jsonrpc" => "2.0",
            "id" => 1,
            "method" => "getTransaction",
            "params" => [
                $signature,  // 传入交易签名
                ["encoding" => "jsonParsed"]        // 返回格式为 JSON
            ]
        ];

        $options = [
            "http" => [
                "header" => "Content-Type: application/json",
                "method" => "POST",
                "content" => json_encode($data)
            ]
        ];

        // 创建请求上下文
        $context = stream_context_create($options);

        // 发送请求并获取响应
        $response = file_get_contents($url, false, $context);

        // 返回解析后的交易信息
        return json_decode($response, true);
    }
}

if (!function_exists('parse_solana_transaction')) {
    function parse_solana_transaction($transaction)
    {
        $result = [];
        $preBalances = [];

        // 记录 preTokenBalances (交易前余额)
        foreach ($transaction['preTokenBalances'] as $pre) {
            $preBalances[$pre['owner']] = $pre['uiTokenAmount']['uiAmount'];
        }

        // 计算 payer 和 receiver，以及转账金额
        foreach ($transaction['postTokenBalances'] as $post) {
            $owner = $post['owner'];
            $previousBalance = $preBalances[$owner] ?? 0;  // 默认 0
            $currentBalance = $post['uiTokenAmount']['uiAmount'];

            if ($previousBalance > $currentBalance) {
                // 付款人（余额减少）
                $result['payer'] = $owner;
                $result['amount'] = $previousBalance - $currentBalance;
            } elseif ($previousBalance < $currentBalance) {
                // 收款人（余额增加）
                $result['receiver'] = $owner;
            }
        }

        return $result;
    }


}