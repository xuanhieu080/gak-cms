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
    protected function responseDataSuccess($message, array $data): JsonResponse
    {
        return $this->responseSuccess($message, $data);
    }

    /**
     * Send a successful response
     *
     * @param string $message
     * @param array $data
     * @param int $code
     * @return JsonResponse
     */
    protected function responseDeleteSuccess($message = 'Xoá dữ liệu thành công', array $data = [], int $code = 200): JsonResponse
    {
        if (empty($message)) {
            $message = 'Xoá dữ liệu thành công';
        }
        return $this->responseSuccess($message, $data, $code);
    }


    /**
     * Send a failed response
     *
     * @param string $message
     * @param array $data
     * @param int $code
     * @return JsonResponse
     */
    protected function responseDeleteFail(string $message = 'Xoá dữ liệu thất bại', array $data = [], int $code = 400): JsonResponse
    {
        if (empty($message)) {
            $message = 'Xoá dữ liệu thất bại';
        }
        return $this->responseFail($message, $data, $code);
    }

    /**
     * Send a successful response
     *
     * @param string $message
     * @param array $data
     * @param int $code
     * @return JsonResponse
     */
    protected function responseUpdateSuccess(string $message = 'Cập nhật dữ liệu thành công', array $data = [], int $code = 200): JsonResponse
    {
        if (empty($message)) {
            $message = 'Cập nhật dữ liệu thành công';
        }
        return $this->responseSuccess($message, $data, $code);
    }


    /**
     * Send a failed response
     *
     * @param string $message
     * @param array $data
     * @param int $code
     * @return JsonResponse
     */
    protected function responseUpdateFail(string $message = 'Cập nhật dữ liệu thất bại', array $data = [], int $code = 400): JsonResponse
    {
        if (empty($message)) {
            $message = 'Cập nhật dữ liệu thất bại';
        }
        return $this->responseFail($message, $data, $code);
    }

    /**
     * Send a successful response
     *
     * @param string $message
     * @param array $data
     * @param int $code
     * @return JsonResponse
     */
    protected function responseStoreSuccess(string $message = 'Thêm dữ liệu thành công', array $data = [], int $code = 200): JsonResponse
    {
        if (empty($message)) {
            $message = 'Thêm dữ liệu thành công';
        }
        return $this->responseSuccess($message, $data, $code);
    }


    /**
     * Send a failed response
     *
     * @param string $message
     * @param array $data
     * @param int $code
     * @return JsonResponse
     */
    protected function responseStoreFail(string $message = 'Thêm dữ liệu thất bại', array $data = [], int $code = 400): JsonResponse
    {
        if (empty($message)) {
            $message = 'Thêm dữ liệu thất bại';
        }
        return $this->responseFail($message, $data, $code);
    }

    /**
     * Send a successful response
     *
     * @param string $message
     * @param array $data
     * @param int $code
     * @return JsonResponse
     */
    protected function responseSuccess(string $message, array $data = [], int $code = 200): JsonResponse
    {
        return $this->response($code, $message, $data);
    }

    /**
     * Send a failed response
     *
     * @param string $message
     * @param array $data
     * @param int $code
     * @return JsonResponse
     */
    protected function responseFail(string $message, array $data = [], int $code = 400): JsonResponse
    {
        return $this->response($code, $message, $data);
    }

    /**
     * Returns a response
     * @param int $code
     * @param string $message
     * @param array $data
     * @return JsonResponse
     */
    protected function response(int $code, string $message = '', array $data = []): JsonResponse
    {
        $isError = 200 <= $code && $code < 400;
        return response()->json(array_merge(['message' => $message, 'code' => $code, 'status' => $isError], $data), $code);
    }
}
