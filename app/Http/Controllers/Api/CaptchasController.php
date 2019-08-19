<?php

namespace App\Http\Controllers\Api;

use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Http\Request;

class CaptchasController extends Controller
{
    public function show(CaptchaBuilder $captchaBuilder)
    {
        $captcha = $captchaBuilder->build();
        $key = 'captcha-'.str_random(15);
        $expiredAt = now()->addMinutes(2);
        \Cache::put($key, [
            'code' => $captcha->getPhrase(),
        ],$expiredAt);
        $data = [
            'captcha_key' => $key,
            'captcha_code' => $captcha->getPhrase(),
            'expired_at' => $expiredAt->toDateTimeString(),
//            'captcha_code' => $captcha->inline(),
        ];
        return $this->responseData($data);
    }
}
