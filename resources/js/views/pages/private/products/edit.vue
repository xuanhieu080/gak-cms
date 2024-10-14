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
            <a-card>
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
                                label="Slug"
                                name="slug"
                                :rules="[
                                    {
                                        required: true,
                                        message: 'Vui lòng nhập slug sản phẩm!',
                                    },
                                ]"
                            >
                                <a-input
                                    v-model:value="form.slug"
                                    placeholder="Nhập slug sản phẩm"
                                />
                            </a-form-item>
                            <a-form-item
                                label="Đường dẫn Video"
                                name="video_link"
                            >
                                <a-input
                                    v-model:value="form.video_link"
                                    placeholder="Nhập đường dẫn video"
                                />
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
                            >
                                <CkEditorCustom
                                    :key="'description-1'"
                                    :content="form.product_description"
                                />
                            </a-form-item>

                            <a-form-item
                                label="Thông tin nổi bật"
                                name="feature_description"
                            >
                                <CkEditorCustom
                                    :key="'description-2'"
                                    :content="form.feature_description"
                                />
                            </a-form-item>
                            <a-form-item
                                label="Hình ảnh thông tin nổi bật"
                                name="image_description_feature"
                            >
                                <a-upload-dragger
                                    :before-upload="beforeUploadFImg"
                                    :max-count="1"
                                    @preview="handlePreviewFImg"
                                    list-type="picture-card"
                                    v-model:file-list="form.feature_img"
                                    class="w-full"
                                >
                                    <div class="w-full">
                                        <PlusOutlined />
                                        <div class="mt-2">
                                            Kéo thả hoặc chọn thêm hình ảnh
                                        </div>
                                    </div>
                                </a-upload-dragger>
                                <a-modal
                                    :open="previewVisibleFImg"
                                    :title="previewFImgTitle"
                                    :footer="null"
                                    @cancel="handleCancelFImg"
                                >
                                    <img
                                        alt="feature-image"
                                        style="width: 100%"
                                        :src="previewFImg"
                                    />
                                </a-modal>
                            </a-form-item>
                            <a-form-item
                                label="SEO tiêu đề"
                                name="seo_title"
                                :rules="[
                                    {
                                        required: true,
                                        message:
                                            'Vui lòng nhập số lượng tồn kho!',
                                    },
                                ]"
                            >
                                <a-input
                                    v-model:value="form.seo_title"
                                    placeholder="Nhập SEO tiêu đề"
                                />
                            </a-form-item>
                            <a-form-item
                                label="SEO nội dung"
                                name="seo_description"
                                :rules="[
                                    {
                                        required: true,
                                        message:
                                            'Vui lòng nhập số lượng tồn kho!',
                                    },
                                ]"
                            >
                                <a-input
                                    v-model:value="form.seo_description"
                                    placeholder="Nhập SEO nội dung"
                                />
                            </a-form-item>
                            <a-form-item
                                label="SEO từ khóa"
                                name="seo_keyword"
                                :rules="[
                                    {
                                        required: true,
                                        message:
                                            'Vui lòng nhập số lượng tồn kho!',
                                    },
                                ]"
                            >
                                <a-input
                                    v-model:value="form.seo_keyword"
                                    placeholder="Nhập SEO từ khóa"
                                />
                            </a-form-item>

                            <a-form-item label="Hình ảnh SEO" name="seo_image">
                                <a-upload-dragger
                                    :before-upload="beforeUploadSEOImg"
                                    @preview="handlePreviewSEOImg"
                                    list-type="picture-card"
                                    v-model:file-list="form.seo_image"
                                    class="w-full"
                                >
                                    <div class="w-full">
                                        <PlusOutlined />
                                        <div class="mt-2">
                                            Kéo thả hoặc chọn thêm hình ảnh
                                        </div>
                                    </div>
                                </a-upload-dragger>
                                <a-modal
                                    :open="previewVisibleSEOImg"
                                    :title="previewSEOImgTitle"
                                    :footer="null"
                                    @cancel="handleCancelSEOImg"
                                >
                                    <img
                                        alt="feature-image"
                                        style="width: 100%"
                                        :src="previewSEOImg"
                                    />
                                </a-modal>
                            </a-form-item>
                            <a-form-item>
                                <a-button type="primary" html-type="submit"
                                    >Tạo mới</a-button
                                >
                            </a-form-item>
                        </a-form>
                    </a-tab-pane>
                    <a-tab-pane key="2" tab="Biến thể mới">
                        <a-form
                            ref="ruleForm"
                            :model="formVariables"
                            layout="vertical"
                        >
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
                                <a-input v-model:value="formVariables.name" />
                            </a-form-item>
                            <a-form-item
                                label="Màu sắc"
                                name="color"
                                :rules="[
                                    {
                                        required: true,
                                        message: 'Vui lòng chọn màu sắc!',
                                    },
                                ]"
                            >
                                <a-select
                                    v-model:value="formVariables.color"
                                    placeholder="Chọn màu sắc"
                                >
                                    <!-- Add your color options here -->
                                </a-select>
                            </a-form-item>
                            <a-form-item
                                label="Kích thước"
                                name="size"
                                :rules="[
                                    {
                                        required: true,
                                        message: 'Vui lòng chọn kích thước!',
                                    },
                                ]"
                            >
                                <a-select
                                    v-model:value="formVariables.size"
                                    placeholder="Chọn kích thước"
                                    class="w-full"
                                >
                                    <!-- Add your size options here -->
                                </a-select>
                            </a-form-item>
                            <a-form-item
                                label="Chất liệu"
                                name="material"
                                :rules="[
                                    {
                                        required: true,
                                        message: 'Vui lòng chọn chất liệu!',
                                    },
                                ]"
                            >
                                <a-select
                                    v-model:value="formVariables.material"
                                    placeholder="Chọn chất liệu"
                                    class="w-full"
                                >
                                    <!-- Add your material options here -->
                                </a-select>
                            </a-form-item>
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
                                ref="quantity"
                                label="Số lượng"
                                name="quantity"
                            >
                                <a-input-number
                                    min="0"
                                    class="w-full"
                                    v-model:value="formVariables.quantity"
                                />
                            </a-form-item>
                            <a-form-item>
                                <a-button type="primary" @click="submitForm"
                                    >Tạo biến thể</a-button
                                >
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
                                                    v-model:value="
                                                        attribute.attributeGroup
                                                    "
                                                    placeholder="Chọn nhóm thuộc tính"
                                                >
                                                    <!-- Add your attribute group options here -->
                                                </a-select>
                                            </a-form-item>
                                        </a-col>
                                        <a-col :span="7">
                                            <a-form-item
                                                :ref="`attribute-${index}`"
                                                :label="`Thuộc tính`"
                                                :name="`attributes.${index}.attribute`"
                                            >
                                                <a-select
                                                    v-model:value="
                                                        attribute.attribute
                                                    "
                                                    placeholder="Chọn thuộc tính"
                                                >
                                                    <!-- Add your attribute options here -->
                                                </a-select>
                                            </a-form-item>
                                        </a-col>
                                        <a-col :span="4">
                                            <a-form-item
                                                :ref="`highlight-${index}`"
                                                :label="`Màu nổi bật`"
                                                :name="`attributes.${index}.highlight`"
                                            >
                                                <a-switch
                                                    v-model:checked="
                                                        attribute.highlight
                                                    "
                                                />
                                            </a-form-item>
                                        </a-col>
                                        <a-col :span="4">
                                            <a-form-item
                                                :ref="`featured-${index}`"
                                                :label="`Thuộc tính chính ${
                                                    index + 1
                                                }`"
                                                :prop="`attributes.${index}.featured`"
                                            >
                                                <a-switch
                                                    v-model:checked="
                                                        attribute.featured
                                                    "
                                                />
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
                            <div>
                                <h3>Thuộc tính đã chọn</h3>
                                <div
                                    v-for="(
                                        selectedAttribute, index
                                    ) in form.selectedAttributes"
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
                                                    v-model:value="
                                                        selectedAttribute.$eventattributeGroup
                                                    "
                                                    placeholder="Chọn nhóm thuộc tính"
                                                >
                                                    <!-- Add your attribute group options here -->
                                                </a-select>
                                            </a-form-item>
                                        </a-col>
                                        <a-col :span="7">
                                            <a-form-item
                                                :ref="`attribute-${index}`"
                                                :label="`Thuộc tính`"
                                                :name="`attributes.${index}.attribute`"
                                            >
                                                <a-select
                                                    v-model:value="
                                                        selectedAttribute.$eventattribute
                                                    "
                                                    placeholder="Chọn thuộc tính"
                                                >
                                                    <!-- Add your attribute options here -->
                                                </a-select>
                                            </a-form-item>
                                        </a-col>
                                        <a-col :span="4">
                                            <a-form-item
                                                :ref="`highlight-${index}`"
                                                :label="`Màu nổi bật`"
                                                :name="`attributes.${index}.highlight`"
                                            >
                                                <a-switch
                                                    v-model:checked="
                                                        selectedAttribute.$eventhighlight
                                                    "
                                                />
                                            </a-form-item>
                                        </a-col>
                                        <a-col :span="4">
                                            <a-form-item
                                                :ref="`featured-${index}`"
                                                :label="`Thuộc tính chính ${
                                                    index + 1
                                                }`"
                                                :prop="`attributes.${index}.featured`"
                                            >
                                                <a-switch
                                                    v-model:checked="
                                                        selectedAttribute.$eventfeatured
                                                    "
                                                />
                                            </a-form-item>
                                        </a-col>
                                        <a-col :span="2">
                                            <div
                                                class="flex items-center gap-1 mt-2.5"
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
                            <a-form-item>
                                <a-button type="primary" @click="submitForm"
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
    SearchOutlined,
    PlusOutlined,
    DeleteOutlined,
    EditOutlined,
    SyncOutlined,
    ReloadOutlined,
    UserOutlined,
    PhoneTwoTone,
    MinusCircleOutlined,
} from "@ant-design/icons-vue";
import { ref } from "vue";
import { useRouter } from "vue-router";
import { message } from "ant-design-vue";
import Page from "@/views/layouts/Page";
import CkEditorCustom from "@/views/components/CkEditorCustom.vue";
import dayjs from "dayjs";
const form = ref({
    name: "",
    slug: "",
    video_link: "",
    image: [],
    amount: null,
    price: 0,
    discount_percent: 0,
    discount_price: 0,
    product_description: "",
    feature_description: "",
    feature_img: [],
    seo_title: "",
    seo_description: "",
    seo_keyword: "",
    seo_image: [],
});
const formVariables = ref({
    name: "",
    video_link: "",
    image: [],
    amount: null,
    price: 0,
    discount_percent: 0,
    discount_price: 0,
    product_description: "",
    feature_description: "",
    feature_img: [],
    seo_title: "",
    seo_description: "",
    seo_keyword: "",
    seo_image: [],
});
const formAttributes = ref({
    attributes: [
        {
            attributeGroup: undefined,
            attribute: undefined,
            highlight: false,
            featured: false,
        },
    ],
    selectedAttributes: [],
});

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

const handleSubmit = async () => {
    try {
        // Perform form submission logic here
        let formData = new FormData();
        formData.append("name", form.value.name);
        formData.append("password", form.value.password);
        formData.append("email", form.value.email);
        if (form.value.image && form.value.image.length > 0) {
            formData.append("image", form.value.image[0].originFileObj);
        }
        router.push({ name: "product-index" });
    } catch (error) {
        message.error("An error occurred. Please try again.");
    }
};
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
const beforeUploadFImg = (file) => {
    form.value.feature_img = [...(form.value.feature_img || []), file];
    return false;
};
const previewVisibleFImg = ref(false);
const previewFImg = ref("");
const previewFImgTitle = ref("");
const handlePreviewFImg = async (file) => {
    if (!file.url && !file.preview) {
        file.preview = await getBase64(file.originFileObj);
    }
    previewFImg.value = file.url || file.preview;
    previewVisibleFImg.value = true;
    previewFImgTitle.value =
        file.name || file.url.substring(file.url.lastIndexOf("/") + 1);
};
const handleCancelFImg = () => {
    previewVisibleFImg.value = false;
    previewFImgTitle.value = "";
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

// Handle Add Attributee
function addAttribute() {
    formAttributes.value.attributes.push({
        attributeGroup: undefined,
        attribute: undefined,
        highlight: false,
        featured: false,
    });
}
function removeAttribute(index) {
    formAttributes.value.attributes.splice(index, 1);
}
function addAttributeToProduct(index) {
    const attribute = formAttributes.value.attributes[index];
    if (attribute.attributeGroup && attribute.attribute) {
        formAttributes.value.selectedAttributes.push({
            attributeGroup: attribute.attributeGroup,
            attribute: attribute.attribute,
            highlight: attribute.highlight,
            featured: attribute.featured,
        });
    }
}
function removeSelectedAttribute(index) {
    formAttributes.value.selectedAttributes.splice(index, 1);
}

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
