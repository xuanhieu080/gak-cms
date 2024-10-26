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
        <div class="main-pyc-create">
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
            <div class="pyc-form border border-blue-500 flex flex-col">
                <div
                    class="title-box bg-blue-500 text-white p-3 flex items-center gap-1"
                >
                    Phiếu tạo nguyên liệu
                </div>
                <div class="p-6 material-form grid grid-cols-2 gap-6">
                    <a-form :model="formState" layout="vertical">
                        <a-form-item v-bind="validateInfos.material_name">
                            <template class="h-full" #label>
                                <span class="font-medium">Tên nguyên liệu</span>
                            </template>
                            <a-input
                                v-model:value="formState.material_name"
                                placeholder=""
                            />
                        </a-form-item>
                        <a-form-item v-bind="validateInfos.material_code">
                            <template class="h-full" #label>
                                <span class="font-medium"
                                    >Code Nguyên Liệu</span
                                >
                            </template>
                            <a-input
                                v-model:value="formState.material_code"
                                placeholder=""
                            />
                        </a-form-item>
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
                            v-for="(warehouse, index) in formState.warehouses"
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
                                        v-model:value="warehouse.warehouse_id"
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
                                            handleSearchStorage('', warehouse)
                                        "
                                    >
                                        <template #notFoundContent>
                                            <a-spin
                                                v-if="warehouse.loading"
                                                size="small"
                                            />
                                            <span
                                                v-if="
                                                    warehouse.options.length ==
                                                        0 && !warehouse.loading
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
                                        v-if="formState.warehouses.length > 1"
                                        class="flex items-center gap-2"
                                    >
                                        <MinusOutlined /> Xóa
                                    </a-button>
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
                    </a-form>
                </div>
            </div>
        </div>
    </Page>
</template>

<script setup>
import { MinusOutlined, PlusOutlined } from "@ant-design/icons-vue";
import { ref, reactive, watch, onMounted } from "vue";
import axios from "axios";
import { Form, message } from "ant-design-vue";
import Page from "@/views/layouts/Page";
import { useRouter } from "vue-router";

const router = useRouter();
const errorInfo = ref([]);

const data_storages = ref([
    {
        value: "1",
        label: "Jack",
    },
    {
        value: "2",
        label: "Lucy",
    },
    {
        value: "3",
        label: "Tom",
    },
]);
const data_storages_fetching = ref(false);
let timeout;
let currentValue = "";

const statusOptions = ref([
    {
        value: "pending",
        label: "Chờ xử lý",
    },
    {
        value: "process",
        label: "Đang xử lý",
    },
    {
        value: "finish",
        label: "Đã xử lý",
    },
    {
        value: "following",
        label: "Đang theo dõi",
    },
]);
const radioStyle = reactive({
    display: "flex",
    height: "30px",
    lineHeight: "30px",
});
const useForm = Form.useForm;
const routes = ref([
    {
        name: "home",
        breadcrumbName: "Trang chủ",
    },
    {
        name: "management-materials",
        breadcrumbName: "Quản lý Nguyên Liệu",
    },
    {
        name: "management-materials-create",
        breadcrumbName: "Tạo Nguyên Liệu",
    },
]);

const formState = ref({
    material_name: null,
    material_code: null,
    warehouses: [
        {
            warehouse_id: null,
            quantity: null,
            loading: false,
            options: [],
        },
    ],
});

const { resetFields, validate, validateInfos } = useForm(
    formState.value,
    reactive({
        material_name: [
            {
                required: true,
                message: "Vui lòng nhập tên nguyên liệu",
            },
        ],
    })
);

const onSubmit = async () => {
    validate()
        .then(async (res) => {
            let param = {
                name: formState.value.material_name,
            };
            if (formState.value.material_code) {
                param.code = formState.value.material_code;
            }
            if (formState.value.warehouses.length > 0) {
                param.items = [];
                formState.value.warehouses.forEach((item, index) => {
                    param.items.push({
                        warehouse_id: item.warehouse_id.value,
                        qty: item.quantity,
                    });
                    // params[`items[${index}][warehouse_id]`] =
                    //     item.warehouse_id.value;
                    // params[`items[${index}][qty]`] = item.quantity;
                });
            }
            const response = await axios.post("/api/materials", param);
            if (response.data.code == 200) {
                message.success(response.data.message, 2, () => {
                    handleChangeMaterialPage();
                });
                message.loading('Quay lại trang quản lý sau 3 giây', 3);
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
    router.push({ name: "management-materials" });
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

//Add Kho
const addWarehouse = () => {
    formState.value.warehouses.push({
        warehouse_id: null,
        quantity: null,
        loading: true,
        options: [],
    });
    handleSearchStorage(
        "",
        formState.value.warehouses[formState.value.warehouses.length - 1],
        (data) =>
            (formState.value.warehouses[
                formState.value.warehouses.length - 1
            ].options = data)
    );
};

const removeWarehouse = (index) => {
    formState.value.warehouses.splice(index, 1);
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
    item.warehouse_id = {
        value: val,
        label: item.options.find((item) => item.value === val).label,
    };
    item.loading = false;
    fetchStorageDropdown("", item, (data) => (item.options = data));
};

async function searchStorage(value, item, callback) {
    item.loading = true;
    const params = new URLSearchParams({
        name: value,
    });

    // Lấy dữ liệu kho đã được thêm ở trước
    let excludeStorage = [];
    if (formState.value.warehouses.length > 0) {
        formState.value.warehouses.forEach((item, index) => {
            if (index < formState.value.warehouses.length - 1)
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

// watch(formState.storage_code, () => {
//     data_storages.value = [];
//     data_storages_fetching.value = false;
// });

// onMounted(() => {
//     searchStorage("", (data) => (data_storages.value = data));
// });
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
