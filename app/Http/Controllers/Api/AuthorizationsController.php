<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\Api\AuthorizationRequest;
use Auth;

class AuthorizationsController extends Controller
{
    public function store(AuthorizationRequest $request)
    {
        $username = $request->username;

        filter_var($username, FILTER_VALIDATE_EMAIL) ?
            $credentials['email'] = $username :
            $credentials['name'] = $username;

        $credentials['password'] = $request->password;

        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return $this->responseError('用户名或密码错误');
        }

        return $this->responseSuccess([
            'access_token' => $token,
            'user' => $this->user(),
        ]);
    }

    public function update()
    {
        $token = Auth::guard('api')->refresh();
        return $this->responseData([
            'access_token' => $token,
        ]);
    }

    public function destroy()
    {
        Auth::guard('api')->logout();
        return $this->responseSuccess('退出成功');
    }
}
