<?php
/**
 * Here is your custom functions.
 */

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
