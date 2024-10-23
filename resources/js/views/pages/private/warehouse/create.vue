<template>
    <svg xmlns="http://www.w3.org/2000/svg" class="hidden">
        <symbol viewBox="0 0 24 24" id="fe-ticket">
            <path
                fill="currentColor"
                fill-rule="evenodd"
                d="M2 10V8a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v2a2 2 0 1 0 0 4v2a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-2a2 2 0 1 0 0-4m3-2v8h14V8zm2 2h10v4H7z"
            ></path>
        </symbol>
        <symbol viewBox="0 0 24 24" id="ms-account-circle">
            <path
                fill="currentColor"
                d="M5.85 17.1q1.275-.975 2.85-1.537T12 15t3.3.563t2.85 1.537q.875-1.025 1.363-2.325T20 12q0-3.325-2.337-5.663T12 4T6.337 6.338T4 12q0 1.475.488 2.775T5.85 17.1M12 13q-1.475 0-2.488-1.012T8.5 9.5t1.013-2.488T12 6t2.488 1.013T15.5 9.5t-1.012 2.488T12 13m0 9q-2.075 0-3.9-.788t-3.175-2.137T2.788 15.9T2 12t.788-3.9t2.137-3.175T8.1 2.788T12 2t3.9.788t3.175 2.137T21.213 8.1T22 12t-.788 3.9t-2.137 3.175t-3.175 2.138T12 22"
            ></path>
        </symbol>
        <symbol viewBox="0 0 16 16" id="bi-boxes">
            <path
                fill="currentColor"
                d="M7.752.066a.5.5 0 0 1 .496 0l3.75 2.143a.5.5 0 0 1 .252.434v3.995l3.498 2A.5.5 0 0 1 16 9.07v4.286a.5.5 0 0 1-.252.434l-3.75 2.143a.5.5 0 0 1-.496 0l-3.502-2l-3.502 2.001a.5.5 0 0 1-.496 0l-3.75-2.143A.5.5 0 0 1 0 13.357V9.071a.5.5 0 0 1 .252-.434L3.75 6.638V2.643a.5.5 0 0 1 .252-.434zM4.25 7.504L1.508 9.071l2.742 1.567l2.742-1.567zM7.5 9.933l-2.75 1.571v3.134l2.75-1.571zm1 3.134l2.75 1.571v-3.134L8.5 9.933zm.508-3.996l2.742 1.567l2.742-1.567l-2.742-1.567zm2.242-2.433V3.504L8.5 5.076V8.21zM7.5 8.21V5.076L4.75 3.504v3.134zM5.258 2.643L8 4.21l2.742-1.567L8 1.076zM15 9.933l-2.75 1.571v3.134L15 13.067zM3.75 14.638v-3.134L1 9.933v3.134z"
            ></path>
        </symbol>
    </svg>
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
                    Phiếu tạo kho
                </div>
                <div class="p-6 warehouse-create-form gap-6">
                    <a-form
                        :model="formState"
                        layout="vertical"
                        @finishFailed="onFinishFailed"
                    >
                        <a-form-item v-bind="validateInfos.manager_id">
                            <template class="h-full" #label>
                                <span class="font-medium">Manager ID</span>
                            </template>
                            <a-select
                                v-model:value="formState.manager_id"
                                :options="data_manager"
                                :not-found-content="
                                    data_manager_fetching ? undefinded : null
                                "
                                placeholder="Chọn người quản lý"
                                @search="handleSearch"
                                @change="handleChange"
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
                        <a-form-item v-bind="validateInfos.storage_name">
                            <template class="h-full" #label>
                                <span class="font-medium">Tên kho</span>
                            </template>
                            <a-input
                                v-model:value="formState.storage_name"
                                placeholder=""
                            />
                        </a-form-item>
                        <a-form-item v-bind="validateInfos.storage_code">
                            <template class="h-full" #label>
                                <span class="font-medium">Code kho</span>
                            </template>
                            <a-input
                                v-model:value="formState.storage_code"
                                placeholder=""
                            />
                        </a-form-item>
                        <a-form-item >
                            <template class="h-full" #label>
                                <span class="font-medium">Mã số thuế</span>
                            </template>
                            <a-input
                                v-model:value="formState.tax_code"
                                placeholder=""
                            />
                        </a-form-item>
                        <a-form-item v-bind="validateInfos.email">
                            <template class="h-full" #label>
                                <span class="font-medium">Email</span>
                            </template>
                            <a-input
                                v-model:value="formState.email"
                                type="email"
                                placeholder=""
                            />
                        </a-form-item>
                        <a-form-item v-bind="validateInfos.phone">
                            <template class="h-full" #label>
                                <span class="font-medium">Số điện thoại</span>
                            </template>
                            <a-input id="phoneInput" v-model:value="formState.phone" v-only-numbers type="text" maxlength="10" :autocomplete="false" required></a-input>
                        </a-form-item>
                        <a-form-item v-bind="validateInfos.address">
                            <template class="h-full" #label>
                                <span class="font-medium">Địa chỉ</span>
                            </template>
                            <a-textarea
                                v-model:value="formState.address"
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
                                    @click="handleChangeMaterialPage"
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
let currentValue = '';

const routes = ref([
    {
        name: "home",
        breadcrumbName: "Trang chủ",
    },
    {
        name: "warehouse-index",
        breadcrumbName: "Quản lý kho",
    },
    {
        name: "warehouse-create",
        breadcrumbName: "Tạo kho",
    },
]);

const formState = ref({
    manager_id: null,
    storage_code: null,
    storage_name: null,
    phone: null,
    tax_code: null,
    email: null,
    address: null,
});
const errorInfo = ref([]);
const { resetFields, validate, validateInfos } = useForm(
    formState.value,
    reactive({
        manager_id: [
            {
                required: true,
                message: "Vui lòng chọn mã quản lý",
            },
        ],
        storage_code: [
            {
                required: true,
                message: "Vui lòng chọn mã kho",
            },
        ],
        storage_name: [
            {
                required: true,
                message: "Vui lòng nhập tên kho",
            },
        ],
        phone: [
            {
                required: true,
                message: "Vui lòng nhập Số điện thoại",
            },
        ],
        email: [
            {
                required: true,
                message: "Vui lòng nhập Email",
            },
        ],
        address: [
            {
                required: true,
                message: "Vui lòng nhập địa chỉ",
            },
        ],
    })
);

const onSubmit = async () => {
    validate()
        .then(async (res) => {
            const response = await axios.post("/api/warehouses", {
                name: formState.value.storage_name,
                code: formState.value.storage_code,
                email: formState.value.email,
                phone: formState.value.phone,
                address: formState.value.address,
                manager_id: formState.value.manager_id,
                tax_code: formState.value.tax_code
            });
            if (response.data.code == 200) {
                message.success(response.data.message);
                handleChangeMaterialPage();
                // refreshRole();
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

const handleChangeMaterialPage = () => {
    resetFields();
    router.push({ name: "warehouse-index" });
};

const onlyNumbers = {
  beforeMount(el) {
    el.addEventListener('input', () => {
      el.value = el.value.replace(/\D/g, '');
    });
  }
};

const reset = () => {
    resetFields();
};

const handleOk = () => {
    loading.value = true;
    setTimeout(() => {
        loading.value = false;
        openCallingBack.value = false;
    }, 1000);
};
const handleCancel = () => {
    openCallingBack.value = false;
};

function fetchStorageDropdown(value, callback) {
    if (timeout) {
        clearTimeout(timeout);
        timeout = null;
    }
    currentValue = value;
    timeout = setTimeout(searchStorage(value, callback), 300);
}

const handleSearch = async (val) => {
    fetchStorageDropdown(val, (data) => (data_manager.value = data));
};
const handleChange = (val, item) => {
    formState.storage_code = item;
    fetchStorageDropdown('', (data) => (data_manager.value = data));
};

async function searchStorage(value,callback) {
    data_manager_fetching.value = true;
    const params = new URLSearchParams({
        name: value,
    });
    if (value) {
        await axios.get(`/api/users?${params}`).then((response) => {
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
        await axios.get(`/api/users`).then((response) => {
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

watch(formState.storage_code, () => {
    data_manager.value = [];
    data_manager_fetching.value = false;
});

onMounted(() => {
    searchStorage('', (data) => (data_manager.value = data));
    const input = document.getElementById('phoneInput');
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
