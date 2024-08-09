<?php


namespace App\CMS\Controllers;


use App\Http\Controllers\Controller;
use App\Supports\CMS_ERROR;
use App\CMS\Models\UserModel;
use App\CMS\Requests\ForgotPasswordRequest;
use App\CMS\Requests\LoginRequest;
use App\CMS\Requests\NewPasswordRequest;
use App\CMS\Requests\OtpRequest;
use App\CMS\Resources\UserResource;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected UserModel $model;

    public function __construct()
    {
        $this->model = new UserModel();
    }

    public function login(LoginRequest $request): UserResource|JsonResponse
    {
        try {
            $user = $request->authenticate();
            $request->session()->regenerate();
        } catch (\Exception $exception) {
            $response = CMS_ERROR::handle($exception, $this->model->getTable());
            return response()->json(['message' => $response['message']], $response['code']);
        }
        return new UserResource($user);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->json(['message' => 'Đăng xuất thành công']);
    }

    /**
     * @param Request $request
     * @return Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|Application
    {
        return view('app');
    }

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        try {
            $input = $request->all();
            $this->model->forgotPassword($input['email']);
        } catch (\Exception $exception) {
            $response = CMS_ERROR::handle($exception, $this->model->getTable());
            return response()->json(['message' => $response['message']], $response['code']);
        }
        return response()->json(['message' => 'Quên mật khẩu'], 200);
    }

    public function otp(OtpRequest $request)
    {
        return response()->json(['message' => 'Nhập OTP thành công'], 200);
    }

    public function newPassword(NewPasswordRequest $request)
    {
        try {
            $input = $request->all();
            $this->model->newPassword($input);
        } catch (\Exception $exception) {
            $response = CMS_ERROR::handle($exception, $this->model->getTable());
            return response()->json(['message' => $response['message']], $response['code']);
        }
        return response()->json(['message' => 'Tạo mật khẩu mới thành công'], 200);
    }
}
