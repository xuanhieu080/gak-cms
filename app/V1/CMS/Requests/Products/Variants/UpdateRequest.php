<?php

namespace App\V1\CMS\Requests\Products\Variants;

use App\Models\Attribute;
use App\Models\ProductAttribute;
use App\V1\CMS\Requests\ValidatorBase;
use Illuminate\Validation\Rule;

class UpdateRequest extends ValidatorBase
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'image'                      => 'nullable|image|max:3145728|mimes:jpg,jpeg,png,bmp,gif,svg,webp,mp4,ogx,oga,ogv,ogg,webm',
            'price'                      => 'required|numeric|between:0,99999999999',
            'price_sale'                 => 'nullable|numeric|between:0,99999999999|lt:price',
            'sku'                        => [
                'required',
                'string',
                Rule::unique('variants', 'sku')->ignore($this->route('id'))
            ],
            'id'                         => 'required|string|unique:products,sku',
            'items'                      => 'required|array',
            'items.*'                    => 'required|array',
            'items.*.attribute_group_id' => [
                'required',
                'exists:attribute_groups,id',
                function ($attribute, $value, $fail) {
                    $item = ProductAttribute::query()
                        ->where('attribute_group_id', $value)
                        ->first();

                    if (empty($item)) {
                        return $fail('Nhóm thuộc tính không đúng');
                    }
                }
            ],
            'items.*.attribute_id'       => [
                'required',
                'exists:attributes,id',
                function ($attribute, $value, $fail) {
                    $data = request()->all();
                    // Lấy index của item hiện tại từ tên thuộc tính
                    $index = explode('.', $attribute)[1];

                    // Lấy attribute_group_id tương ứng với index hiện tại
                    $attributeGroupId = data_get($data, "items.$index.attribute_group_id");

                    // Kiểm tra attribute_id với điều kiện group_id tương ứng
                    $exists = Attribute::query()
                        ->where('id', $value)
                        ->where('group_id', $attributeGroupId)
                        ->exists();

                    if (!$exists) {
                        $fail("Thuộc tính không hợp lệ cho nhóm thuộc tính");
                    }
                }
            ],
        ];
    }
}
