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
                    Chỉnh sửa nhóm sản phẩm
                </div>
                <div class="p-6 warehouse-create-form gap-6">
                    <a-form
                        :model="formState"
                        layout="vertical"
                        :rules="validateInfos"
                    >
                        <a-form-item name="name" :autoLink="false">
                            <template class="h-full" #label>
                                <span class="font-medium">Tên khách hàng</span>
                            </template>
                            <a-input
                                v-model:value="formState.name"
                                placeholder=""
                            />
                        </a-form-item>
                        <a-form-item name="image" :autoLink="false">
                            <template class="h-full" #label>
                                <span class="font-medium">Hình ảnh </span>
                            </template>
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
                        <a-form-item name="email" :autoLink="false">
                            <template class="h-full" #label>
                                <span class="font-medium">Email</span>
                            </template>
                            <a-input
                                v-model:value="formState.email"
                                type="email"
                                placeholder=""
                            />
                        </a-form-item>
                        <a-form-item name="phone" :autoLink="false">
                            <template class="h-full" #label>
                                <span class="font-medium">Phone</span>
                            </template>
                            <a-input
                                id="phoneInput"
                                v-model:value="formState.phone"
                                v-only-numbers
                                type="text"
                                maxlength="10"
                                :autocomplete="false"
                                required
                            ></a-input>
                        </a-form-item>
                        <a-form-item name="address" :autoLink="false">
                            <template class="h-full" #label>
                                <span class="font-medium">Địa chỉ</span>
                            </template>
                            <a-input
                                v-model:value="formState.address"
                                placeholder=""
                            />
                        </a-form-item>
                        <a-form-item name="note" :autoLink="false">
                            <template class="h-full" #label>
                                <span class="font-medium">Ghi chú</span>
                            </template>
                            <a-textarea
                                v-model:value="formState.note"
                                placeholder=""
                                rows="5"
                                :resize="false"
                            />
                        </a-form-item>
                        <a-form-item name="discount" :autoLink="false">
                            <template class="h-full" #label>
                                <span class="font-medium">Discount</span>
                            </template>
                            <a-input-number
                                class="w-full"
                                v-model:value="formState.discount"
                                max="100"
                                min="0"
                                :autocomplete="false"
                            ></a-input-number>
                        </a-form-item>
                        <a-form-item name="is_active" :autoLink="false">
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
                                    Cập nhật
                                </a-button>
                                <a-button
                                    type="primary"
                                    danger
                                    block
                                    @click="handleChangeCustomerPage"
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
import { ref, reactive, watch, onMounted } from "vue";
import { usePagination } from "vue-request";
import axios from "axios";
import { Form, message } from "ant-design-vue";
import Page from "@/views/layouts/Page";
import { useRouter } from "vue-router";

const router = useRouter();

const useForm = Form.useForm;

const tempImg = ref([]);
const data_manager_fetching = ref(false);
let timeout;
let currentValue = "";

const routes = ref([
    {
        name: "home",
        breadcrumbName: "Trang chủ",
    },
    {
        name: "customer-index",
        breadcrumbName: "Quản lý nhóm sản phẩm",
    },
    {
        name: "customer-edit",
        breadcrumbName: "Chỉnh sửa nhóm sản phẩm",
    },
]);

const formState = ref({
    email: null,
    note: null,
    name: null,
    phone: null,
    address: null,
    discount: null,
    image: null,
    email: null,
    is_active: false,
});
const errorInfo = ref([]);
const validateInfos = ref({
    email: [
        {
            required: false,
        },
    ],
    note: [
        {
            required: false,
        },
    ],
    name: [
        {
            required: true,
            message: "Vui lòng nhập tên khách hàng",
        },
    ],
    phone: [
        {
            required: true,
            message: "Vui lòng nhập số điện thoại",
        },
    ],
    image: [
        {
            required: false,
        },
    ],
    is_active: [
        {
            required: false,
        },
    ],
});
const { resetFields, validate } = useForm(formState.value);

const onSubmit = async () => {
    validate()
        .then(async (res) => {
            let formData = new FormData();
            formData.append("name", formState.value.name);
            if (formState.value.image && formState.value.image.length > 0) {
                if (
                    tempImg.value[0].name != formState.value.image[0].name
                )
                    formData.append(
                        "image",
                        formState.value.image[0].originFileObj
                    );
            }
            if (formState.value.email) {
                formData.append("email", formState.value.email);
            }
            formData.append("phone", formState.value.phone);
            if (formState.value.address) {
                formData.append("address", formState.value.address);
            }
            if (formState.value.note) {
                formData.append("note", formState.value.note);
            }
            if (formState.value.discount) {
                formData.append("discount", formState.value.discount);
            }
            formData.append("is_active", formState.value.is_active);
            const response = await axios.post(
                `/api/customers/${router.currentRoute.value.params.id}`,
                formData,
                {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                }
            );
            if (response.data.code == 200) {
                message.success(response.data.message);
                handleChangeCustomerPage();
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

const handleChangeCustomerPage = () => {
    resetFields();
    router.push({ name: "customer-index" });
};

// function fetchCategoriesDropdown(value, callback) {
//     if (timeout) {
//         clearTimeout(timeout);
//         timeout = null;
//     }
//     currentValue = value;
//     timeout = setTimeout(searchCategory(value, callback), 300);
// }

// const handleSearchCategory = async (val) => {
//     fetchCategoriesDropdown(val, (data) => (data_manager.value = data));
// };
// const handleChangeCategory = (val, item) => {
//     formState.value.email = item;
//     fetchCategoriesDropdown("", (data) => (data_manager.value = data));
// };

// async function searchCategory(value, callback) {
//     data_manager_fetching.value = true;
//     const params = new URLSearchParams({
//         name: value,
//     });
//     if (value) {
//         await axios.get(`/api/categories?${params}`).then((response) => {
//             if (currentValue === value) {
//                 const result = response.data.data?.map((storage) => ({
//                     label: storage.name,
//                     value: storage.id,
//                     data: storage,
//                     disabled: storage.id == router.currentRoute.value.params.id,
//                 }));
//                 data_manager_fetching.value = false;
//                 callback(result);
//             }
//         });
//     } else {
//         await axios.get(`/api/categories`).then((response) => {
//             if (currentValue === value) {
//                 const result = response.data.data?.map((storage) => ({
//                     label: storage.name,
//                     value: storage.id,
//                     data: storage,
//                     disabled: storage.id == router.currentRoute.value.params.id,
//                 }));
//                 data_manager_fetching.value = false;
//                 callback(result);
//             }
//         });
//     }
// }

// watch(formState.value.email, () => {
//     data_manager.value = [];
//     data_manager_fetching.value = false;
// });

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

//Get Data

// Reset formState when API data is passed
const resetFormState = (data) => {
    formState.value = {
        email: data.email || null,
        note: data.note || null,
        name: data.name || null,
        image: data.image || [],
        is_active: data.is_active || false,
        phone: data.phone || null,
        address: data.address || null,
        discount: data.discount || null,
    };
    tempImg.value = data.image || [];
};

const queryData = (params) => {
    return axios.get(`/api/customers/${router.currentRoute.value.params.id}`);
};

const { data: dataCustomer, loading, run } = usePagination(queryData);

watch(
    () => dataCustomer.value,
    (newData) => {
        if (newData.data?.item && !loading.value) {
            let data = {
                ...newData.data?.item,
                image: newData.data?.item.image
                    ? [
                          {
                              name: "image.png",
                              url: newData.data?.item.image,
                          },
                      ]
                    : [],
                is_active:
                    newData.data?.item?.is_active != null
                        ? newData.data?.item.is_active
                        : false,
            };
            resetFormState(data);
        } else {
            message.error("Không tìm thấy dữ liệu");
            router.push({ name: "category-index" });
        }
    }
);
const onlyNumbers = {
    beforeMount(el) {
        el.addEventListener("input", () => {
            el.value = el.value.replace(/\D/g, "");
        });
    },
};

onMounted(() => {
    // searchCategory("", (data) => (data_manager.value = data));
    const input = document.getElementById("phoneInput");
    onlyNumbers.beforeMount(input);
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
