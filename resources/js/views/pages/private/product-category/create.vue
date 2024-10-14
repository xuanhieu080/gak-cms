<template>
    <Page>
        <div class="warehouse-create space-y-4">
            <a-breadcrumb :routes="routes" class="bg-gray-100 p-2">
                <template #itemRender="{ route, params, routes, paths }">
                    <span
                        v-if="routes.indexOf(route) === routes.length - 1"
                        class="text-blue-500"
                    >
                        {{ route.breadcrumbName }}
                    </span>
                    <router-link
                        v-else
                        :to="{ name: route.name }"
                        class="!text-black"
                        >{{ route.breadcrumbName }}
                    </router-link>
                </template>
            </a-breadcrumb>
            <div class="warehouse-form border border-blue-500 flex flex-col">
                <div
                    class="title-box bg-blue-500 text-white p-3 flex items-center gap-1"
                >
                    Tạo nhóm sản phẩm
                </div>
                <div class="p-6 warehouse-create-form gap-6">
                    <a-form
                        :model="formState"
                        layout="vertical"
                        @finishFailed="onFinishFailed"
                    >
                        <a-form-item v-bind="validateInfos.category_name">
                            <template class="h-full" #label>
                                <span class="font-medium"
                                    >Tên nhóm sản phẩm</span
                                >
                            </template>
                            <a-input
                                v-model:value="formState.category_name"
                                placeholder=""
                            />
                        </a-form-item>
                        <a-form-item label="Hình ảnh" name="image">
                            <a-upload-dragger
                                :before-upload="beforeUploadFImg"
                                :max-count="1"
                                @preview="handlePreviewFImg"
                                list-type="picture-card"
                                v-model:file-list="formState.image"
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
                        <a-form-item v-bind="validateInfos.parent_category_id">
                            <template class="h-full" #label>
                                <span class="font-medium"
                                    >Nhóm sản phẩm cha (Optional)</span
                                >
                            </template>
                            <a-select
                                v-model:value="formState.parent_category_id"
                                :options="data_manager"
                                :not-found-content="
                                    data_manager_fetching ? undefinded : null
                                "
                                placeholder="Chọn nhóm sản phẩm"
                                @search="handleSearchCategory"
                                @change="handleChangeCategory"
                                :filter-option="false"
                                show-search
                            >
                                <template #notFoundContent>
                                    <a-spin
                                        v-if="data_manager_fetching"
                                        size="small"
                                    />
                                    <span
                                        v-if="
                                            data_manager.length == 0 &&
                                            !data_manager_fetching
                                        "
                                        >Không có kết quả nào</span
                                    >
                                </template>
                            </a-select>
                        </a-form-item>
                        <a-form-item v-bind="validateInfos.description">
                            <template class="h-full" #label>
                                <span class="font-medium">Mô tả</span>
                            </template>
                            <a-textarea
                                v-model:value="formState.description"
                                placeholder=""
                                rows="5"
                                :resize="false"
                            />
                        </a-form-item>
                        <a-form-item v-bind="validateInfos.is_active">
                            <template class="h-full" #label>
                                <span class="font-medium">Hoạt động</span>
                            </template>
                            <a-switch
                                v-model:checked="formState.is_active"
                                placeholder=""
                            />
                        </a-form-item>
                        <a-form-item>
                            <div class="flex gap-6">
                                <a-button
                                    type="primary"
                                    block
                                    html-type="submit"
                                    @click.prevent="onSubmit"
                                >
                                    Thêm mới
                                </a-button>
                                <a-button
                                    type="primary"
                                    danger
                                    block
                                    @click="handleChangeCategoryPage"
                                >
                                    Hủy bỏ
                                </a-button>
                            </div>
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
                    </a-form>
                </div>
            </div>
        </div>
    </Page>
</template>

<script setup>
import { CalendarOutlined } from "@ant-design/icons-vue";
import { ref, reactive, watch, onMounted } from "vue";
import axios from "axios";
import { Form, message } from "ant-design-vue";
import Page from "@/views/layouts/Page";
import { useRouter } from "vue-router";

const router = useRouter();

const useForm = Form.useForm;

const data_manager = ref([]);
const data_manager_fetching = ref(false);
let timeout;
let currentValue = "";

const routes = ref([
    {
        name: "home",
        breadcrumbName: "Trang chủ",
    },
    {
        name: "category-index",
        breadcrumbName: "Quản lý nhóm sản phẩm",
    },
    {
        name: "category-create",
        breadcrumbName: "Tạo nhóm sản phẩm",
    },
]);

const formState = ref({
    parent_category_id: null,
    description: null,
    category_name: null,
    image: null,
    email: null,
    is_active: false,
});
const errorInfo = ref([]);
const { resetFields, validate, validateInfos } = useForm(
    formState.value,
    reactive({
        parent_category_id: [
            {
                required: false,
            },
        ],
        description: [
            {
                required: false,
            },
        ],
        category_name: [
            {
                required: true,
                message: "Vui lòng nhập tên kho",
            },
        ],
        image: [
            {
                required: false,
                message: "Vui lòng nhập Số điện thoại",
            },
        ],
        is_active: [
            {
                required: false,
            },
        ],
    })
);

const onSubmit = async () => {
    validate()
        .then(async (res) => {
            let formData = new FormData();
            formData.append("name", formState.value.category_name);
            if (formState.value.description) {
                formData.append("description", formState.value.description);
            }
            if (formState.value.image && formState.value.image.length > 0) {
                formData.append(
                    "image",
                    formState.value.image[0].originFileObj
                );
            }
            if (formState.value.parent_category_id) {
                formData.append(
                    "parent_id",
                    formState.value.parent_category_id.value
                );
            }
            formData.append("is_active", formState.value.is_active);
            const response = await axios.post("/api/categories", formData, {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            });
            if (response.data.code == 200) {
                message.success(response.data.message);
                handleChangeCategoryPage();
            }
        })
        .catch((err) => {
            if (err?.response?.status == 422) {
                errorInfo.value = Object.values(err.response.data.errors);
                message.error("Vui lòng kiểm tra lại thông tin");
            } else {
                console.log("error", err);
            }
        });
};

const handleChangeCategoryPage = () => {
    resetFields();
    router.push({ name: "category-index" });
};

function fetchCategoriesDropdown(value, callback) {
    if (timeout) {
        clearTimeout(timeout);
        timeout = null;
    }
    currentValue = value;
    timeout = setTimeout(searchCategory(value, callback), 300);
}

const handleSearchCategory = async (val) => {
    fetchCategoriesDropdown(val, (data) => (data_manager.value = data));
};
const handleChangeCategory = (val, item) => {
    formState.value.parent_category_id = item;
    fetchCategoriesDropdown("", (data) => (data_manager.value = data));
};

async function searchCategory(value, callback) {
    data_manager_fetching.value = true;
    const params = new URLSearchParams({
        name: value,
    });
    if (value) {
        await axios.get(`/api/categories?${params}`).then((response) => {
            if (currentValue === value) {
                const result = response.data.data?.map((storage) => ({
                    label: storage.name,
                    value: storage.id,
                    data: storage,
                }));
                data_manager_fetching.value = false;
                callback(result);
            }
        });
    } else {
        await axios.get(`/api/categories`).then((response) => {
            if (currentValue === value) {
                const result = response.data.data?.map((storage) => ({
                    label: storage.name,
                    value: storage.id,
                    data: storage,
                }));
                data_manager_fetching.value = false;
                callback(result);
            }
        });
    }
}

watch(formState.value.parent_category_id, () => {
    data_manager.value = [];
    data_manager_fetching.value = false;
});

// Load Hình ảnh thông tin nổi bật
function getBase64(file) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = () => resolve(reader.result);
        reader.onerror = (error) => reject(error);
    });
}
const beforeUploadFImg = (file) => {
    formState.value.image = [...(formState.value.image || []), file];
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

onMounted(() => {
    searchCategory("", (data) => (data_manager.value = data));
});
</script>

<style lang="scss" scoped>
.main-pyc-create {
    @apply space-y-4;
    .search-item {
        @apply flex flex-col gap-1;
        .title {
            @apply font-medium;
        }
    }
}
</style>
