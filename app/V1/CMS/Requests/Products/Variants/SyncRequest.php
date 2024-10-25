<?php

namespace App\V1\CMS\Requests\Products\Variants;

use App\Models\Attribute;
use App\V1\CMS\Requests\ValidatorBase;
use Illuminate\Validation\Rule;

class SyncRequest extends ValidatorBase
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_id'                 => 'required|exists:products,id',
            'items'                      => 'required|array',
            'items.*'                    => 'required|array',
            'items.*.attribute_group_id' => [
                'required',
                'exists:attribute_groups,id',
                Rule::exists('product_attributes', 'attribute_group_id')->where('product_id', $this->input('product_id')),
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
