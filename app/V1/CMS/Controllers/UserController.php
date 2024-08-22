<?php

namespace App\V1\CMS\Controllers;

use App\Supports\GAK_ERROR;
use App\V1\CMS\Models\UserModel;
use App\V1\CMS\Requests\Users\CreateRequest;
use App\V1\CMS\Requests\Users\UpdateAvatarRequest;
use App\V1\CMS\Requests\Users\UpdateRequest;
use App\V1\CMS\Resources\UserResource;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    /**
     * The service instance
     * @var UserModel
     */
    private UserModel $model;

    /**
     * Constructor
     */
    public function __construct(UserModel $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     * @return JsonResponse|AnonymousResourceCollection
     * @throws AuthorizationException
     */
    public function index(Request $request)
    {
        $input = $request->all();
        $limit = Arr::get($input, 'limit', 999);
        $input['is_active'] = 1;

        $input['sort'] = ['id' => 'desc'];

        $data = $this->model->search($input, ['group'], $limit);

        return UserResource::collection($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(CreateRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $input = $request->validated();
            $data = $this->model->store($input);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            $response = GAK_ERROR::handle($exception, 'users');

            return $this->responseStoreFail($response['message']);
        }
        return $this->responseStoreSuccess('', ['item' => new UserResource($data)]);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function detail($id): JsonResponse
    {
        try {
            $item = $this->model->detail($id);
        } catch (\Exception $exception) {
            $response = GAK_ERROR::handle($exception, 'users');

            return $this->responseFail($response['message']);
        }
        return $this->responseSuccess('', ['item' => new UserResource($item)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest $request
     * @param $id
     * @return JsonResponse
     * @throws \Throwable
     */
    public function update(UpdateRequest $request, $id): JsonResponse
    {
        try {
            DB::beginTransaction();
            $input = $request->validated();
            $input['id'] = $id;
            $data = $this->model->update($input);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            $response = GAK_ERROR::handle($exception, 'users');

            return $this->responseUpdateFail($response['message']);
        }
        return $this->responseUpdateSuccess('', ['item' => new UserResource($data)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateAvatarRequest $request
     * @param $id
     * @return JsonResponse
     * @throws \Throwable
     */
    public function updateAvatar(UpdateAvatarRequest $request, $id): JsonResponse
    {
        try {
            DB::beginTransaction();
            $input = $request->validated();
            $input['id'] = $id;
            $data = $this->model->updateAvatar($input);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            $response = GAK_ERROR::handle($exception, 'users');

            return $this->responseUpdateFail($response['message']);
        }
        return $this->responseUpdateSuccess('', ['item' => new UserResource($data)]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return JsonResponse
     * @throws AuthorizationException
     * @throws Exception
     */
    public function delete($id)
    {
        if ($this->model->deleteModel($id)) {
            return $this->responseDeleteSuccess();
        }

        return $this->responseDeleteFail();

    }
}
