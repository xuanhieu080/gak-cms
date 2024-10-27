<template>
    <Page>
        <div class="main-pyc">
            <a-breadcrumb :routes="routes" class="bg-gray-100 p-2">
                <template #itemRender="{ route, params, routes, paths }">
                    <span
                        v-if="routes.indexOf(route) === routes.length - 1"
                        class="text-blue-500"
                        >{{ route.breadcrumbName }}</span
                    >
                    <router-link
                        v-else
                        :to="{ name: route.name }"
                        class="!text-black"
                        >{{ route.breadcrumbName }}</router-link
                    >
                </template>
            </a-breadcrumb>
            <a-card :loading="loadingProduct">
                <a-tabs default-active-key="1">
                    <a-tab-pane key="1" tab="Thông tin chung">
                        <a-form
                            :form="form"
                            layout="vertical"
                            @submit.prevent="handleSubmit"
                            class="product-form"
                        >
                            <a-form-item
                                label="Tên sản phẩm"
                                name="name"
                                :rules="[
                                    {
                                        required: true,
                                        message: 'Vui lòng nhập tên sản phẩm!',
                                    },
                                ]"
                            >
                                <a-input
                                    v-model:value="form.name"
                                    placeholder="Nhập tên sản phẩm"
                                />
                            </a-form-item>
                            <a-form-item
                                label="Sku"
                                name="sku"
                                :autoLink="false"
                                :rules="[
                                    {
                                        required: true,
                                        message: 'Vui lòng nhập sku sản phẩm!',
                                    },
                                ]"
                            >
                                <a-input
                                    v-model:value="form.sku"
                                    placeholder="Nhập sku sản phẩm"
                                />
                            </a-form-item>
                            <a-form-item
                                name="category"
                                label="Nhóm sản phẩm"
                                :rules="[
                                    {
                                        required: true,
                                        message: 'Vui lòng chọn nhóm sản phẩm!',
                                    },
                                ]"
                            >
                                <a-select
                                    v-model:value="form.category"
                                    :options="data_manager"
                                    :not-found-content="
                                        category_fetch ? undefinded : null
                                    "
                                    placeholder="Chọn nhóm sản phẩm"
                                    @search="handleSearchCategory"
                                    @change="handleChangeCategory"
                                    @click="handleSearchCategory('')"
                                    :filter-option="false"
                                    show-search
                                >
                                    <template #notFoundContent>
                                        <a-spin
                                            v-if="category_fetch"
                                            size="small"
                                        />
                                        <span
                                            v-if="
                                                data_manager.length == 0 &&
                                                !category_fetch
                                            "
                                            >Không có kết quả nào</span
                                        >
                                    </template>
                                </a-select>
                            </a-form-item>
                            <a-form-item label="Hoạt động" name="active">
                                <a-switch v-model:checked="form.is_active" />
                            </a-form-item>
                            <a-form-item
                                label="Hình ảnh"
                                name="image"
                                :autoLink="false"
                                :rules="[
                                    {
                                        required: true,
                                        message: 'Vui lòng nhập URL hình ảnh!',
                                    },
                                ]"
                            >
                                <a-upload-dragger
                                    :before-upload="beforeUpload"
                                    @preview="handlePreview"
                                    list-type="picture-card"
                                    :max-count="1"
                                    v-model:file-list="form.image"
                                >
                                    <div>
                                        <PlusOutlined />
                                        <div style="margin-top: 8px">
                                            Kéo thả hoặc chọn thêm hình ảnh
                                        </div>
                                    </div>
                                </a-upload-dragger>
                                <a-modal
                                    :open="previewVisible"
                                    :title="previewTitle"
                                    :footer="null"
                                    @cancel="handleCancel"
                                >
                                    <img
                                        alt="example"
                                        style="width: 100%"
                                        :src="previewImage"
                                    />
                                </a-modal>
                            </a-form-item>

                            <a-form-item
                                class="w-full"
                                label="Số lượng tồn kho"
                                name="amount"
                                :autoLink="false"
                                :rules="[
                                    {
                                        required: true,
                                        message:
                                            'Vui lòng nhập số lượng tồn kho!',
                                    },
                                ]"
                            >
                                <a-input-number
                                    v-model:value="form.amount"
                                    placeholder="Nhập số lượng tồn kho"
                                    :min="1"
                                    class="w-full"
                                />
                            </a-form-item>
                            <a-form-item
                                :label="`Đơn vị tính`"
                                name="unit"
                                :autoLink="false"
                                :rules="[
                                    {
                                        required: true,
                                        message: 'Vui lòng chọn đơn vị tính',
                                    },
                                ]"
                            >
                                <a-select
                                    v-model:value="form.unit"
                                    placeholder="Chọn kho"
                                    :loading="loadingUnit"
                                    :not-found-content="
                                        unit_fetching ? undefinded : null
                                    "
                                    :options="unitOptions"
                                    show-search
                                    @search="handleSearchUnit"
                                    @change="handleChangeUnit"
                                    @click="handleSearchUnit('')"
                                >
                                    <template #notFoundContent>
                                        <a-spin
                                            v-if="unit_fetching"
                                            size="small"
                                        />
                                        <span
                                            v-if="
                                                unitOptions.length == 0 &&
                                                !unit_fetching
                                            "
                                            >Không có kết quả nào</span
                                        >
                                    </template>
                                </a-select>
                            </a-form-item>

                            <a-form-item
                                class="w-full"
                                label="Giá tiền gốc"
                                name="price"
                            >
                                <a-input-number
                                    v-model:value="form.price"
                                    :formatter="
                                        (value) =>
                                            `${value}`.replace(
                                                /\B(?=(\d{3})+(?!\d))/g,
                                                ','
                                            )
                                    "
                                    :parser="
                                        (value) =>
                                            value.replace(/\$\s?|(,*)/g, '')
                                    "
                                    class="w-full"
                                    @change="calculateDiscountedPrice"
                                />
                            </a-form-item>
                            <a-form-item
                                label="% giảm giá"
                                name="discount_percent"
                            >
                                <a-input-number
                                    :min="0"
                                    :max="100"
                                    :formatter="(value) => `${value}`"
                                    class="w-full"
                                    v-model:value="form.discount_percent"
                                    @change="calculateDiscountedPrice"
                                />
                            </a-form-item>
                            <a-form-item
                                label="Giá sau giảm"
                                name="discount_price"
                                class="w-full"
                            >
                                <a-input-number
                                    v-model:value="form.discount_price"
                                    :formatter="
                                        (value) =>
                                            `${value}`.replace(
                                                /\B(?=(\d{3})+(?!\d))/g,
                                                ','
                                            )
                                    "
                                    :parser="
                                        (value) =>
                                            value.replace(/\$\s?|(,*)/g, '')
                                    "
                                    class="w-full"
                                    @change="calculateDiscountPercent"
                                />
                            </a-form-item>

                            <a-form-item
                                label="Mô tả sản phẩm"
                                name="product_description"
                                :rules="[
                                    {
                                        required: true,
                                        message: 'Vui lòng nhập mô tả',
                                    },
                                ]"
                            >
                                <CkEditorCustom
                                    :key="'description-1'"
                                    :content="form.product_description"
                                    @updateData="handleUpdateDescription"
                                />
                            </a-form-item>

                            <hr />
                            <a-form-item class="my-4">
                                <a-button
                                    type="dashed"
                                    size="lg"
                                    @click="addWarehouse"
                                    class="flex items-center gap-1 justify-center"
                                >
                                    <PlusOutlined /> Thêm kho
                                </a-button>
                            </a-form-item>

                            <div
                                v-for="(warehouse, index) in form.warehouses"
                                :key="index"
                            >
                                <div class="grid grid-cols-4 gap-4">
                                    <a-form-item
                                        :label="`Kho ${index + 1}`"
                                        :name="[
                                            'warehouses',
                                            index,
                                            'warehouse_id',
                                        ]"
                                        :autoLink="false"
                                        :rules="[
                                            {
                                                required: true,
                                                message: 'Vui lòng chọn kho',
                                            },
                                        ]"
                                    >
                                        <a-select
                                            v-model:value="
                                                warehouse.warehouse_id
                                            "
                                            placeholder="Chọn kho"
                                            :loading="warehouse.loading"
                                            :options="warehouse.options"
                                            :not-found-content="
                                                warehouse.loading
                                                    ? undefinded
                                                    : null
                                            "
                                            show-search
                                            @search="
                                                (val) =>
                                                    handleSearchStorage(
                                                        val,
                                                        warehouse
                                                    )
                                            "
                                            @change="
                                                (val) =>
                                                    handleChangeStorage(
                                                        val,
                                                        warehouse
                                                    )
                                            "
                                            @click="
                                                handleSearchStorage(
                                                    '',
                                                    warehouse
                                                )
                                            "
                                        >
                                            <template #notFoundContent>
                                                <a-spin
                                                    v-if="warehouse.loading"
                                                    size="small"
                                                />
                                                <span
                                                    v-if="
                                                        warehouse.options
                                                            .length == 0 &&
                                                        !warehouse.loading
                                                    "
                                                    >Không có kết quả nào</span
                                                >
                                            </template>
                                        </a-select>
                                    </a-form-item>
                                    <a-form-item
                                        :label="`Số lượng`"
                                        :name="[
                                            'warehouses',
                                            index,
                                            'quantity',
                                        ]"
                                        :autoLink="false"
                                        :rules="[
                                            {
                                                required: true,
                                                message:
                                                    'Vui lòng nhập số lượng',
                                            },
                                        ]"
                                    >
                                        <a-input-number
                                            v-model:value="warehouse.quantity"
                                            class="w-full"
                                            placeholder="Số lượng"
                                            :min="1"
                                        />
                                    </a-form-item>

                                    <div class="w-fit flex items-center">
                                        <a-button
                                            type="primary"
                                            danger
                                            @click="removeWarehouse(index)"
                                            class="flex items-center gap-2"
                                        >
                                            <MinusOutlined /> Xóa
                                        </a-button>
                                    </div>
                                </div>
                            </div>

                            <hr />
                            <a-form-item v-if="errorInfo.length > 0">
                                <ul class="list-disc pl-6">
                                    <li
                                        class="text-red-500 capitalize"
                                        v-for="error in errorInfo"
                                    >
                                        {{ error[0] }}
                                    </li>
                                </ul>
                            </a-form-item>
                            <a-form-item class="mt-4">
                                <div class="flex items-center gap-4">
                                    <a-button type="primary" html-type="submit">
                                        Cập nhật thông tin
                                    </a-button>
                                    <a-button
                                        type="primary"
                                        @click="handleUpdateStorageProduct"
                                    >
                                        Cập nhật kho
                                    </a-button>
                                    <a-button
                                        type="primary"
                                        danger
                                        @click="handleBackProductIndex"
                                        >Hủy bỏ</a-button
                                    >
                                </div>
                            </a-form-item>
                        </a-form>
                    </a-tab-pane>
                    <a-tab-pane key="2" tab="Biến thể mới">
                        <div class="flex items-center gap-4">
                            <a-button @click="handleAutoAddVariant"
                                >Thêm hàng loạt biến thể</a-button
                            >
                            <a-button @click="handleOpenAddVariant"
                                >Thêm biến thể mới</a-button
                            >
                        </div>
                        <a-modal
                            v-model:open="openAutoAddVariant"
                            title="Thêm Biến Thể Hàng Loạt"
                            @ok="autoAddVariantFunc"
                        >
                            <p>Coming Soon...</p>
                        </a-modal>
                        <a-form
                            v-if="openCreateVariantForm && !loadingAttribute"
                            ref="ruleForm"
                            :model="formVariables"
                            layout="vertical"
                            @submit.prevent="handleCreateNewVariant"
                            class="mt-4"
                        >
                            <div class="font-bold text-xl mb-4">
                                Thêm biến thể mới
                            </div>
                            <a-form-item
                                label="Tên biến thể"
                                name="name"
                                :rules="[
                                    {
                                        required: true,
                                        message: 'Vui lòng nhập tên biến thể!',
                                    },
                                ]"
                            >
                                <a-input
                                    v-model:value="formVariables.name"
                                    disabled
                                />
                            </a-form-item>
                            <a-form-item label="SKU biến thể" name="sku">
                                <a-input v-model:value="formVariables.sku" />
                            </a-form-item>
                            <a-form-item
                                label="Hình ảnh"
                                name="image"
                                :autoLink="false"
                                :rules="[
                                    {
                                        required: true,
                                        message: 'Vui lòng nhập URL hình ảnh!',
                                    },
                                ]"
                            >
                                <a-upload-dragger
                                    :before-upload="beforeUploadVariantImg"
                                    @preview="handlePreviewVariantImg"
                                    list-type="picture-card"
                                    :max-count="1"
                                    v-model:file-list="formVariables.image"
                                >
                                    <div>
                                        <PlusOutlined />
                                        <div style="margin-top: 8px">
                                            Kéo thả hoặc chọn thêm hình ảnh
                                        </div>
                                    </div>
                                </a-upload-dragger>
                                <a-modal
                                    :open="previewVariantImg"
                                    :title="previewVariantTitle"
                                    :footer="null"
                                    @cancel="handleCancelVariantImg"
                                >
                                    <img
                                        alt="example"
                                        style="width: 100%"
                                        :src="previewVariant"
                                    />
                                </a-modal>
                            </a-form-item>
                            <div
                                v-for="(
                                    attribute, index
                                ) in formVariables.attributes"
                                :key="index"
                            >
                                <div class="w-full">
                                    <a-form-item
                                        :label="`${attribute.name}`"
                                        :name="['attributes', index, 'id']"
                                        :autoLink="false"
                                        :rules="[
                                            {
                                                required: true,
                                                message: `Vui lòng chọn ${attribute.name}`,
                                            },
                                        ]"
                                    >
                                        <a-select
                                            v-model:value="
                                                attribute.attributes_id
                                            "
                                            :placeholder="
                                                'Chọn ' + attribute.name
                                            "
                                            :loading="attribute.loading"
                                            :options="attribute.options"
                                            :not-found-content="
                                                attribute.loading
                                                    ? undefinded
                                                    : null
                                            "
                                            @change="
                                                (val) =>
                                                    handleSetAttributeVariant(
                                                        val,
                                                        attribute
                                                    )
                                            "
                                            show-search
                                        >
                                            <template #notFoundContent>
                                                <a-spin
                                                    v-if="attribute.loading"
                                                    size="small"
                                                />
                                                <span
                                                    v-if="
                                                        attribute.options
                                                            .length == 0 &&
                                                        !attribute.loading
                                                    "
                                                    >Không có kết quả nào</span
                                                >
                                            </template>
                                        </a-select>
                                    </a-form-item>
                                </div>
                            </div>
                            <a-form-item
                                label="Giá tiền"
                                name="price"
                                class="w-full"
                            >
                                <a-input-number
                                    class="w-full"
                                    :formatter="
                                        (value) =>
                                            `${value}`.replace(
                                                /\B(?=(\d{3})+(?!\d))/g,
                                                ','
                                            )
                                    "
                                    :parser="
                                        (value) =>
                                            value.replace(/\$\s?|(,*)/g, '')
                                    "
                                    v-model:value="formVariables.price"
                                    @change="calculateDiscountedPriceVariable"
                                />
                            </a-form-item>
                            <a-form-item
                                label="% giảm giá"
                                name="discount_percent"
                            >
                                <a-input-number
                                    class="w-full"
                                    :min="0"
                                    :max="100"
                                    :formatter="(value) => `${value}`"
                                    v-model:value="
                                        formVariables.discount_percent
                                    "
                                    @change="calculateDiscountedPriceVariable"
                                />
                            </a-form-item>
                            <a-form-item
                                label="Giá sau giảm"
                                name="discount_price"
                            >
                                <a-input-number
                                    class="w-full"
                                    :formatter="
                                        (value) =>
                                            `${value}`.replace(
                                                /\B(?=(\d{3})+(?!\d))/g,
                                                ','
                                            )
                                    "
                                    :parser="
                                        (value) =>
                                            value.replace(/\$\s?|(,*)/g, '')
                                    "
                                    v-model:value="formVariables.discount_price"
                                    @change="calculateDiscountPercentVariable"
                                />
                            </a-form-item>
                            <a-form-item
                                label="Mô tả sản phẩm"
                                name="product_description"
                                :rules="[
                                    {
                                        required: true,
                                        message: 'Vui lòng nhập mô tả',
                                    },
                                ]"
                            >
                                <CkEditorCustom
                                    :key="'description-variant'"
                                    :content="formVariables.product_description"
                                    @updateData="
                                        (value) =>
                                            (formVariables.product_description =
                                                value)
                                    "
                                />
                            </a-form-item>
                            <a-form-item v-if="errorInfo.length > 0">
                                <ul class="list-disc pl-6">
                                    <li
                                        class="text-red-500 capitalize"
                                        v-for="error in errorInfo"
                                    >
                                        {{ error[0] }}
                                    </li>
                                </ul>
                            </a-form-item>
                            <a-form-item>
                                <a-button type="primary" html-type="submit">
                                    Tạo biến thể
                                </a-button>
                            </a-form-item>
                        </a-form>
                    </a-tab-pane>
                    <a-tab-pane key="3" tab="Thuộc tính">
                        <a-form
                            ref="ruleForm"
                            :model="formAttributes"
                            :rules="rules"
                            :label-col="labelCol"
                            :wrapper-col="wrapperCol"
                        >
                            <div>
                                <div class="flex items-center gap-4 mb-4">
                                    <h3>Thêm thuộc tính</h3>
                                    <a-button
                                        type="primary"
                                        @click="addAttribute"
                                        >Thêm chi tiết thuộc tính</a-button
                                    >
                                </div>
                                <div
                                    v-for="(
                                        attribute, index
                                    ) in formAttributes.attributes"
                                    :key="index"
                                >
                                    <a-row :gutter="16">
                                        <a-col :span="7">
                                            <a-form-item
                                                :ref="`attributeGroup-${index}`"
                                                :label="`Nhóm thuộc tính`"
                                                :name="`attributes.${index}.attributeGroup`"
                                            >
                                                <a-select
                                                    v-model:value="attribute.id"
                                                    placeholder="Chọn nhóm thuộc tính"
                                                    :loading="attribute.loading"
                                                    :options="attribute.options"
                                                    :not-found-content="
                                                        attribute.loading
                                                            ? undefinded
                                                            : null
                                                    "
                                                    show-search
                                                    @search="
                                                        (val) =>
                                                            handleSearchAttributeGroup(
                                                                val,
                                                                attribute
                                                            )
                                                    "
                                                    @change="
                                                        (val) =>
                                                            handleChangeAttributeGroup(
                                                                val,
                                                                attribute
                                                            )
                                                    "
                                                    @click="
                                                        handleSearchAttributeGroup(
                                                            '',
                                                            attribute
                                                        )
                                                    "
                                                >
                                                    <template #notFoundContent>
                                                        <a-spin
                                                            v-if="
                                                                attribute.loading
                                                            "
                                                            size="small"
                                                        />
                                                        <span
                                                            v-if="
                                                                attribute
                                                                    .options
                                                                    .length ==
                                                                    0 &&
                                                                !attribute.loading
                                                            "
                                                            >Không có kết quả
                                                            nào</span
                                                        >
                                                    </template>
                                                </a-select>
                                            </a-form-item>
                                        </a-col>

                                        <a-col :span="2">
                                            <div
                                                class="flex items-center gap-1 ml-4 mt-2.5"
                                            >
                                                <MinusCircleOutlined
                                                    :style="{ color: 'red' }"
                                                    @click="
                                                        removeAttribute(index)
                                                    "
                                                />
                                            </div>
                                        </a-col>
                                    </a-row>
                                </div>
                            </div>
                            <div class="flex flex-col gap-4 mb-4">
                                <h3>Thuộc tính đã chọn</h3>
                                <div class="flex gap-4 flex-wrap">
                                    <div
                                        v-for="(
                                            selectedAttribute, index
                                        ) in formAttributes.selectedAttributes"
                                        :key="index"
                                    >
                                        <a-row
                                            class="border border-dashed p-4 flex flex-col gap-2"
                                        >
                                            <a-col>
                                                <b>Nhóm thuộc tính:</b>
                                                {{
                                                    selectedAttribute.data.name
                                                }}
                                            </a-col>
                                        </a-row>
                                    </div>
                                </div>
                            </div>
                            <a-form-item v-if="errorInfo.length > 0">
                                <ul class="list-disc pl-6">
                                    <li
                                        class="text-red-500 capitalize"
                                        v-for="error in errorInfo"
                                    >
                                        {{ error[0] }}
                                    </li>
                                </ul>
                            </a-form-item>
                            <a-form-item>
                                <a-button
                                    type="primary"
                                    @click="handleSubmitAttributes"
                                    >Lưu thuộc tính</a-button
                                >
                            </a-form-item>
                        </a-form>
                    </a-tab-pane>
                    <a-tab-pane key="4" tab="Biến thể">
                        <a-form
                            :form="variableForm"
                            @submit="handleSubmitEachVariant"
                            layout="vertical"
                        >
                            <a-form-item label="Chọn biến thể">
                                <a-select
                                    v-model:value="selectedVariant"
                                    placeholder="Chọn biến thể"
                                    @change="handleVariantChange"
                                >
                                    <a-select-option
                                        v-for="variant in variants"
                                        :key="variant.id"
                                        :value="variant.id"
                                    >
                                        {{ variant.name }}
                                    </a-select-option>
                                </a-select>
                            </a-form-item>

                            <template v-if="selectedVariant">
                                <a-form-item
                                    label="Mã sản phẩm"
                                    :rules="[
                                        {
                                            required: true,
                                            message:
                                                'Vui lòng nhập mã sản phẩm!',
                                        },
                                    ]"
                                    name="productCode"
                                    :autoLink="false"
                                >
                                    <a-input
                                        v-model:value="variableForm.productCode"
                                    />
                                </a-form-item>

                                <a-form-item
                                    label="Tên biến thể"
                                    :rules="[
                                        {
                                            required: true,
                                            message:
                                                'Vui lòng nhập tên biến thể!',
                                        },
                                    ]"
                                    :autoLink="false"
                                    name="variantName"
                                >
                                    <a-input
                                        v-model:value="variableForm.variantName"
                                    />
                                </a-form-item>

                                <a-form-item
                                    label="Nội dung biến thể"
                                    name="variantContent"
                                >
                                    <a-input
                                        v-model:value="
                                            variableForm.variantContent
                                        "
                                        :disabled="true"
                                    />
                                </a-form-item>

                                <a-form-item
                                    label="Param biến thể"
                                    name="variantParam"
                                >
                                    <a-input
                                        v-model:value="
                                            variableForm.variantParam
                                        "
                                    />
                                </a-form-item>

                                <a-form-item label="Giá tiền" name="price">
                                    <a-input-number
                                        v-model:value="variableForm.price"
                                        class="w-full"
                                        :formatter="
                                            (value) =>
                                                `${value}`.replace(
                                                    /\B(?=(\d{3})+(?!\d))/g,
                                                    ','
                                                )
                                        "
                                        :parser="
                                            (value) =>
                                                value.replace(/\$\s?|(,*)/g, '')
                                        "
                                        @change="
                                            calculateDiscountedVariablePrice
                                        "
                                    />
                                </a-form-item>

                                <a-form-item
                                    label="% giảm giá"
                                    name="discountPercent"
                                >
                                    <a-input-number
                                        v-model:value="
                                            variableForm.discount_percent
                                        "
                                        :min="0"
                                        :max="100"
                                        :formatter="(value) => `${value}`"
                                        class="w-full"
                                        @change="
                                            calculateDiscountedVariablePrice
                                        "
                                    />
                                </a-form-item>

                                <a-form-item
                                    label="Tiền sau giảm"
                                    name="discountedPrice"
                                >
                                    <a-input-number
                                        v-model:value="
                                            variableForm.discount_price
                                        "
                                        class="w-full"
                                        :formatter="
                                            (value) =>
                                                `${value}`.replace(
                                                    /\B(?=(\d{3})+(?!\d))/g,
                                                    ','
                                                )
                                        "
                                        :parser="
                                            (value) =>
                                                value.replace(/\$\s?|(,*)/g, '')
                                        "
                                        @change="
                                            calculateVariableDiscountPercent
                                        "
                                    />
                                </a-form-item>

                                <a-form-item label="Số lượng" name="quantity">
                                    <a-input-number
                                        class="w-full"
                                        :formatter="
                                            (value) =>
                                                `${value}`.replace(
                                                    /\B(?=(\d{3})+(?!\d))/g,
                                                    ','
                                                )
                                        "
                                        v-model:value="variableForm.quantity"
                                        :min="0"
                                    />
                                </a-form-item>

                                <a-form-item>
                                    <a-button type="primary" html-type="submit">
                                        Lưu
                                    </a-button>
                                </a-form-item>
                            </template>
                        </a-form>
                    </a-tab-pane>
                </a-tabs>
            </a-card>
        </div>
    </Page>
</template>

<script setup>
import {
    MinusOutlined,
    PlusOutlined,
    DeleteOutlined,
    EditOutlined,
    SyncOutlined,
    ReloadOutlined,
    UserOutlined,
    PhoneTwoTone,
    MinusCircleOutlined,
} from "@ant-design/icons-vue";
import { ref, watch, reactive } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";
import { usePagination } from "vue-request";
import { message } from "ant-design-vue";
import Page from "@/views/layouts/Page";
import CkEditorCustom from "@/views/components/CkEditorCustom.vue";
import dayjs from "dayjs";
const form = ref({
    name: "",
    sku: "",
    category: null,
    is_active: false,
    image: [],
    amount: 0,
    unit: null,
    price: 0,
    discount_percent: 0,
    discount_price: 0,
    product_description: "",
    warehouses: [
        {
            warehouse_id: null,
            quantity: null,
            loading: false,
            options: [],
        },
    ],
});
const formVariables = ref({
    name: null,
    sku: null,
    attributes: [],
    image: [],
    price: 0,
    discount_percent: 0,
    discount_price: 0,
    product_description: "",
});
const openCreateVariantForm = ref(false);
const openAutoAddVariant = ref(false);
const openEditSelectedAttribute = ref(false);
const formAttributes = ref({
    attributes: [
        {
            id: null,
            options: [],
            loading: false,
        },
    ],
    selectedAttributes: [],
});

const errorInfo = ref([]);
const visible = ref(false);
const visibleEditReview = ref(false);
const formReview = ref({
    author: "",
    variable: "",
    rating: 0,
    content: "",
    reply: "",
    date: null,
    reviews: [
        {
            author: "Alex Phạm",
            variable:
                "Màu sắc: Cam, Lưới: X5, Phản quang: Phản quang nhựa chuối caro",
            rating: 5,
            content: "Chưa bao giờ mà mua được sản phẩm xịn như vậy",
            reply: "Chân thành cảm ơn anh đã ủng hộ",
            date: dayjs(),
        },
    ],
});
const formEditReview = ref({
    author: "",
    variable: "",
    rating: 0,
    content: "",
    reply: "",
    date: null,
});

// Handle Change, edit một variable

const selectedVariant = ref(null);
const variants = ref([
    { id: 1, name: "Biến thể 1", content: "Nội dung biến thể 1" },
    { id: 2, name: "Biến thể 2", content: "Nội dung biến thể 2" },
    // Thêm các biến thể khác vào đây
]);
const variableForm = ref({
    productCode: "",
    variantName: "",
    variantContent: "",
    variantParam: "",
    price: 0,
    discount_percent: 0,
    discount_price: 0,
    quantity: 0,
});
const routes = ref([
    {
        name: "home",
        breadcrumbName: "Trang chủ",
    },
    {
        name: "product-index",
        breadcrumbName: "Quản lý Sản phẩm",
    },
    {
        name: "product-edit",
        breadcrumbName: "Chỉnh sửa sản phẩm",
    },
]);

const router = useRouter();

const handleBackProductIndex = () => {
    router.push({ name: "product-index" });
};

//Price handle

function calculateDiscountedPrice() {
    // form.value.discount_price = Math.round(
    //     form.value.price * (1 - form.value.discount_percent / 100)
    // );
    form.value.discount_price = customRound(
        form.value.price * (1 - form.value.discount_percent / 100)
    );
}
function calculateDiscountPercent() {
    if (
        form.value.price !== 0 &&
        form.value.discount_price <= form.value.price
    ) {
        let newDiscountPercent =
            ((form.value.price - form.value.discount_price) /
                form.value.price) *
            100;
        if (Math.abs(newDiscountPercent - form.value.discount_percent) >= 1) {
            form.value.discount_percent = Math.round(newDiscountPercent);
        }
    }
    if (form.value.discount_price > form.value.price) {
        form.value.discount_price = 0;
        form.value.discount_percent = 100;
        message.error("Giá sau giảm không được lớn hơn giá gốc");
    }
}

// Price Variable
function calculateDiscountedPriceVariable() {
    // formVariables.value.discount_price = Math.round(
    //     formVariables.value.price * (1 - formVariables.value.discount_percent / 100)
    // );
    formVariables.value.discount_price = customRound(
        formVariables.value.price *
            (1 - formVariables.value.discount_percent / 100)
    );
}
function calculateDiscountPercentVariable() {
    if (
        formVariables.value.price !== 0 &&
        formVariables.value.discount_price <= formVariables.value.price
    ) {
        let newDiscountPercent =
            ((formVariables.value.price - formVariables.value.discount_price) /
                formVariables.value.price) *
            100;
        if (
            Math.abs(
                newDiscountPercent - formVariables.value.discount_percent
            ) >= 1
        ) {
            formVariables.value.discount_percent =
                customRound(newDiscountPercent);
        }
    }
    if (formVariables.value.discount_price > formVariables.value.price) {
        formVariables.value.discount_price = 0;
        formVariables.value.discount_percent = 100;
        message.error("Giá sau giảm không được lớn hơn giá gốc");
    }
}

// Price specific variable
//Price handle

function calculateDiscountedVariablePrice() {
    // form.value.discount_price = Math.round(
    //     form.value.price * (1 - form.value.discount_percent / 100)
    // );
    variableForm.value.discount_price = customRound(
        variableForm.value.price *
            (1 - variableForm.value.discount_percent / 100)
    );
}
function calculateVariableDiscountPercent() {
    if (
        variableForm.value.price !== 0 &&
        variableForm.value.discount_price <= variableForm.value.price
    ) {
        let newDiscountPercent =
            ((variableForm.value.price - variableForm.value.discount_price) /
                variableForm.value.price) *
            100;
        if (
            Math.abs(
                newDiscountPercent - variableForm.value.discount_percent
            ) >= 1
        ) {
            variableForm.value.discount_percent =
                Math.round(newDiscountPercent);
        }
    }
    if (variableForm.value.discount_price > variableForm.value.price) {
        variableForm.value.discount_price = 0;
        variableForm.value.discount_percent = 100;
        message.error("Giá sau giảm không được lớn hơn giá gốc");
    }
}

function customRound(value) {
    if (value % 1 >= 0.5) {
        return Math.floor(value);
    } else {
        return Math.round(value);
    }
}

//Handle Description
const handleUpdateDescription = (value) => {
    form.value.product_description = value;
};

//Handle Submit Form Update Product Tab-1

const handleSubmit = async () => {
    try {
        // Perform form submission logic here

        errorInfo.value = [];
        let formData = new FormData();
        formData.append("name", form.value.name);
        formData.append("sku", form.value.sku);
        formData.append("category_id", form.value.category.value);
        formData.append("qty", form.value.amount);
        formData.append("is_active", form.value.is_active);
        if (
            form.value.image &&
            form.value.image.length > 0 &&
            form.value.image[0].originFileObj
        ) {
            formData.append("image", form.value.image[0].originFileObj);
        }
        formData.append("qty", form.value.amount);
        formData.append("unit_id", form.value.unit.value);
        formData.append("price", form.value.price);
        formData.append(
            "price_sale",
            form.value.price - form.value.discount_price
        );
        if (form.value.product_description) {
            formData.append("description", form.value.product_description);
        }
        const response = await axios.post(
            `/api/products/${router.currentRoute.value.params.id}`,
            formData,
            {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            }
        );
        if (response.data.code == 200) {
            message.success(response.data.message);
            router.push({ name: "product-index" });
        }
    } catch (e) {
        if (e.response.status == 422) {
            errorInfo.value = Object.values(e.response.data.errors);
            message.error("Vui lòng kiểm tra lại thông tin");
        } else {
            message.error("Máy chủ bận");
            console.log("errors: ", e);
        }
    }
};

// Xử lý thông tin kho

const handleUpdateStorageProduct = async () => {
    try {
        let items = [];
        if (form.value.warehouses.length > 0) {
            items = form.value.warehouses.map((item) => {
                return {
                    warehouse_id: item.warehouse_id.value,
                    qty: item.quantity,
                };
            });
            const response = await axios.post(
                `/api/products/${router.currentRoute.value.params.id}/warehouses`,
                {
                    items: items,
                }
            );
            if (response.data.code == 200) {
                message.success(response.data.message);
            }
        }
    } catch (e) {
        if (e.response.status == 422) {
            errorInfo.value = Object.values(e.response.data.errors);
            message.error("Vui lòng kiểm tra lại thông tin");
        } else {
            message.error("Máy chủ bận");
            console.log("errors: ", e);
        }
    }
};

// Xử lý hình ảnh
function getBase64(file) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = () => resolve(reader.result);
        reader.onerror = (error) => reject(error);
    });
}

// Load hình ảnh sản phẩm chung
const beforeUpload = (file) => {
    form.value.image = [...(form.value.image || []), file];
    return false;
};
const previewVisible = ref(false);
const previewImage = ref("");
const previewTitle = ref("");
const handlePreview = async (file) => {
    if (!file.url && !file.preview) {
        file.preview = await getBase64(file.originFileObj);
    }
    previewImage.value = file.url || file.preview;
    previewVisible.value = true;
    previewTitle.value =
        file.name || file.url.substring(file.url.lastIndexOf("/") + 1);
};
const handleCancel = () => {
    previewVisible.value = false;
    previewTitle.value = "";
};

// Load Hình ảnh thông tin nổi bật
const beforeUploadVariantImg = (file) => {
    formVariables.value.image = [...(formVariables.value.image || []), file];
    return false;
};
const previewVariantImg = ref(false);
const previewVariant = ref("");
const previewVariantTitle = ref("");
const handlePreviewVariantImg = async (file) => {
    if (!file.url && !file.preview) {
        file.preview = await getBase64(file.originFileObj);
    }
    previewVariant.value = file.url || file.preview;
    previewVariantImg.value = true;
    previewVariantTitle.value =
        file.name || file.url.substring(file.url.lastIndexOf("/") + 1);
};
const handleCancelVariantImg = () => {
    previewVariantImg.value = false;
    previewVariantTitle.value = "";
};
// Load Hình ảnh SEO
const beforeUploadSEOImg = (file) => {
    form.value.seo_image = [...(form.value.seo_image || []), file];
    return false;
};
const previewVisibleSEOImg = ref(false);
const previewSEOImg = ref("");
const previewSEOImgTitle = ref("");
const handlePreviewSEOImg = async (file) => {
    if (!file.url && !file.preview) {
        file.preview = await getBase64(file.originFileObj);
    }
    previewSEOImg.value = file.url || file.preview;
    previewVisibleSEOImg.value = true;
    previewSEOImgTitle.value =
        file.name || file.url.substring(file.url.lastIndexOf("/") + 1);
};
const handleCancelSEOImg = () => {
    previewVisibleSEOImg.value = false;
    previewSEOImgTitle.value = "";
};

//Add Kho
const addWarehouse = () => {
    form.value.warehouses.push({
        warehouse_id: null,
        quantity: null,
        loading: true,
        options: [],
    });
    handleSearchStorage(
        "",
        form.value.warehouses[form.value.warehouses.length - 1],
        (data) =>
            (form.value.warehouses[form.value.warehouses.length - 1].options =
                data)
    );
};

const removeWarehouse = (index) => {
    form.value.warehouses.splice(index, 1);
};

//Load Kho options
let valueStorage = null;

let timeoutStorage = null;
function fetchStorageDropdown(value, item = null, callback) {
    if (timeoutStorage) {
        clearTimeout(timeoutStorage);
        timeoutStorage = null;
    }
    valueStorage = value;
    timeoutStorage = setTimeout(searchStorage(value, item, callback), 300);
}

const handleSearchStorage = async (val, ỉtem = null) => {
    ỉtem.loading = true;
    fetchStorageDropdown(val, ỉtem, (data) => (ỉtem.options = data));
};
const handleChangeStorage = (val, item) => {
    item.loading = false;
    let dataSelected = item.options.find((ele) => ele.value == val);
    if (dataSelected) {
        item.warehouse_id = {
            value: val,
            label: dataSelected.name,
            data: dataSelected,
        };
    }
    fetchStorageDropdown("", (data) => (item.options = data));
};

async function searchStorage(value, item, callback) {
    item.loading = true;
    const params = new URLSearchParams({
        name: value,
    });

    // Lấy dữ liệu kho đã được thêm ở trước
    let excludeStorage = [];
    if (form.value.warehouses.length > 0) {
        form.value.warehouses.forEach((item, index) => {
            if (index < form.value.warehouses.length - 1)
                excludeStorage.push(item.warehouse_id);
        });
    }

    // console.log(excludeStorage);
    // excludeStorage => Loại bỏ những kho đã lựa chọn trước đó
    if (value) {
        await axios.get(`/api/warehouses?${params}`).then((response) => {
            if (valueStorage === value) {
                const result = response.data.data?.map((storage) => ({
                    label: storage.name,
                    value: storage.id,
                    data: storage,
                }));
                item.loading = false;
                callback(result);
            }
        });
    } else {
        await axios.get(`/api/warehouses`).then((response) => {
            if (valueStorage === value) {
                const result = response.data.data?.map((storage) => ({
                    label: storage.name,
                    value: storage.id,
                    data: storage,
                }));
                item.loading = false;
                callback(result);
            }
        });
    }
}

// Handle Add Attributee
function addAttribute() {
    formAttributes.value.attributes.push({
        id: null,
        options: [],
        loading: false,
    });
}
function removeAttribute(index) {
    formAttributes.value.attributes.splice(index, 1);
    formAttributes.value.selectedAttributes.splice(index, 1);
}

//Load Attribute options
let timeout;
let currentValue = "";
function fetchAttributeDropdown(value, item = null, callback) {
    if (timeout) {
        clearTimeout(timeout);
        timeout = null;
    }
    currentValue = value;
    timeout = setTimeout(searchAttributeGroup(value, item, callback), 300);
}

const handleSearchAttributeGroup = async (val, ỉtem = null) => {
    ỉtem.loading = true;
    fetchAttributeDropdown(val, ỉtem, (data) => (ỉtem.options = data));
};
const handleChangeAttributeGroup = (val, item) => {
    item.id = val;
    item.loading = false;
    let dataSelected = item.options.find((ele) => ele.value == val);
    if (dataSelected) {
        if (
            formAttributes.value.attributes.length >
            formAttributes.value.selectedAttributes.length
        ) {
            formAttributes.value.selectedAttributes.push({
                id: dataSelected.value,
                data: dataSelected.data,
            });
        } else {
            formAttributes.value.selectedAttributes[
                formAttributes.value.selectedAttributes.length - 1
            ] = {
                id: dataSelected.value,
                data: dataSelected.data,
            };
        }
    }
    fetchAttributeDropdown("", item, (data) => (item.options = data));
};
const handleChangeAttribute = (val, item) => {
    item.attribute_id = val;
    item.loading = false;
    let dataSelected = item.attribute.find((ele) => ele.value == val);
    if (dataSelected) {
        if (
            formAttributes.value.attributes.length >
            formAttributes.value.selectedAttributes.length
        ) {
            formAttributes.value.selectedAttributes.push({
                id: dataSelected.data.id,
                data: dataSelected.data,
                is_highlight: item.is_highlight,
                is_feature: item.is_feature,
            });
        } else {
            formAttributes.value.selectedAttributes[
                formAttributes.value.selectedAttributes.length - 1
            ] = {
                id: dataSelected.data.id,
                data: dataSelected.data,
                is_highlight: item.is_highlight,
                is_feature: item.is_feature,
            };
        }
    }
};
const handleUpdateToggle = (item, name) => {
    if (name == "highlight") {
        let res = formAttributes.value.selectedAttributes.findIndex(
            (ele) => ele.id == item.attribute_id
        );
        if (res != -1) {
            formAttributes.value.selectedAttributes[res].is_highlight =
                item.is_highlight;
        }
    } else {
        let res = formAttributes.value.selectedAttributes.findIndex(
            (ele) => ele.id == item.attribute_id
        );
        if (res != -1) {
            formAttributes.value.selectedAttributes[res].is_feature =
                item.is_feature;
        }
    }
};

async function searchAttributeGroup(value, item, callback) {
    item.loading = true;
    const params = new URLSearchParams({
        name: value,
    });

    // Lấy dữ liệu kho đã được thêm ở trước
    // let excludeStorage = [];
    // if (formAttributes.value.attribute.length > 0) {
    //     formAttributes.value.attribute.forEach((item, index) => {
    //         if (index < formAttributes.value.attribute.length - 1)
    //             excludeStorage.push(item.warehouse_id);
    //     });
    // }

    // console.log(excludeStorage);
    // excludeStorage => Loại bỏ những kho đã lựa chọn trước đó
    if (value) {
        await axios.get(`/api/attribute-groups?${params}`).then((response) => {
            if (currentValue === value) {
                const result = response.data.data?.map((storage) => ({
                    label: storage.name,
                    value: storage.id,
                    data: storage,
                }));
                item.loading = false;
                callback(result);
            }
        });
    } else {
        await axios.get(`/api/attribute-groups`).then((response) => {
            if (currentValue === value) {
                const result = response.data.data?.map((storage) => ({
                    label: storage.name,
                    value: storage.id,
                    data: storage,
                }));
                item.loading = false;
                callback(result);
            }
        });
    }
}

//Handle Submit Attributes
const handleSubmitAttributes = async () => {
    if (formAttributes.value.selectedAttributes.length > 0) {
        let groupIds = formAttributes.value.selectedAttributes.map(
            (item) => item.id
        );
        await axios
            .post(
                `/api/products/${router.currentRoute.value.params.id}/attributes`,
                {
                    groups: groupIds,
                }
            )
            .then((response) => {
                if (response.data.status) {
                    message.success(response.data.message);
                    // formAttributes.value.selectedAttributes = [];
                    // fetchAttributes();
                } else {
                    message.error(response.data.message);
                }
            })
            .catch((e) => {
                if (e.response.status == 422) {
                    errorInfo.value = Object.values(e.response.data.errors);
                    message.error("Vui lòng kiểm tra lại thông tin");
                } else {
                    message.error("Máy chủ bận");
                    console.log("errors: ", e);
                }
            });
    } else {
        return;
    }
};

// Handle Review form
function showModal() {
    visible.value = true;
}
function handleOk() {
    formReview.value.reviews.push({
        author: formReview.value.author,
        variable: formReview.value.variable,
        rating: formReview.value.rating,
        content: formReview.value.content,
        date: formReview.value.date,
    });
    visible.value = false;
    resetForm();
}
function resetForm() {
    formReview.value = {
        author: "",
        variable: "",
        rating: 0,
        content: "",
        date: null,
    };
}
function handleUpdateReview() {
    // formEditReview.value.reviews.push({
    //     author: formEditReview.value.author,
    //     variable: formEditReview.value.variable,
    //     rating: formEditReview.value.rating,
    //     content: formEditReview.value.content,
    //     date: formEditReview.value.date,
    // });
    visibleEditReview.value = false;
    formEditReview.value = {
        author: "",
        variable: "",
        rating: 0,
        content: "",
        date: null,
    };
}
function handleEditReview(reviewItem) {
    visibleEditReview.value = true;
    formEditReview.value = reviewItem;
}
function handleDeleteReview(reviewItem) {}

//Handle Edit, change Each Variable
function handleVariantChange(value) {
    selectedVariant.value = value;
    resetVariantFields();
}
function resetVariantFields() {
    variableForm.value = {
        productCode: "",
        variantName: "",
        variantContent: "",
        variantParam: "",
        price: 0,
        discount_percent: 0,
        discount_price: 0,
        quantity: 0,
    };
}

//Loading Category sản phẩm
const category_fetch = ref(false);
const data_manager = ref([]);
let timeoutCategory;
let categoryValue = "";
function fetchCategoriesDropdown(value, callback) {
    if (timeoutCategory) {
        clearTimeout(timeoutCategory);
        timeoutCategory = null;
    }
    categoryValue = value;
    timeoutCategory = setTimeout(searchCategory(value, callback), 300);
}

const handleSearchCategory = async (val) => {
    fetchCategoriesDropdown(val, (data) => (data_manager.value = data));
};
const handleChangeCategory = (val, item) => {
    form.value.category = item;
    fetchCategoriesDropdown("", (data) => (data_manager.value = data));
};

async function searchCategory(value, callback) {
    category_fetch.value = true;
    const params = new URLSearchParams({
        name: value,
    });
    if (value) {
        await axios.get(`/api/categories?${params}`).then((response) => {
            if (categoryValue === value) {
                const result = response.data.data?.map((storage) => ({
                    label: storage.name,
                    value: storage.id,
                    data: storage,
                }));
                category_fetch.value = false;
                callback(result);
            }
        });
    } else {
        await axios.get(`/api/categories`).then((response) => {
            if (categoryValue === value) {
                const result = response.data.data?.map((storage) => ({
                    label: storage.name,
                    value: storage.id,
                    data: storage,
                }));
                category_fetch.value = false;
                callback(result);
            }
        });
    }
}

watch(form.value.category, () => {
    data_manager.value = [];
    category_fetch.value = false;
});

//Loading Đơn vị tính
const unitOptions = ref([]);
const unit_fetching = ref(false);
let timeoutUnit;
let valueUnit = "";
function fetchUnitDropdown(value, callback) {
    if (timeoutUnit) {
        clearTimeout(timeoutUnit);
        timeoutUnit = null;
    }
    valueUnit = value;
    timeoutUnit = setTimeout(searchUnit(value, callback), 300);
}

const handleSearchUnit = async (val) => {
    fetchUnitDropdown(val, (data) => (unitOptions.value = data));
};
const handleChangeUnit = (val, item) => {
    form.value.unit = item;
    fetchUnitDropdown("", (data) => (unitOptions.value = data));
};

async function searchUnit(value, callback) {
    unit_fetching.value = true;
    const params = new URLSearchParams({
        name: value,
    });
    if (value) {
        await axios.get(`/api/units?${params}`).then((response) => {
            if (valueUnit === value) {
                const result = response.data.data?.map((unit) => ({
                    label: unit.name,
                    value: unit.id,
                    data: unit,
                }));
                unit_fetching.value = false;
                callback(result);
            }
        });
    } else {
        await axios.get(`/api/units`).then((response) => {
            if (valueUnit === value) {
                const result = response.data.data?.map((unit) => ({
                    label: unit.name,
                    value: unit.id,
                    data: unit,
                }));
                unit_fetching.value = false;
                callback(result);
            }
        });
    }
}

watch(form.value.unit, () => {
    unitOptions.value = [];
    unit_fetching.value = false;
});

// Xử lý mở form thêm biến thể
const handleOpenAddVariant = () => {
    // Do something

    openCreateVariantForm.value = !openCreateVariantForm.value;
};

const handleAutoAddVariant = () => {
    openAutoAddVariant.value = true;
};

const autoAddVariantFunc = async () => {};

const handleEditSelectedAttribute = () => {
    openEditSelectedAttribute.value = true;
};

const handleSetAttributeVariant = (val, item) => {
    let selectedData = item.options.find((o) => o.value == val);
    if (formVariables.value.name) {
        formVariables.value.name += ` ---- ${item.name}: ${selectedData.data.name}`;
    } else {
        formVariables.value.name = `${form.value.name} ---- ${item.name}: ${selectedData.data.name}`;
    }
};

const handleCreateNewVariant = async () => {
    try {
        // Perform form submission logic here
        errorInfo.value = [];
        let formData = new FormData();
        formData.append("sku", formVariables.value.sku);
        formData.append("product_id", router.currentRoute.value.params.id);
        if (
            formVariables.value.image &&
            formVariables.value.image.length > 0 &&
            formVariables.value.image[0].originFileObj
        ) {
            formData.append(
                "image",
                formVariables.value.image[0].originFileObj
            );
        }
        formData.append("price", formVariables.value.price);
        formData.append(
            "price_sale",
            formVariables.value.price - formVariables.value.discount_price
        );
        if (formVariables.value.product_description) {
            formData.append(
                "description",
                formVariables.value.product_description
            );
        }
        let selectedAttribute = formVariables.value.attributes.filter(
            (item) => item.attributes_id != null
        );
        if (selectedAttribute.length > 0) {
            let items = selectedAttribute.map((item) => ({
                attribute_group_id: item.id,
                attribute_id: item.attributes_id,
            }));

            console.log(items);
            formData.append("items[]", items);
        }
        const response = await axios.post(`/api/variants`, formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        });
        if (response.data.code == 200) {
            message.success(response.data.message);
            router.push({ name: "product-index" });
        }
    } catch (e) {
        if (e.response.status == 422) {
            errorInfo.value = Object.values(e.response.data.errors);
            message.error("Vui lòng kiểm tra lại thông tin");
        } else {
            message.error("Máy chủ bận");
            console.log("errors: ", e);
        }
    }
};

let plainOptions = reactive([]);
let state = reactive({
    indeterminate: true,
    checkAll: false,
    checkedList: [],
});
const onCheckAllChange = (e) => {
    Object.assign(state, {
        checkedList: e.target.checked ? plainOptions : [],
        indeterminate: false,
    });
};
watch(
    () => state.checkedList,
    (val) => {
        state.indeterminate = !!val.length && val.length < plainOptions.length;
        state.checkAll = val.length === plainOptions.length;
    }
);

// Query Data of Product

const queryDataProduct = (params) => {
    return axios.get(`/api/products/${router.currentRoute.value.params.id}`);
};
const { data: dataProduct, loading: loadingProduct } =
    usePagination(queryDataProduct);

const queryDataAttribute = (params) => {
    return axios.get(
        `/api/products/${router.currentRoute.value.params.id}/attributes`
    );
};
const { data: dataAttribute, loading: loadingAttribute } =
    usePagination(queryDataAttribute);

watch(
    () => dataAttribute.value,
    (newValue) => {
        if (newValue.data?.data.length > 0) {
            formAttributes.value.selectedAttributes = newValue.data.data.map(
                (item) => ({
                    id: item.id,
                    name: item.name,
                    data: item,
                })
            );
            formAttributes.value.attributes = newValue.data.data.map(
                (item) => ({
                    id: {
                        value: item.id,
                        label: item.name,
                    },
                    options: [newValue.data],
                    loading: false,
                })
            );
            formVariables.value.attributes = newValue.data.data.map((item) => ({
                id: item.id,
                name: item.name,
                attributes_id: null,
                options: item.attributes.map((attr) => ({
                    value: attr.id,
                    label: attr.name,
                    data: attr,
                })),
                loading: false,
            }));
        }
    }
);

watch(
    () => dataProduct.value,
    (newValue) => {
        if (newValue.data?.item) {
            let data = newValue.data.item;
            form.value = {
                name: data.name ?? null,
                image: data.image
                    ? [
                          {
                              name: "image.png",
                              url: data.image,
                          },
                      ]
                    : [],
                category: data.category
                    ? {
                          value: data.category.id,
                          data: data.category,
                          label: data.category.name,
                      }
                    : null,
                sku: data.sku ?? null,
                unit: data.unit
                    ? {
                          value: data.unit.id,
                          data: data.unit,
                          label: data.unit.name,
                      }
                    : null,
                is_active: data.is_active,
                amount: data.qty ?? 0,
                price: data.price ?? 0,
                discount_percent: Math.round(
                    (data.price_sale * 100) / data.price
                ),
                discount_price: data.price - data.price_sale,
                product_description: data.description ?? "",
            };
            if (data.product_warehouses.length > 0) {
                let warehousesArr = data.product_warehouses.map((item) => ({
                    warehouse_id: {
                        value: item.warehouse.id,
                        label: item.warehouse.name,
                        data: item.warehouse,
                    },
                    quantity: item.qty,
                    loading: false,
                    options: [],
                }));
                form.value.warehouses = warehousesArr;
            } else {
                form.value.warehouses = [];
            }
        } else {
            router.push({ name: "product-index" });
        }
    }
);
</script>

<style lang="scss" scoped>
.main-pyc {
    @apply space-y-4;
    .search-item {
        @apply flex flex-col gap-1;
        .title {
            @apply font-medium;
        }
    }
    .pyc-table {
        .statistic-ticket {
            .statistic-ticket-item {
                @apply flex items-center gap-1;
                font-size: 14px;
                .counting-box {
                    padding: 4px 6px;
                    border-radius: 4px;
                    font-weight: bold;
                    text-align: center;
                    color: white;
                    font-size: 13px;
                    &.pending {
                        @apply bg-blue-500;
                    }
                    &.progress {
                        background-color: #f0ad4e;
                    }
                    &.finish {
                        background-color: #5cb85c;
                    }
                    &.following {
                        background-color: #777777;
                    }
                }
            }
        }
    }
}
.ant-upload-select-picture-card i {
    font-size: 32px;
    color: #999;
}

.ant-upload-select-picture-card .ant-upload-text {
    margin-top: 8px;
    color: #666;
}
</style>
<style lang="scss">
.main-pyc {
    .ant-collapse-content-box {
        background-color: white !important;
        border: 1px solid rgb(59 130 246 / var(--tw-bg-opacity));
        border-radius: 8;
    }
    .ant-table-fixed {
        table-layout: fixed;
    }
    .ant-table-cell {
        .status-box {
            width: fit-content;
            padding: 4px 8px;
            border-radius: 4px;
            color: white;
            &.pending {
                @apply bg-blue-500;
            }
            &.progress {
                background-color: #f0ad4e;
            }
            &.finish {
                background-color: #5cb85c;
            }
            &.following {
                background-color: #777777;
            }
        }
    }
}
.ant-input {
    box-sizing: border-box;
    margin: 0;
    padding: 4px 11px;
    color: rgba(0, 0, 0, 0.88);
    font-size: 14px;
    line-height: 1.5;
    list-style: none;
    position: relative;
    display: inline-block;
    width: 100%;
    min-width: 0;
    background-color: #ffffff;
    background-image: none;
    border-width: 1px;
    border-style: solid;
    border-color: #d9d9d9;
    border-radius: 6px;
    transition: all 0.2s;
}

.ant-select-selection-search {
    input[type="search"] {
        box-shadow: none !important;
    }
}
</style>
