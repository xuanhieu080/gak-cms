<?php

namespace App\V1\CMS\Controllers;

use App\Supports\GAK_ERROR;
use App\V1\CMS\Models\WarehouseModel;
use App\V1\CMS\Requests\Warehouse\CreateRequest;
use App\V1\CMS\Requests\Warehouse\UpdateRequest;
use App\V1\CMS\Resources\Warehouses\WarehouseResource;
use App\V1\CMS\Resources\Warehouses\WarehouseShortResource;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Throwable;

class WarehouseController extends Controller
{

    /**
     * The service instance
     * @var WarehouseModel
     */
    private WarehouseModel $model;

    /**
     * Constructor
     */
    public function __construct(WarehouseModel $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return ResourceCollection
     */
    public function index(Request $request): ResourceCollection
    {
        $input = $request->all();
        $limit = Arr::get($input, 'limit', 999);
        $input['sort'] = ['name' => 'asc'];

        $data = $this->model->search($input, [], $limit);
        if (isset($input['short'])) {
            return $this->responseIndex(WarehouseShortResource::collection($data));
        }

        return $this->responseIndex(WarehouseResource::collection($data));
    }

    /**
     * Show the form for creating a new resource.
     * @param CreateRequest $request
     * @return JsonResponse
     * @throws Throwable
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
        return $this->responseStoreSuccess('', ['item' => new WarehouseResource($data)]);
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
        return $this->responseSuccess('', ['item' => new WarehouseResource($item)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest $request
     * @param $id
     * @return JsonResponse
     * @throws Throwable
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

        return $this->responseUpdateSuccess('', ['item' => new WarehouseResource($data)]);
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
    public function delete(int $id): JsonResponse
    {
        if ($this->model->deleteModel($id)) {
            return $this->responseDeleteSuccess();
        }

        return $this->responseDeleteFail();
    }
}
