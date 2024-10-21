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
                <a-form
                    :model="form"
                    layout="vertical"
                    @submit.prevent="handleCreateProduct"
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
                                <a-spin v-if="category_fetch" size="small" />
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
                            :max-count="1"
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
                        v-if="false"
                        class="w-full"
                        label="Số lượng tồn kho"
                        name="amount"
                        :autoLink="false"
                        :rules="[
                            {
                                required: true,
                                message: 'Vui lòng nhập số lượng tồn kho!',
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
                                <a-spin v-if="unit_fetching" size="small" />
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
                                (value) => value.replace(/\$\s?|(,*)/g, '')
                            "
                            class="w-full"
                            @change="calculateDiscountedPrice"
                        />
                    </a-form-item>
                    <a-form-item label="% giảm giá" name="discount_percent">
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
                                (value) => value.replace(/\$\s?|(,*)/g, '')
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
                                :name="['warehouses', index, 'warehouse_id']"
                                :autoLink="false"
                                :rules="[
                                    {
                                        required: true,
                                        message: 'Vui lòng chọn kho',
                                    },
                                ]"
                            >
                                <a-select
                                    v-model:value="warehouse.warehouse_id"
                                    placeholder="Chọn kho"
                                    :loading="warehouse.loading"
                                    :options="warehouse.options"
                                    :not-found-content="
                                        warehouse.loading ? undefinded : null
                                    "
                                    show-search
                                    @search="
                                        (val) =>
                                            handleSearchStorage(val, warehouse)
                                    "
                                    @change="
                                        (val) =>
                                            handleChangeStorage(val, warehouse)
                                    "
                                    @click="handleSearchStorage('', warehouse)"
                                >
                                    <template #notFoundContent>
                                        <a-spin
                                            v-if="warehouse.loading"
                                            size="small"
                                        />
                                        <span
                                            v-if="
                                                warehouse.options.length == 0 &&
                                                !warehouse.loading
                                            "
                                            >Không có kết quả nào</span
                                        >
                                    </template>
                                </a-select>
                            </a-form-item>
                            <a-form-item
                                :label="`Số lượng`"
                                :name="['warehouses', index, 'quantity']"
                                :autoLink="false"
                                :rules="[
                                    {
                                        required: true,
                                        message: 'Vui lòng nhập số lượng',
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
                                    v-if="form.warehouses.length > 1"
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
                        <a-button type="primary" html-type="submit"
                            >Tạo mới</a-button
                        >
                    </a-form-item>
                </a-form>
            </a-card>
        </div>
    </Page>
</template>

<script setup>
import {
    SearchOutlined,
    PlusOutlined,
    DeleteOutlined,
    SyncOutlined,
    ReloadOutlined,
    UserOutlined,
    MinusOutlined,
} from "@ant-design/icons-vue";
import { ref, reactive, onMounted, watch } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";
import { message } from "ant-design-vue";
import Page from "@/views/layouts/Page";
import CkEditorCustom from "@/views/components/CkEditorCustom.vue";

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
    feature_description: "",
    feature_img: [],
    seo_title: "",
    seo_description: "",
    seo_keyword: "",
    seo_image: [],
    warehouses: [
        {
            warehouse_id: null,
            quantity: null,
            loading: false,
            options: [],
        },
    ],
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
        name: "product-create",
        breadcrumbName: "Tạo sản phẩm",
    },
]);

const errorInfo = ref([]);
const warehouseOptions = ref([]);
const data_warehouse_fetching = ref(false);
let timeout;
let currentValue = "";

const router = useRouter();

//Handle Description
const handleUpdateDescription = (value) => {
    form.value.product_description = value;
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

function customRound(value) {
    if (value % 1 >= 0.5) {
        return Math.floor(value);
    } else {
        return Math.round(value);
    }
}

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
function fetchStorageDropdown(value, item = null, callback) {
    if (timeout) {
        clearTimeout(timeout);
        timeout = null;
    }
    currentValue = value;
    timeout = setTimeout(searchStorage(value, item, callback), 300);
}

const handleSearchStorage = async (val, ỉtem = null) => {
    ỉtem.loading = true;
    fetchStorageDropdown(val, ỉtem, (data) => (ỉtem.options = data));
};
const handleChangeStorage = (val, item) => {
    item.warehouse_id = val;
    item.loading = false;
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
        await axios.get(`/api/warehouses`).then((response) => {
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

//Handle Submit Product
const handleCreateProduct = async () => {
    try {
        // Perform form submission logic here
        let formData = new FormData();
        formData.append("name", form.value.name);
        formData.append("sku", form.value.sku);
        formData.append("category_id", form.value.category.value);
        formData.append("is_active", form.value.is_active);
        if (form.value.image && form.value.image.length > 0) {
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
        if (form.value.warehouses.length > 0) {
            form.value.warehouses.forEach((item, index) => {
                formData.append("category_id", item.warehouse_id);
                formData.append("qty", item.quantity);
            });
        }

        const response = await axios.post("/api/products", formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        });
        if (response.data.code == 200) {
            message.success(response.data.message);
            router.push({ name: "product-index" });
        }
        // router.push({ name: "product-index" });
    } catch (err) {
        if (err?.response?.status == 422) {
            errorInfo.value = Object.values(err.response.data.errors);
            message.error("Vui lòng kiểm tra lại thông tin");
        } else {
            message.error("An error occurred. Please try again.");
            console.log("error", err);
        }
    }
};
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
