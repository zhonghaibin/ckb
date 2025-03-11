<?php
namespace app\middleware;

use Webman\Http\Request;
use Webman\Http\Response;
use app\support\Lang;

class DetectLanguage
{
    public function process(Request $request, callable $next): Response
    {
        // 语言优先级： query > cookie > header > 默认值
        $lang = $request->get('lang') ?? $request->cookie('lang') ?? $request->header('Accept-Language', 'zh_CN');


        Lang::setLang($lang);

        return $next($request);
    }
}
