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
    function json_success($data = null, string $message = '操作成功', int $code = 200)
    {
        return json([
            'code' => $code,
            'message' => $message,
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


