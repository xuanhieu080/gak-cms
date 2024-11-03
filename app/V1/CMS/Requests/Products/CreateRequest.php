<?php

namespace App\V1\CMS\Requests\Products;

use App\V1\CMS\Requests\ValidatorBase;

class CreateRequest extends ValidatorBase
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'        => 'required|unique:products,name|string|max:255',
            'image'       => 'nullable|image|max:3145728|mimes:jpg,jpeg,png,bmp,gif,svg,webp,mp4,ogx,oga,ogv,ogg,webm',
            'price'       => 'required|numeric|between:0,99999999999',
            'price_sale'  => 'nullable|numeric|between:0,99999999999|lte:price',
            'sku'         => 'nullable|string|unique:products,sku',
            'unit_id'     => 'required|exists:units,id',
            'description' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'is_active'   => 'nullable|in:1,0,true,false',

            'items'                                   => 'nullable|array',
            'items.*'                                 => 'nullable|array',
            'items.*.image'                           => 'nullable|image|max:3145728|mimes:jpg,jpeg,png,bmp,gif,svg,webp,mp4,ogx,oga,ogv,ogg,webm',
            'items.*.price'                           => 'nullable|numeric|between:0,99999999999',
            'items.*.price_sale'                      => 'nullable|numeric|between:0,99999999999|lte:price',
            'items.*.sku'                             => 'nullable|string|unique:variants,sku',
            'items.*.attributes.*'                    => 'nullable|array',
            'items.*.attributes.*.attribute_group_id' => 'required|exists:attribute_groups,id',
            'items.*.attributes.*.attribute_name'     => 'required|string|max:255',

            'attributes'                      => 'nullable|array',
            'attributes.*'                    => 'nullable|array',
            'attributes.*.attribute_group_id' => 'required|exists:attribute_groups,id',
            'attributes.*.attribute_names'     => 'required|array',
            'attributes.*.attribute_names.*'   => 'required|string',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $data = $this->input();

            // Sử dụng collect để lấy danh sách các attribute_group_id và attribute_names hợp lệ
            $validAttributeGroups = collect($data['attributes'] ?? [])
                ->pluck('attribute_group_id')
                ->unique()
                ->all();

            $validAttributeNames = collect($data['attributes'] ?? [])
                ->flatMap(fn($attr) => $attr['attribute_names'] ?? [])
                ->unique()
                ->all();

            // Lặp qua từng item để kiểm tra tính hợp lệ của attribute_group_id và attribute_name
            collect($data['items'] ?? [])->each(function ($item, $itemKey) use ($validator, $validAttributeGroups, $validAttributeNames) {
                collect($item['attributes'] ?? [])->each(function ($attribute, $attrKey) use ($validator, $itemKey, $validAttributeGroups, $validAttributeNames) {
                    if (!in_array($attribute['attribute_group_id'], $validAttributeGroups)) {
                        $validator->errors()->add("items.$itemKey.attributes.$attrKey.attribute_group_id", 'Giá trị này không tồn tại trong attributes.attribute_group_id.');
                    }

                    if (!in_array($attribute['attribute_name'], $validAttributeNames)) {
                        $validator->errors()->add("items.$itemKey.attributes.$attrKey.attribute_name", 'Giá trị này không tồn tại trong attributes.attribute_names.');
                    }
                });
            });
        });
    }
}
