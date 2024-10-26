<?php

namespace App\V1\CMS\Models;

use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductWarehouse;
use App\Models\Variant;
use App\Models\VariantDetail;
use App\Supports\Support;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class VariantModel extends AbstractModel
{
    public function __construct()
    {
        $model = new Variant();
        parent::__construct($model);
    }

    public function store(array $data)
    {
        $items = $data['items'];
        $itemCount = count($items);
        $productId = $data['product_id'];

        // Truy vấn để đếm số lượng attribute_group_id khớp với product_id = 1
        $matchedCount = ProductAttribute::query()
            ->where('product_id', $productId)
            ->count();

        // Kiểm tra nếu số lượng khớp không bằng số lượng điều kiện
        if ($matchedCount != $itemCount) {
            throw new Exception("Một số nhóm thuộc tính không khớp hoặc bị thiếu.");
        }

        // Lấy danh sách các variant có chi tiết thỏa mãn
        $variantIds = VariantDetail::query()
            ->select('variant_id')
            ->where(function ($query) use ($items) {
                foreach ($items as $item) {
                    $query->orWhere(function ($q) use ($item) {
                        $q->where('attribute_id', $item['attribute_id'])
                            ->where('attribute_group_id', $item['attribute_group_id']);
                    });
                }
            })
            ->whereHas('variant')
            ->where('product_id', $productId)
            ->groupBy('variant_id')
            ->havingRaw("COUNT(DISTINCT id) = ?", [$itemCount]) // Kiểm tra đủ số lượng cặp điều kiện
            ->pluck('variant_id');

        $exists = $variantIds->isNotEmpty();

        if ($exists) {
            throw new Exception('Dữ liệu đã tồn tại');
        }

        $model = $this->create($data);

        if (empty($model)) {
            throw new Exception('Thêm dữ liệu thất bại');
        }

        $items = array_map(function ($item) use ($productId, $model) {
            $item['product_id'] = $productId;
            $item['variant_id'] = $model->id;
            $item['created_at'] = Support::now();
            $item['updated_at'] = Support::now();
            $item['created_by'] = Auth::id();
            $item['updated_by'] = Auth::id();
            return $item;
        }, $items);

        VariantDetail::query()
            ->insert($items);

        if (!empty($data['image'])) {
            $model->addMedia($data['image'])
                ->usingName($model->sku)
                ->usingFileName($model->sku . '-' . time() . '.' . $data['image']->getClientOriginalExtension())
                ->toMediaCollection();
        }
        $model->refresh();

        return $model;
    }

    public function update(array $data, array $with = []): mixed
    {
        $currentVariantId = $data[$this->model->getKeyName()];

        $model = $this->model
            ->with($with)
            ->find($currentVariantId);

        if (empty($model)) {
            throw new Exception('Dữ liệu không tồn tại', 404);
        }

        $items = $data['items'];
        $itemCount = count($items);
        $productId = $data['product_id'];

        // Truy vấn để đếm số lượng attribute_group_id khớp với product_id = 1
        $matchedCount = ProductAttribute::query()
            ->where('product_id', $productId)
            ->count();

        // Kiểm tra nếu số lượng khớp không bằng số lượng điều kiện
        if ($matchedCount != $itemCount) {
            throw new Exception("Một số nhóm thuộc tính không khớp hoặc bị thiếu.");
        }
        // Lấy danh sách các variant có chi tiết thỏa mãn
        $variantIds = VariantDetail::query()
            ->select('variant_id')
            ->where(function ($query) use ($items) {
                foreach ($items as $item) {
                    $query->orWhere(function ($q) use ($item) {
                        $q->where('attribute_id', $item['attribute_id'])
                            ->where('attribute_group_id', $item['attribute_group_id']);
                    });
                }
            })
            ->whereHas('variant')
            ->where('variant_id', '!=', $currentVariantId)
            ->where('product_id', $productId)
            ->groupBy('variant_id')
            ->havingRaw("COUNT(DISTINCT id) = ?", [$itemCount]) // Kiểm tra đủ số lượng cặp điều kiện
            ->pluck('variant_id');

        $exists = $variantIds->isNotEmpty();

        if ($exists) {
            throw new Exception('Dữ liệu đã tồn tại');
        }

        $model->fill($data);

        if ($model->save()) {
            // Lấy tất cả các chi tiết hiện có của variant hiện tại
            $existingDetails = VariantDetail::query()
                ->where('variant_id', $model->id)
                ->get(['id', 'attribute_id', 'attribute_group_id']);

// Chuyển các chi tiết hiện có thành mảng để so sánh nhanh chóng
            $existingDetailsMap = $existingDetails->map(function ($detail) {
                return $detail->attribute_id . '-' . $detail->attribute_group_id;
            })->toArray();

            $existingIds = $existingDetails->pluck('id')->toArray(); // Lưu các ID hiện có

            // Tạo mảng mới và tách biệt các mục cần giữ lại
            $newItems = [];
            $keepIds = [];

            $items = array_map(function ($item) use ($productId, $model) {
                return [
                    'product_id'         => $productId,
                    'variant_id'         => $model->id,
                    'attribute_id'       => $item['attribute_id'],
                    'attribute_group_id' => $item['attribute_group_id'],
                    'created_at'         => Support::now(),
                    'updated_at'         => Support::now(),
                    'created_by'         => Auth::id(),
                    'updated_by'         => Auth::id(),
                ];
            }, $items);

            foreach ($items as $item) {
                $itemKey = $item['attribute_id'] . '-' . $item['attribute_group_id'];

                if (in_array($itemKey, $existingDetailsMap)) {
                    // Nếu đã tồn tại, tìm ID của nó và lưu lại
                    $existing = $existingDetails->first(function ($detail) use ($item) {
                        return $detail->attribute_id == $item['attribute_id'] &&
                            $detail->attribute_group_id == $item['attribute_group_id'];
                    });
                    $keepIds[] = $existing->id;
                } else {
                    // Nếu chưa tồn tại, thêm vào danh sách để thêm mới
                    $newItems[] = $item;
                }
            }

            // Bước 2: Xóa các chi tiết không có trong danh sách cần giữ lại
            $idsToDelete = array_diff($existingIds, $keepIds);
            if (!empty($idsToDelete)) {
                VariantDetail::query()
                    ->whereIn('id', $idsToDelete) // Xóa các chi tiết thừa
                    ->delete();
            }

            // Bước 3: Thêm các chi tiết mới chưa tồn tại
            if (!empty($newItems)) {
                VariantDetail::query()->insert($newItems);
            }

            if (!empty($data['image'])) {
                $model->addMedia($data['image'])
                    ->usingName($model->sku)
                    ->usingFileName($model->sku . '-' . time() . '.' . $data['image']->getClientOriginalExtension())
                    ->toMediaCollection();
            }
            $model->refresh();
            return $model;
        }

        return false;
    }

    public function syncWarehouse($productId, $id, array $input = []): void
    {
        $model = $this->model
            ->where('product_id', $productId)
            ->where('id', $id)
            ->first();

        if (empty($model)) {
            throw new Exception('Dữ liệu không tồn tại', 404);
        }

        $items = Arr::get($input, 'items', []);

        $items = array_map(function ($item) use ($productId, $id) {
            $item['product_id'] = $productId;
            $item['variant_id'] = $id;
            $item['created_at'] = Support::now();
            $item['updated_at'] = Support::now();
            $item['created_by'] = Auth::id();
            $item['updated_by'] = Auth::id();
            return $item;
        }, $items);

        $warehouseIds = array_column($items, 'warehouse_id');

        ProductWarehouse::query()
            ->where('product_id', $productId)
            ->whereNull('variant_id')
            ->delete();

        ProductWarehouse::query()
            ->where('product_id', $productId)
            ->where('variant_id', $id)
            ->whereNotIn('warehouse_id', $warehouseIds)
            ->delete();

        ProductWarehouse::query()
            ->upsert($items, ['variant_id', 'warehouse_id', 'product_id'], ['qty']);
    }

    /**
     * @throws Exception
     */
    public function getWarehouse($productId, $id): Collection|array
    {
        $model = $this->model
            ->where('product_id', $productId)
            ->where('id', $id)
            ->first();

        if (empty($model)) {
            throw new Exception('Dữ liệu không tồn tại', 404);
        }

        return ProductWarehouse::query()
            ->with(['warehouse'])
            ->where('product_id', $productId)
            ->where('variant_id', $id)
            ->get();
    }


    /**
     * @param $productId
     * @param $input
     * @return void
     */
    public function syncVariant($productId, $input = [])
    {
        $product = Product::query()->find($productId);
        $items = $input['items'];
        // Sắp xếp lại các phần tử
        $items = array_map(function ($item) {
            return [
                "attribute_id"       => $item["attribute_id"],
                "attribute_group_id" => $item["attribute_group_id"],
            ];
        }, $items);
        // Gộp thuộc tính theo nhóm và tính số biến thể
        $variantNew = $this->showVariants($items);

        $variantCurrents = VariantDetail::query()
            ->where('product_id', $productId)
            ->whereHas('variant') // Đảm bảo variant tồn tại
            ->where(function ($query) use ($items) {
                foreach ($items as $item) {
                    $query->orWhere(function ($q) use ($item) {
                        $q->where('attribute_id', $item['attribute_id'])
                            ->where('attribute_group_id', $item['attribute_group_id']);
                    });
                }
            })
            ->selectRaw('attribute_id, attribute_group_id')
            ->get()->toArray();

        $variantCurrents = $this->showVariants($variantCurrents);

        $this->normalizeAndSortArray($variantCurrents);
        $this->normalizeAndSortArray($variantNew);

        $result = $this->excludeDuplicateItems($variantNew, $variantCurrents);


        if (count($result) > 0) {
            $variantsToCreate = [];
            $variantCodes = [];

            foreach ($result as $item) {
                $code = Support::genCode('variants', 'sku');
                $variantCodes[] = $code;
                $variantsToCreate[] = [
                    'product_id' => $productId,
                    'price'      => $product->price,
                    'price_sale' => $product->price_sale,
                    'sku'        => $code,
                    'created_at' => Support::now(),
                    'updated_at' => Support::now(),
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ];
            }

            // Sử dụng bulk insert để tiết kiệm thời gian và giảm số lượng truy vấn
            Variant::query()->insert($variantsToCreate);

            $variants = Variant::query()
                ->whereIn('sku', $variantCodes)
                ->get();

            $newVariantDetails = [];
            foreach ($variants as $index => $variant) {
                foreach ($result[$index] as $variantParam) {

                    $newVariantDetails[] = [
                        'product_id'         => $productId,
                        'variant_id'         => $variant->id,
                        'attribute_id'       => $variantParam['attribute_id'],
                        'attribute_group_id' => $variantParam['attribute_group_id'],
                        'created_at'         => Support::now(),
                        'updated_at'         => Support::now(),
                        'created_by'         => Auth::id(),
                        'updated_by'         => Auth::id(),
                    ];
                }
            }

            VariantDetail::query()->insert($newVariantDetails);
        }
    }

    public function showVariants(array $data = []): array
    {
        // Gộp thuộc tính theo nhóm
        $groupedAttributes = [];
        foreach ($data as $attribute) {
            $groupedAttributes[$attribute['attribute_group_id']][] = $attribute;
        }

        // Tạo biến thể từ tất cả các nhóm
        return $this->combineAttributes($groupedAttributes);
    }

    // Hàm để kết hợp các thuộc tính
    private function combineAttributes($groupedAttributes): array
    {
        $variants = [[]]; // Bắt đầu với một mảng rỗng

        foreach ($groupedAttributes as $group) {
            $tempVariants = [];
            foreach ($variants as $variant) {
                foreach ($group as $attribute) {
                    // Kết hợp thuộc tính vào từng biến thể hiện tại
                    $tempVariants[] = array_merge($variant, [$attribute]);
                }
            }
            $variants = $tempVariants; // Cập nhật biến thể với các thuộc tính đã kết hợp
        }

        return $variants;
    }

    function excludeDuplicateItems($array1, $array2)
    {


// Hàm để kiểm tra sự tồn tại của một phần tử trong array2
        function isArrayInArray($element, $array)
        {
            foreach ($array as $subArray) {
                if ($element === $subArray) {
                    return true;
                }
            }
            return false;
        }

// Tìm các chỉ số của array1 không có trong array2
        $resultIndexes = [];
        foreach ($array1 as $index => $subArray) {
            if (!isArrayInArray($subArray, $array2)) {
                $resultIndexes[] = $index;
            }
        }

        $resultItems = [];
        foreach ($resultIndexes as $index) {
            $resultItems[] = $array1[$index];
        }

        return $resultItems;
    }

    // Hàm chuẩn hóa và sắp xếp mảng
    function normalizeAndSortArray(&$array): void
    {
        foreach ($array as &$subArray) {
            // Chuẩn hóa dữ liệu và sắp xếp các phần tử bên trong mỗi mảng con
            array_walk($subArray, function (&$item) {
                $item['attribute_id'] = (int)$item['attribute_id'];
                $item['attribute_group_id'] = (int)$item['attribute_group_id'];
            });

            usort($subArray, function ($a, $b) {
                if ($a['attribute_id'] === $b['attribute_id']) {
                    return $a['attribute_group_id'] <=> $b['attribute_group_id'];
                }
                return $a['attribute_id'] <=> $b['attribute_id'];
            });
        }

        // Sắp xếp các mảng con
        usort($array, function ($a, $b) {
            foreach ($a as $index => $item) {
                if ($item['attribute_id'] === $b[$index]['attribute_id']) {
                    return $item['attribute_group_id'] <=> $b[$index]['attribute_group_id'];
                }
                return $item['attribute_id'] <=> $b[$index]['attribute_id'];
            }
            return 0;
        });
    }
}
