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
                    <a-form-item label="Sku" name="sku">
                        <a-input
                            v-model:value="form.sku"
                            placeholder="Nhập sku sản phẩm"
                        />
                    </a-form-item>
                    <a-form-item name="category" label="Nhóm sản phẩm">
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
                    <a-form-item label="Hình ảnh (Tối đa 1 tấm)" name="image">
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

                    <a-form-item class="w-full" label="Giá mua" name="price">
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
                        />
                    </a-form-item>
                    <a-form-item
                        label="Giá bán"
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
                        />
                    </a-form-item>

                    <a-form-item
                        label="Mô tả sản phẩm"
                        name="product_description"
                    >
                        <a-textarea
                            v-model:value="form.product_description"
                            rows="5"
                        ></a-textarea>
                    </a-form-item>
                    <hr />
                    <div class="flex flex-col gap-4 w-full my-4">
                        <div class="text-3xl font-bold">
                            Thông Tin Thuộc Tính
                        </div>
                        <div
                            v-for="(attribute, index) in attributes"
                            :key="index"
                            class="attribute-dropdown-tag"
                        >
                            <div class="w-full">
                                <a-form-item
                                    :label="`${attribute.name}`"
                                    :name="['attributes', index, 'id']"
                                >
                                    <a-select
                                        v-model:value="attribute.options"
                                        :placeholder="'Nhập ' + attribute.name"
                                        :loading="attribute.loading"
                                        mode="tags"
                                        :options="[]"
                                    >
                                    </a-select>
                                </a-form-item>
                            </div>
                        </div>
                        <div class="note text-sm text-gray-500 italic">
                            <b class="text-red-500">*</b>
                            <b
                                >Vui lòng nhập đầy đủ thông tin sản phẩm ở trên
                                trước khi tạo biến thể!.</b
                            >
                            <div>
                                <b class="text-red-500">*</b> Sau khi thêm thông
                                tin thuộc tính, bấm nút để tạo danh sách biến
                                thể theo thuộc tính đã nhập.
                            </div>
                        </div>
                        <a-button
                            type="primary"
                            ghost
                            class="w-fit"
                            @click="handleGenerateVariants"
                            >Tạo biến thể đồng loạt</a-button
                        >
                    </div>
                    <hr />

                    <a-table
                        v-if="hasVariant"
                        :row-selection="rowSelection"
                        :columns="columns"
                        :loading="loadingVariant"
                        :row-key="(record) => record.id"
                        :data-source="variantsData"
                        @change="handleTableChange"
                        bordered
                        :scroll="{ x: 'max-content' }"
                    >
                        <template #bodyCell="{ column, text, index, record }">
                            <template v-if="column.dataIndex === 'image'">
                                <a-upload-dragger
                                    :before-upload="(e) => beforeUploadVariantImg(e,record)"
                                    @preview="(e) => handlePreviewVariantImg(e,record)"
                                    :max-count="1"
                                    list-type="picture-card"
                                    v-model:file-list="record.image"
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
                            </template>
                            <template v-if="column.dataIndex === 'name'">
                                <div class="flex flex-col gap-1">
                                    <span class="text-blue-700">{{
                                        record.name
                                    }}</span>
                                </div>
                            </template>
                            <template v-if="column.dataIndex === 'unit'">
                                <div class="flex flex-col gap-1">
                                    <span class="text-blue-700">{{
                                        record.unit
                                    }}</span>
                                </div>
                            </template>
                            <template v-if="column.dataIndex === 'price'">
                                <a-input v-model:value="record.price"></a-input>
                            </template>
                            <template
                                v-if="column.dataIndex === 'discount_price'"
                            >
                                <a-input
                                    v-model:value="record.discount_price"
                                ></a-input>
                            </template>
                            <template v-if="column.dataIndex === 'action'">
                                <div class="flex items-center justify-center">
                                    <a-tooltip>
                                        <template #title>Xóa</template>
                                        <DeleteOutlined
                                            @click="handleDeleteVariant(index)"
                                            :style="{ color: 'red' }"
                                        />
                                    </a-tooltip>
                                </div>
                            </template>
                        </template>
                    </a-table>

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
import { usePagination } from "vue-request";
import { useRouter } from "vue-router";
import axios from "axios";
import { message } from "ant-design-vue";
import Page from "@/views/layouts/Page";

const columns = [
    {
        title: "image",
        dataIndex: "image",
    },
    {
        title: "Tên Sản phẩm",
        dataIndex: "name",
    },
    {
        title: "Mã Sản phẩm",
        dataIndex: "sku",
    },
    {
        title: "Đơn vị tính",
        dataIndex: "unit",
        sorter: true,
    },
    {
        title: "Giá nhập",
        dataIndex: "price",
        sorter: true,
    },
    {
        title: "Giá bán",
        dataIndex: "discount_price",
        sorter: true,
    },
    {
        title: "Hành động",
        dataIndex: "action",
        fixed: "right",
    },
];

const form = ref({
    name: "",
    sku: "",
    category: null,
    is_active: false,
    image: [],
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

const attributes = ref([]);

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
const previewVisible = ref(false);
const previewImage = ref("");
const previewTitle = ref("");
const beforeUpload = (file) => {
    form.value.image = [...(form.value.image || []), file];
    return false;
};

const handlePreview = async (file) => {
    if (!file.url && !file.preview) {
        file.preview = await getBase64(file.originFileObj);
    }
    previewImage.value = file.url || file.preview;
    previewVisible.value = true;
    previewTitle.value =
        file.name || file.url.substring(file.url.lastIndexOf("/") + 1);
};
const beforeUploadVariantImg = (file, record) => {
    record.image = [...(record.image || []), file];
    return false;
};

const handlePreviewVariantImg = async (file, record) => {
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

// Loading Danh sách nhóm thuộc tính
const queryDataAttribute = (params) => {
    return axios.get(`/api/attribute-groups`);
};
const { data: dataAttribute, loading: loadingAttribute } =
    usePagination(queryDataAttribute);

watch(
    () => dataAttribute.value,
    (newValue) => {
        if (newValue.data?.data.length > 0) {
            attributes.value = newValue.data.data.map((item) => ({
                id: item.id,
                name: item.name,
                options: [],
                loading: false,
            }));
        }
    }
);

//Handle generate variant based on inputed attribute which is save in options variable.
const variantsData = ref([]);
const hasVariant = ref(false);
const loadingVariant = ref(false);
const handleGenerateVariants = () => {
    loadingVariant.value = true;
    const selectedOptions = attributes.value
        .filter((attribute) => attribute.options.length > 0)
        .map((attribute) => attribute.options);
    if (selectedOptions.length === 0) {
        return;
    }
    variantsData.value = [];
    hasVariant.value = false;
    const variants = cartesianProduct(...selectedOptions);
    variants.forEach((variant) => {
        const variantName = form.value.name + "( " + variant.join(" / ") + " )";
        const variantPrice = form.value.price;
        const price_export = form.value.discount_price;
        // VariantSku I want it have the format I want it to be like this: sku-option1-option2-option3. If option1 have the blank string, then join it without blank
        const variantSku = form.value.sku + "-" + removeAccents(variant.join("-"));
        const variantImage = [];
        const variantAttributes = attributes.value.map((attribute, index) => ({
            name: attribute.name,
            value: variant[index],
        }));
        const variantObject = {
            name: variantName,
            price: variantPrice,
            discount_price: price_export,
            sku: variantSku,
            unit: form.value.unit ? form.value.unit.label : null,
            image: variantImage,
            attributes: variantAttributes,
        };
        variantsData.value.push(variantObject);
    });
    hasVariant.value = true;
    loadingVariant.value = false;

    // Update the form data with the generated variants
};
//cartesianProduct
function cartesianProduct(...arrays) {
    const result = [];
    const helper = (arr, i) => {
        for (let j = 0; j < arrays[i].length; j++) {
            const copy = [...arr];
            copy.push(arrays[i][j]);
            if (i < arrays.length - 1) {
                helper(copy, i + 1);
            } else {
                result.push(copy);
            }
        }
    };
    helper([], 0);
    return result;
}

// Remove accents
function removeAccents(str) {
  const accentsMap = {
    'á': 'a', 'à': 'a', 'ả': 'a', 'ã': 'a', 'ạ': 'a',
    'ă': 'a', 'ắ': 'a', 'ằ': 'a', 'ẳ': 'a', 'ẵ': 'a', 'ặ': 'a',
    'â': 'a', 'ấ': 'a', 'ầ': 'a', 'ẩ': 'a', 'ẫ': 'a', 'ậ': 'a',
    'é': 'e', 'è': 'e', 'ẻ': 'e', 'ẽ': 'e', 'ẹ': 'e',
    'ê': 'e', 'ế': 'e', 'ề': 'e', 'ể': 'e', 'ễ': 'e', 'ệ': 'e',
    'í': 'i', 'ì': 'i', 'ỉ': 'i', 'ĩ': 'i', 'ị': 'i',
    'ó': 'o', 'ò': 'o', 'ỏ': 'o', 'õ': 'o', 'ọ': 'o',
    'ô': 'o', 'ố': 'o', 'ồ': 'o', 'ổ': 'o', 'ỗ': 'o', 'ộ': 'o',
    'ơ': 'o', 'ớ': 'o', 'ờ': 'o', 'ở': 'o', 'ỡ': 'o', 'ợ': 'o',
    'ú': 'u', 'ù': 'u', 'ủ': 'u', 'ũ': 'u', 'ụ': 'u',
    'ư': 'u', 'ứ': 'u', 'ừ': 'u', 'ử': 'u', 'ữ': 'u', 'ự': 'u',
    'ý': 'y', 'ỳ': 'y', 'ỷ': 'y', 'ỹ': 'y', 'ỵ': 'y',
    'đ': 'd'
  };

  return str.toLowerCase().replace(/[áàảãạăắằẳẵặâấầẩẫậéèẻẽẹêếềểễệíìỉĩịóòỏõọôốồổỗộơớờởỡợúùủũụưứừửữựýỳỷỹỵđ]/gu, char => accentsMap[char]);
}

const handleDeleteVariant = (index) => {
    variantsData.value.splice(index, 1);
}
//Handle Submit Product
const handleCreateProduct = async () => {
    try {
        // Perform form submission logic here
        let formData = new FormData();
        formData.append("name", form.value.name);
        if (form.value.sku) {
            formData.append("sku", form.value.sku);
        }
        if (form.value.category) {
            formData.append("category_id", form.value.category.value);
        }
        if (form.value.image && form.value.image.length > 0) {
            formData.append("image", form.value.image[0].originFileObj);
        }
        formData.append("is_active", form.value.is_active);
        formData.append("unit_id", form.value.unit.value);
        formData.append("price", form.value.price);
        formData.append("price_import", form.value.discount_price);
        if (form.value.product_description) {
            formData.append("description", form.value.product_description);
        }
        // if (form.value.warehouses.length > 0) {
        //     form.value.warehouses.forEach((item, index) => {
        //         formData.append("category_id", item.warehouse_id);

        //     });
        // }
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
.ant-select-selection-item-remove {
    display: flex !important;
}
.attribute-dropdown-tag .ant-select-selector {
    min-height: 70px;
}
</style>
