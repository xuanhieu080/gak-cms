<?php

namespace App\V1\CMS\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Returns the current user
     * @return User|\Illuminate\Contracts\Auth\Authenticatable|null
     */
    protected function getCurrentUser()
    {
        return Auth::check() ? Auth::user() : null;
    }

    /**
     * Send data response
     * @param array $data
     * @return JsonResponse
     */
    protected function responseDataSuccess(array $data)
    {
        return $this->responseSuccess('', $data);
    }

    /**
     * Send a successful response
     *
     * @param $data
     * @param $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseDeleteSuccess($data = [], $code = 200)
    {
        return $this->responseSuccess('Xoá thông tin thành công', $data, $code);
    }


    /**
     * Send a failed response
     *
     * @param  array  $data
     * @param $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseDeleteFail(array $data = [], int $code = 422)
    {
        return $this->responseFail("Xoá thông tin không thành công", $data, $code);
    }

    /**
     * Send a successful response
     *
     * @param $data
     * @param $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseUpdateSuccess($data = [], $code = 200)
    {
        return $this->responseSuccess('Cập nhật thông tin thành công', $data, $code);
    }


    /**
     * Send a failed response
     *
     * @param  array  $data
     * @param $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseUpdateFail(array $data = [], int $code = 422)
    {
        return $this->responseFail('Cập nhật thông tin bị lỗi', $data, $code);
    }

    /**
     * Send a successful response
     *
     * @param $data
     * @param $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseStoreSuccess($data = [], $code = 200)
    {
        return $this->responseSuccess("Thêm thông tin thành công", $data, $code);
    }


    /**
     * Send a failed response
     *
     * @param  array  $data
     * @param $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseStoreFail(array $data = [], int $code = 422)
    {
        return $this->responseFail("Thêm thông tin thất bại", $data, $code);
    }

    /**
     * Send a successful response
     *
     * @param  string  $message
     * @param  array  $data
     * @param  int  $code
     * @return JsonResponse
     */
    protected function responseSuccess(string $message, array $data = [], int $code = 200)
    {
        return $this->response($code, $message, $data);
    }

    /**
     * Send a failed response
     *
     * @param  string  $message
     * @param  array  $data
     * @param  int  $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseFail(string $message, array $data = [], int $code = 400)
    {
        return $this->response($code, $message, $data);
    }

    /**
     * Returns a response
     * @param  int  $code
     * @param  string  $message
     * @param  array  $data
     * @return JsonResponse
     */
    protected function response(int $code, string $message = '', array $data = [])
    {
        return response()->json(array_merge(['message' => $message], $data), $code);
    }
}
