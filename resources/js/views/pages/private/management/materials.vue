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

            <div class="pyc-table border border-blue-500 flex flex-col">
                <div class="title-box bg-blue-500 text-white p-4">
                    Quản lý nguyên liệu
                </div>
                <div
                    class="flex items-center gap-4 justify-between w-full p-4 flex-wrap"
                >
                    <div
                        class="filter-box flex items-center lg:justify-between gap-4 flex-wrap"
                    >
                        <a-select
                            v-model:value="selected_storage"
                            show-search
                            allowClear="true"
                            placeholder="Chọn kho"
                            :options="options"
                            :filter-option="filterOption"
                            @change="handleChange"
                            class="w-[150px]"
                        ></a-select>
                        <div class="search-item">
                            <a-input
                                v-model:value="material_name"
                                placeholder="Tên nguyên liệu"
                            />
                        </div>
                        <a-button type="primary">Tìm kiếm</a-button>
                    </div>
                </div>
                <div class="table-container p-3">
                    <a-table
                        :row-selection="rowSelection"
                        :columns="columns"
                        :pagination="pagination"
                        :loading="loading"
                        :row-key="(record) => record?.id"
                        :data-source="dataSource?.data.data"
                        @change="handleTableChange"
                        bordered
                        :scroll="{ x: 'max-content' }"
                    >
                        <template #headerCell="{ column }">
                            <template v-if="column.dataIndex === 'action'">
                                <div
                                    class="flex items-center justify-center text-blue-500"
                                >
                                    {{ column.title }}
                                </div>
                            </template>
                            <div v-else class="text-blue-500">
                                {{ column.title }}
                            </div>
                        </template>
                        <template #bodyCell="{ column, text, record }">
                            <template
                                v-if="column.dataIndex === 'warehouse_id'"
                            >
                                <div class="text-blue-500 cursor-pointer">
                                    {{ text }}
                                </div>
                            </template>
                            <template v-if="column.dataIndex === 'action'">
                                <div class="flex items-center justify-center">
                                    <a-tooltip>
                                        <template #title>Chỉnh sửa</template>
                                        <EditOutlined
                                            @click="
                                                handleOpenMaterialDetails(
                                                    record
                                                )
                                            "
                                            :style="{ color: '#3b82f6' }"
                                        />
                                    </a-tooltip>
                                </div>
                            </template>
                        </template>
                        <template #title>
                            <div
                                class="flex items-center gap-4 justify-between w-full"
                            >
                                <div>
                                    Trình bày
                                    <b>{{
                                        (current > 1
                                            ? pageSize * (current - 1) + 1
                                            : 1) +
                                        "-" +
                                        pageSize * current
                                    }}</b>
                                    trong số <b>{{ totalPage }}</b> mục.
                                </div>
                                <div class="flex items-center flex-wrap gap-2">
                                    <a-button
                                        type="primary"
                                        class="flex items-center"
                                        @click="handleCreateMaterial"
                                    >
                                        <template #icon>
                                            <PlusOutlined />
                                        </template>
                                        Tạo nguyên liệu
                                    </a-button>
                                    <a-popconfirm
                                        :disabled="selectedRow.length == 0"
                                        title="Bạn chắc chắn xóa chứ?"
                                        ok-text="Đúng"
                                        cancel-text="Hủy bỏ"
                                        @confirm="handleDeleteMaterials"
                                        @cancel="cancel"
                                    >
                                        <a-button
                                            type="primary"
                                            danger
                                            class="flex items-center"
                                            :disabled="selectedRow.length == 0"
                                        >
                                            <template #icon>
                                                <DeleteOutlined />
                                            </template>
                                            Xóa
                                        </a-button>
                                    </a-popconfirm>

                                    <a-button
                                        type="primary"
                                        class="flex items-center"
                                        @click="handleReloadData"
                                    >
                                        <template #icon>
                                            <SyncOutlined />
                                        </template>
                                        Tạo lại
                                    </a-button>
                                </div>
                            </div>
                        </template>
                    </a-table>
                </div>
                <a-modal
                    v-if="openMaterialDetails"
                    v-model:open="openMaterialDetails"
                    title="Thông Tin nguyên liệu"
                    :footer="null"
                >
                    <div class="py-4">
                        <a-form
                            v-if="formState"
                            :model="formState"
                            layout="vertical"
                            class="h-full"
                        >
                            <a-form-item v-bind="validateInfos.storage_code">
                                <template class="h-full" #label>
                                    <span class="font-medium">Mã kho hàng</span>
                                </template>
                                <a-select
                                    v-model:value="formState.storage_code"
                                    :options="data_storages"
                                    :not-found-content="
                                        data_storages_fetching
                                            ? undefinded
                                            : null
                                    "
                                    placeholder="Chọn kho hàng"
                                    @search="handleSearch"
                                    @change="handleChange"
                                    :filter-option="false"
                                    show-search
                                >
                                    <template #notFoundContent>
                                        <a-spin
                                            v-if="data_storages_fetching"
                                            size="small"
                                        />
                                        <span
                                            v-if="
                                                data_storages.length == 0 &&
                                                !data_storages_fetching
                                            "
                                            >Không có kết quả nào</span
                                        >
                                    </template>
                                </a-select>
                            </a-form-item>
                            <a-form-item v-bind="validateInfos.material_name">
                                <template class="h-full" #label>
                                    <span class="font-medium"
                                        >Tên nguyên liệu</span
                                    >
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
                            <a-form-item no-style>
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
                                        key="back"
                                        type="primary"
                                        danger
                                        block
                                        @click="handleCancelEditMaterials"
                                    >
                                        Hủy bỏ
                                    </a-button>
                                </div>
                            </a-form-item>
                        </a-form>
                    </div>
                </a-modal>
            </div>
        </div>
    </Page>
</template>

<script setup>
import {
    PlusOutlined,
    DeleteOutlined,
    SyncOutlined,
    EditOutlined,
} from "@ant-design/icons-vue";
import { ref, computed, reactive, watch, onMounted } from "vue";
import { usePagination } from "vue-request";
import { useRouter } from "vue-router";
import axios from "axios";
import Page from "@/views/layouts/Page";
import { Form, message } from "ant-design-vue";

const openSearchTicket = ref([]);
const material_name = ref(null);
const selected_storage = ref(null);
const openMaterialDetails = ref(false);
const materialDetails = ref(null);

const routes = ref([
    {
        name: "home",
        breadcrumbName: "Trang chủ",
    },
    {
        name: "management-materials",
        breadcrumbName: "Quản lý Nguyên Liệu",
    },
]);

const columns = [
    {
        title: "Mã kho",
        dataIndex: "warehouse_id",
        sorter: true,
    },
    {
        title: "Tên nguyên liệu",
        dataIndex: "name",
        sorter: true,
    },
    {
        title: "Mã nguyên liệu",
        dataIndex: "code",
        sorter: true,
    },
    {
        title: "Hành động",
        dataIndex: "action",
    },
];

const selectedRow = ref([]);

const errorInfo = ref([]);
const data_storages = ref([]);
const data_storages_fetching = ref(false);
let timeout;
let currentValue = "";
const formState = ref(null);

const router = useRouter();

const handleOpenMaterialDetails = (record) => {
    materialDetails.value = record;
    formState.value = {
        storage_code: record.warehouse?.id ?? "",
        material_name: record.name,
        material_code: record.code,
    };
    openMaterialDetails.value = true;
};
const handleCreateMaterial = () => {
    router.push({ name: "management-materials-create" });
};

const handleToggleSearchBox = () => {
    if (openSearchTicket.value.length > 0) {
        openSearchTicket.value = [];
    } else {
        openSearchTicket.value[0] = 1;
    }
};

const filterOption = (input, option) => {
    return option.value.toLowerCase().indexOf(input.toLowerCase()) >= 0;
};

const handleChange = (value) => {
    console.log(`selected ${value}`);
};
const useForm = Form.useForm;

const { resetFields, validate, validateInfos } = useForm(
    formState.value,
    reactive({
        storage_code: [
            {
                required: true,
                message: "Vui lòng chọn mã đại lý",
            },
        ],
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
            const response = await axios.post(
                `/api/materials/${materialDetails.value.id}`,
                {
                    name: formState.value.material_name,
                    code: formState.value.material_code,
                    warehouse_id: formState.value.storage_code,
                }
            );
            if (response.data.code == 200) {
                message.success(response.data.message);
                handleCancelEditMaterials();
                handleReloadData();
            }
        })
        .catch((err) => {
            if (err.response.status == 422) {
                errorInfo.value = Object.values(err.response.data.errors);
                message.error("Vui lòng kiểm tra lại thông tin");
            } else {
                console.log("error", err);
            }
        });
};
const handleCancelEditMaterials = () => {
    formState.value = null;
    openMaterialDetails.value = false;
};

const queryData = (params) => {
    return axios.get("/api/materials", {
        params,
    });
};

const {
    data: dataSource,
    run,
    loading,
    current,
    totalPage,
    pageSize,
} = usePagination(queryData, {
    defaultParams: [
        {
            limit: 10,
        },
    ],
    pagination: {
        currentKey: "page",
        pageSizeKey: "limit",
        totalKey: "dataSource.total",
    },
});

const pagination = computed(() => ({
    total: totalPage.value,
    current: current.value,
    pageSize: pageSize.value,
}));

const handleReloadData = () => {
    run({
        limit: 10,
        page: 1,
    });
};

const handleTableChange = (pag, filters, sorter) => {
    run({
        limit: pag.pageSize,
        page: pag?.current,
        sort_by: sorter.field,
        order_by: sorter.order,
        ...filters,
    });
};

const rowSelection = {
    onChange: (selectedRowKeys, selectedRows) => {
        selectedRow.value = selectedRowKeys;
    },
    getCheckboxProps: (record) => ({
        // Column configuration not to be checked
        id: record.id,
    }),
};

const handleDeleteMaterials = async () => {
    if (selectedRow.value.length > 0) {
        selectedRow.value.forEach(async (id) => {
            try {
                loading.value = true;
                const response = await axios.delete(`/api/materials/${id}`);
                message.success(response.data.message);
                handleReloadData();
            } catch (e) {
                loading.value = false;
                message.error("Vui lòng thử lại sau");
                console.log("err: ", e);
            }
        });
    }
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
    fetchStorageDropdown(val, (data) => (data_storages.value = data));
};
const handleChangeStorage = (val, item) => {
    formState.value.storage_code = item;
    fetchStorageDropdown("", (data) => (data_storages.value = data));
};

async function searchStorage(value, callback) {
    data_storages_fetching.value = true;
    const params = new URLSearchParams({
        name: value,
    });
    if (value) {
        await axios.get(`/api/warehouses?${params}`).then((response) => {
            if (currentValue === value) {
                const result = response.data.data?.map((storage) => ({
                    label: storage.name + " (" + storage.id + ")",
                    value: storage.id,
                    data: storage,
                }));
                data_storages_fetching.value = false;
                callback(result);
            }
        });
    } else {
        await axios.get(`/api/warehouses`).then((response) => {
            if (currentValue === value) {
                const result = response.data.data?.map((storage) => ({
                    label: storage.name + " (" + storage.id + ")",
                    value: storage.id,
                    data: storage,
                }));
                data_storages_fetching.value = false;
                callback(result);
            }
        });
    }
}

watch(formState.value?.storage_code, () => {
    data_storages.value = [];
    data_storages_fetching.value = false;
});

onMounted(() => {
    searchStorage("", (data) => (data_storages.value = data));
});
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
