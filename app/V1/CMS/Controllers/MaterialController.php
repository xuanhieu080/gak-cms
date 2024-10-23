<?php

namespace App\V1\CMS\Controllers;

use App\Supports\GAK_ERROR;
use App\V1\CMS\Models\MaterialModel;
use App\V1\CMS\Requests\Material\CreateRequest;
use App\V1\CMS\Requests\Material\SyncWarehouseRequest;
use App\V1\CMS\Requests\Material\UpdateRequest;
use App\V1\CMS\Resources\Materials\MaterialResource;
use App\V1\CMS\Resources\Materials\MaterialShortResource;
use App\V1\CMS\Resources\Materials\MaterialWarehouseResource;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Throwable;

class MaterialController extends Controller
{

    /**
     * The service instance
     * @var MaterialModel
     */
    private MaterialModel $model;

    /**
     * Constructor
     */
    public function __construct(MaterialModel $model)
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
            return $this->responseIndex(MaterialShortResource::collection($data));
        }

        return $this->responseIndex(MaterialResource::collection($data));
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
            $response = GAK_ERROR::handle($exception, 'materials');

            return $this->responseStoreFail($response['message']);
        }
        return $this->responseStoreSuccess('', ['item' => new MaterialResource($data)]);
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
            $response = GAK_ERROR::handle($exception, 'materials');

            return $this->responseFail($response['message']);
        }
        return $this->responseSuccess('', ['item' => new MaterialResource($item)]);
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
            $data = $this->model->update($input, ['materialWarehouses']);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            $response = GAK_ERROR::handle($exception, 'materials');

            return $this->responseUpdateFail($response['message']);
        }

        return $this->responseUpdateSuccess('', ['item' => new MaterialResource($data)]);
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

    public function syncWarehouse(SyncWarehouseRequest $request, $id): JsonResponse
    {
        try {
            DB::beginTransaction();
            $input = $request->validated();
            $input['id'] = $id;
            $this->model->syncWarehouse($id, $input);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            $response = GAK_ERROR::handle($exception, 'materials');

            return $this->responseUpdateFail($response['message']);
        }

        return $this->responseUpdateSuccess('Cập nhật dữ liệu thành công');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param $id
     * @return JsonResponse
     * @throws Exception
     */
    public function getWarehouse($id): JsonResponse
    {
        try {
            $data = $this->model->getWarehouse($id);
        } catch (\Exception $exception) {
            $response = GAK_ERROR::handle($exception, 'materials');

            return $this->responseUpdateFail($response['message']);
        }

        return $this->response(200, '', ['data' => MaterialWarehouseResource::collection($data)]);
    }
}
