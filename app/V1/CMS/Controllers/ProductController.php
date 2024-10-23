<?php

namespace App\V1\CMS\Controllers;

use App\Supports\GAK_ERROR;
use App\V1\CMS\Models\ProductModel;
use App\V1\CMS\Requests\Products\CreateRequest;
use App\V1\CMS\Requests\Products\SyncAttributeRequest;
use App\V1\CMS\Requests\Products\UpdateRequest;
use App\V1\CMS\Requests\Products\Warehouses\SyncRequest;
use App\V1\CMS\Resources\AttributeGroupShortResource;
use App\V1\CMS\Resources\Products\ProductResource;
use App\V1\CMS\Resources\Products\ProductShortResource;
use App\V1\CMS\Resources\Products\ProductWarehouseResource;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Throwable;

class ProductController extends Controller
{

    /**
     * The service instance
     * @var ProductModel
     */
    private ProductModel $model;

    /**
     * Constructor
     */
    public function __construct(ProductModel $model)
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
            return $this->responseIndex(ProductShortResource::collection($data));
        }

        return $this->responseIndex(ProductResource::collection($data));
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
            $response = GAK_ERROR::handle($exception, 'customers');

            return $this->responseStoreFail($response['message']);
        }
        return $this->responseStoreSuccess('', ['item' => new ProductResource($data)]);
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
            $response = GAK_ERROR::handle($exception, 'customers');

            return $this->responseFail($response['message']);
        }
        return $this->responseSuccess('', ['item' => new ProductResource($item)]);
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
            $response = GAK_ERROR::handle($exception, 'customers');

            return $this->responseUpdateFail($response['message']);
        }

        return $this->responseUpdateSuccess('', ['item' => new ProductResource($data)]);
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

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest $request
     * @param $id
     * @return JsonResponse
     * @throws Throwable
     */
    public function syncAttribute(SyncAttributeRequest $request, $id): JsonResponse
    {
        try {
            DB::beginTransaction();
            $input = $request->validated();
            $this->model->syncAttribute($id, $input);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            $response = GAK_ERROR::handle($exception, 'product_variants');

            return $this->responseUpdateFail($response['message']);
        }

        return $this->response(200, 'Cập nhật thông tin thành công');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param $id
     * @return JsonResponse
     * @throws Exception
     */
    public function getAttribute($id): JsonResponse
    {
        try {
            $data = $this->model->getAttribute($id);
        } catch (\Exception $exception) {
            $response = GAK_ERROR::handle($exception, 'product_variants');

            return $this->responseUpdateFail($response['message']);
        }

        return $this->response(200, '', ['data' => AttributeGroupShortResource::collection($data)]);
    }


    public function syncWarehouse(SyncRequest $request, $id): JsonResponse
    {
        try {
            DB::beginTransaction();
            $input = $request->validated();
            $input['id'] = $id;
            $this->model->syncWarehouse($id, $input);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            $response = GAK_ERROR::handle($exception, 'products');

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
            $response = GAK_ERROR::handle($exception, 'products');

            return $this->responseUpdateFail($response['message']);
        }

        return $this->response(200, '', ['data' => ProductWarehouseResource::collection($data)]);
    }
}
