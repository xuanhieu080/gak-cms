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
                        @finishFailed="onFinishFailed"
                    >
                        <a-form-item v-bind="validateInfos.name">
                            <template class="h-full" #label>
                                <span class="font-medium">Đơn vị tính</span>
                            </template>
                            <a-input
                                v-model:value="formState.name"
                                placeholder=""
                            />
                        </a-form-item>
                        <a-form-item v-bind="validateInfos.code">
                            <template class="h-full" #label>
                                <span class="font-medium">Code</span>
                            </template>
                            <a-input
                                v-model:value="formState.code"
                                placeholder=""
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
                                    @click="handleChangeCustomerPage"
                                >
                                    Hủy bỏ
                                </a-button>
                            </div>
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
        name: "management-units",
        breadcrumbName: "Quản lý đơn vị tính",
    },
    {
        name: "units-create",
        breadcrumbName: "Tạo đơn vị tính",
    },
]);

const formState = ref({
    name: null,
    code: null,
    is_active: false,
});
const errorInfo = ref([]);
const { resetFields, validate, validateInfos } = useForm(
    formState.value,
    reactive({
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
);

const onSubmit = async () => {
    validate()
        .then(async (res) => {
            let formData = new FormData();
            formData.append("name", formState.value.name);
            formData.append("code", formState.value.code);
            formData.append("is_active", formState.value.is_active);
            const response = await axios.post("/api/units", formData);
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
    router.push({ name: "management-units" });
};

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
