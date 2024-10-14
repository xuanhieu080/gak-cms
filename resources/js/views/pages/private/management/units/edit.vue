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
                    Tạo đơn vị tính
                </div>
                <div class="p-6 warehouse-create-form gap-6">
                    <a-form
                        :model="formState"
                        layout="vertical"
                        :rules="validateInfos"
                    >
                        <a-form-item name="name" :autoLink="false">
                            <template class="h-full" #label>
                                <span class="font-medium">Đơn vị tính</span>
                            </template>
                            <a-input
                                v-model:value="formState.name"
                                placeholder=""
                            />
                        </a-form-item>
                        <a-form-item name="code" :autoLink="false">
                            <template class="h-full" #label>
                                <span class="font-medium">Code</span>
                            </template>
                            <a-input
                                v-model:value="formState.code"
                                placeholder=""
                            />
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
                                    Cập Nhật
                                </a-button>
                                <a-button
                                    type="primary"
                                    danger
                                    block
                                    @click="handleChangeUnitsPage"
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
        breadcrumbName: "Quản lý khách hàng",
    },
    {
        name: "category-create",
        breadcrumbName: "Tạo khách hàng",
    },
]);

const formState = ref({
    name: null,
    image: null,
    code: null,
    is_active: false,
});
const resetFormState = (data) => {
    formState.value = {
        name: data.name || null,
        code: data.code || null,
        is_active: data.is_active || false,
    };
};
const errorInfo = ref([]);
const validateInfos = ref({
    code: [
            {
                required: true,
                message: "Vui lòng nhập code đơn vị tính",
            },
        ],
        name: [
            {
                required: true,
                message: "Vui lòng nhập tên đơn vị tính",
            },
        ],
        is_active: [
            {
                required: false,
            },
        ],
})
const { resetFields, validate } = useForm(formState.value);

const onSubmit = async () => {
    validate()
        .then(async (res) => {
            let formData = new FormData();
            formData.append("name", formState.value.name);
            formData.append("code", formState.value.code);
            formData.append("is_active", formState.value.is_active);
            const response = await axios.post(`/api/units/${router.currentRoute.value.params.id}`, {
                name: formState.value.name,
                code: formState.value.code,
                is_active: formState.value.is_active,
            });
            if (response.data.code == 200) {
                message.success(response.data.message);
                handleChangeUnitsPage();
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

const handleChangeUnitsPage = () => {
    resetFields();
    router.push({ name: "management-units" });
};

const queryData = (params) => {
    return axios.get(`/api/units/${router.currentRoute.value.params.id}`);
};

const { data: dataUnit, loading, run } = usePagination(queryData);

watch(
    () => dataUnit.value,
    (newData) => {
        if (newData.data?.item && !loading.value) {
            let data = {
                ...newData.data?.item,
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
