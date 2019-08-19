<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\UserRequest;
use App\Models\Topic;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use App\Models\Image;

use App\Models\User;

class UsersController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'captcha_key' => 'required',
            'captcha_code' => 'required',
            'name' => 'required|between:3,25|regex:/^[A-Za-z0-9\-\_]+$/|unique:users,name',
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $captchaData = \Cache::get($request->captcha_key);
        if (!$captchaData) {
            return $this->response->error('图片验证码已失效', 422);
        }

        if (!hash_equals($captchaData['code'], $request->captcha_code)) {
            // 验证错误就清除缓存
            \Cache::forget($request->captcha_key);
            return $this->response->errorUnauthorized('验证码错误');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return $this->responseSuccess('创建成功');
    }

    public function me()
    {
        return $this->responseItem($this->user(), new UserTransformer());
    }

    public function update(Request $request)
    {
        $user = $this->user();
        $this->validate($request, [
            'name' => 'between:3,25|regex:/^[A-Za-z0-9\-\_]+$/|unique:users,name,' . $user->id,
            'email' => 'email',
            'introduction' => 'max:80',
            'avatar_image_id' => 'exists:images,id,type,avatar,user_id,'. $user->id,
        ]);

        $attributes = $request->only(['name', 'email', 'introduction']);
        if ($request->avatar_image_id) {
            $image = Image::find($request->avatar_image_id);
            $attributes['avatar'] = $image->path;
        }
        $user->update($attributes);
        return $this->responseItem($user, new UserTransformer());
    }
}
