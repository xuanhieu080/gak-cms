<template>
    <Page>
        <div class="product-category">
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
                    Quản lý khách hàng
                </div>
                <div class="table-container">
                    <a-table
                        :row-selection="rowSelection"
                        :columns="columns"
                        :pagination="pagination"
                        :loading="loading"
                        :row-key="(record) => record.id"
                        :data-source="dataSource?.data?.data"
                        @change="handleTableChange"
                        bordered
                        :scroll="{ x: 'max-content' }"
                    >
                        <template #bodyCell="{ column, text, index, record }">
                            <template v-if="column.dataIndex === 'image'">
                                <img
                                    v-if="text"
                                    :src="text"
                                    class="h-16 w-16 object-contain"
                                    alt=""
                                />
                            </template>
                            <template v-if="column.dataIndex === 'name'">
                                <span class="text-blue-700">{{ text }}</span>
                            </template>
                            <template v-if="column.dataIndex === 'is_active'">
                                <a-switch
                                    v-model:checked="record.is_active"
                                    :loading="loading"
                                    @change="getActiveProductCategory(record)"
                                />
                            </template>
                            <template v-if="column.dataIndex === 'action'">
                                <div class="flex items-center justify-center">
                                    <a-tooltip>
                                        <template #title>Chỉnh sửa</template>
                                        <EditOutlined
                                            @click="handleEditProduct(record)"
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
                                <div class="flex items-center gap-4">
                                    <div class="search-item">
                                        <a-input
                                            v-model:value="search_name"
                                            placeholder="Tìm kiếm"
                                            allow-clear
                                        >
                                            <template #prefix>
                                                <SearchOutlined />
                                            </template>
                                        </a-input>
                                    </div>
                                    <a-button
                                        type="primary"
                                        class="flex items-center"
                                        @click="handleSearchTable"
                                    >
                                        <template #icon>
                                            <SearchOutlined />
                                        </template>
                                        Tìm kiếm
                                    </a-button>
                                    <a-button
                                        class="flex items-center justify-center"
                                        v-if="is_search"
                                        danger
                                        @click="handleClearSearchTable"
                                    >
                                        <template #icon>
                                            <DeleteOutlined
                                                :style="{ color: red }"
                                            />
                                        </template>
                                    </a-button>
                                </div>
                                <div class="flex items-center flex-wrap gap-2">
                                    <a-button
                                        type="primary"
                                        class="flex items-center"
                                        @click="handleCreateCustomer"
                                    >
                                        <template #icon>
                                            <PlusOutlined />
                                        </template>
                                        Tạo khách hàng
                                    </a-button>
                                    <a-popconfirm
                                        :disabled="selectedRow.length == 0"
                                        title="Bạn chắc chắn xóa chứ?"
                                        ok-text="Đúng"
                                        cancel-text="Hủy bỏ"
                                        @confirm="handleDeleteCategory"
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
            </div>
        </div>
    </Page>
</template>

<script setup>
import {
    SearchOutlined,
    PlusOutlined,
    DeleteOutlined,
    SyncOutlined,
    EditOutlined,
    UserOutlined,
    PhoneTwoTone,
} from "@ant-design/icons-vue";
import { ref, computed, watch } from "vue";
import { usePagination } from "vue-request";
import { useRouter } from "vue-router";
import axios from "axios";
import Page from "@/views/layouts/Page";
import { message } from "ant-design-vue";

const openSearchTicket = ref([]);
const search_name = ref(null);
const selectedRow = ref([]);
const is_search = ref(false);
const routes = ref([
    {
        name: "home",
        breadcrumbName: "Trang chủ",
    },
    {
        name: "warehouse",
        breadcrumbName: "Quản lý nhóm sản phẩm",
    },
]);

const columns = [
    {
        title: "ID",
        dataIndex: "id",
        sorter: true,
    },
    {
        title: "image",
        dataIndex: "image",
        sorter: true,
    },
    {
        title: "Tên khách hàng",
        dataIndex: "name",
        sorter: true,
    },
    {
        title: "Email",
        dataIndex: "email",
        sorter: true,
    },
    {
        title: "Phone",
        dataIndex: "phone",
        sorter: true,
    },
    {
        title: "Địa chỉ",
        dataIndex: "address",
        sorter: true,
    },
    {
        title: "Giảm giá",
        dataIndex: "discount",
        sorter: true,
    },
    {
        title: "Ghi chú",
        dataIndex: "note",
        sorter: true,
        width: "150px",
    },
    {
        title: "Hoạt động",
        dataIndex: "is_active",
        sorter: true,
    },
    {
        title: "Hành động",
        dataIndex: "action",
        fixed: "right",
    },
];

const fakeData = ref([
    {
        id: 1,
        image: "https://app.gak.vn/storage/media/products/mau-dong-phuc-cong-nhan-kieu-phoi-so-2-1722417926uuKZT23p.jpg",
        name: "Nhóm A",
        description: "Hello",
        is_active: 1,
        children: [
            {
                id: 11,
                image: "",
                name: "",
                description: "Hello",
                is_active: 0,
            },
            {
                id: 12,
                image: "",
                name: "",
                description: "Hello",
                is_active: 0,
            },
            {
                id: 13,
                image: "",
                name: "",
                description: "Hello",
                is_active: 1,
            },
            {
                id: 14,
                image: "",
                name: "",
                description: "Hello",
                is_active: 1,
            },
        ],
    },
    {
        id: 3,
        image: "https://app.gak.vn/storage/media/products/mau-dong-phuc-cong-nhan-kieu-phoi-so-2-1722417926uuKZT23p.jpg",
        name: "Mẫu đồnh phục công nhân kiểu phối số 4",
        description: "Hello",
        is_active: 1,
    },
    {
        id: 4,
        image: "https://app.gak.vn/storage/media/products/mau-dong-phuc-cong-nhan-kieu-phoi-so-2-1722417926uuKZT23p.jpg",
        name: "Mẫu đồnh phục công nhân kiểu phối số 5",
        description: "Hello",
        is_active: 0,
        // Không có children
    },
]);

const options = ref([
    {
        value: "jack",
        label: "Jack",
    },
    {
        value: "lucy",
        label: "Lucy",
    },
    {
        value: "tom",
        label: "Tom",
    },
]);
const router = useRouter();
const handleCreateCustomer = () => {
    router.push({ name: "customer-create" });
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

const handleDeleteCategory = async () => {
    if (selectedRow.value.length > 0) {
        selectedRow.value.forEach(async (id) => {
            try {
                loading.value = true;
                const response = await axios.delete(`/api/customers/${id}`);
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

const queryData = (params) => {
    return axios.get("/api/customers", {
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
    },
});

const pagination = computed(() => ({
    total: dataSource.value?.data.meta.total,
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

const handleEditProduct = (record) => {
    router.push({ name: "customer-edit", params: { id: record.id } });
};

const getActiveProductCategory = async (record) => {
    try {
        loading.value = true;
        const response = await axios.post(`/api/customers/${record.id}`, {
            name: record.name,
            phone: record.phone,
            is_active: record.is_active,
        });
        if (response.data.status) {
            message.success(response.data.message);
            handleReloadData();
        }
    } catch (e) {
        record.is_active = !record.is_active;
        if (e.response.status == 422) {
            message.error(e.response.data.errors.is_active[0]);
        } else {
            message.error("Server bận. Vui lòng thử lại sau!");
        }
        loading.value = false;
    }
};

//handle search table
const handleSearchTable = () => {
    let params = {};
    if (search_name.value) {
        params.search = search_name.value;
    }
    run({
        limit: pagination.value.pageSize,
        page: 1,
        ...params,
    });
    is_search.value = true;
};
const handleClearSearchTable = () => {
    search_name.value = null;
    is_search.value = false;
    run({
        limit: pagination.value.pageSize,
        page: 1,
    });
};

watch(
    () => search_name.value,
    () => {
        if (
            (search_name.value === null || search_name.value === "") &&
            is_search.value
        ) {
            handleClearSearchTable();
        }
    }
);
</script>

<style lang="scss" scoped>
.product-category {
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
.product-category {
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

.ant-input-clear-icon {
    display: flex;
}
</style>
