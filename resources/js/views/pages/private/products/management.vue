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
                    Quản lý Sản phẩm
                </div>
                <div class="table-container">
                    <a-table
                        :row-selection="rowSelection"
                        :columns="columns"
                        :pagination="pagination"
                        :loading="loading"
                        :row-key="(record) => record.id"
                        :data-source="fakeData"
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
                                <div class="flex flex-col gap-1">
                                    <span class="text-blue-700">{{
                                        record.name
                                    }}</span>
                                    <div
                                        v-if="record.color && record.size"
                                        class="flex items-center gap-1 text-green-600"
                                    >
                                        {{
                                            "Color: " +
                                            record.color +
                                            ", Size: " +
                                            record.size
                                        }}
                                    </div>
                                    <span class="text-gray-500">{{
                                        record.product_code
                                    }}</span>
                                </div>
                            </template>
                            <template
                                v-if="column.dataIndex === 'discount_price'"
                            >
                                <span class="text-red-500">{{ text }}</span>
                            </template>
                            <template v-if="column.dataIndex === 'action'">
                                <div class="flex items-center justify-center">
                                    <a-tooltip>
                                        <template #title>Chỉnh sửa</template>
                                        <EditOutlined
                                            @click="
                                                handleEditProduct(
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
                                <div class="flex items-center gap-4">
                                    <div class="search-item">
                                        <a-input
                                            v-model:value="storage_id"
                                            placeholder="Tìm kiếm"
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
                                </div>
                                <div class="flex items-center flex-wrap gap-2">
                                    <a-button
                                        type="primary"
                                        class="flex items-center"
                                        @click="handleCreateProduct"
                                    >
                                        <template #icon>
                                            <PlusOutlined />
                                        </template>
                                        Tạo Sản Phẩm
                                    </a-button>
                                    <a-popconfirm
                                        :disabled="selectedRow.length == 0"
                                        title="Bạn chắc chắn xóa chứ?"
                                        ok-text="Đúng"
                                        cancel-text="Hủy bỏ"
                                        @confirm="handleDeleteWareHouse"
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
import { ref, computed } from "vue";
import { usePagination } from "vue-request";
import { useRouter } from "vue-router";
import axios from "axios";
import Page from "@/views/layouts/Page";
import { message } from "ant-design-vue";

const openSearchTicket = ref([]);
const storage_id = ref(null);
const storage_name = ref(null);
const storage_address = ref(null);
const selected_date_request = ref(null);
const selectedRow = ref([]);
const routes = ref([
    {
        name: "home",
        breadcrumbName: "Trang chủ",
    },
    {
        name: "warehouse",
        breadcrumbName: "Quản lý Sản phẩm",
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
    },
    {
        title: "Tên Sản phẩm",
        dataIndex: "name",
        sorter: true,
    },
    {
        title: "Số lượng tồn kho",
        dataIndex: "amount",
        sorter: true,
        width: "150px",
    },
    {
        title: "Giá",
        dataIndex: "price",
        sorter: true,
        width: "150px",
    },
    {
        title: "Giá sau giảm",
        dataIndex: "discount_price",
        sorter: true,
        width: "150px",
    },
    {
        title: "Ngày Tạo",
        dataIndex: "created_at",
        sorter: true,
        width: "150px",
    },
    {
        title: "Cập Nhật Lúc",
        dataIndex: "updated_at",
        sorter: true,
        width: "150px",
    },
    {
        title: "Người Tạo",
        dataIndex: "created_by_name",
        sorter: true,
    },
    {
        title: "Người Cập Nhật",
        dataIndex: "updated_by_name",
        sorter: true,
    },
    {
        title: "Hành động",
        dataIndex: "action",
        fixed: 'right',
    },
];

const fakeData = ref([
    {
        id: 1,
        image: "https://app.gak.vn/storage/media/products/mau-dong-phuc-cong-nhan-kieu-phoi-so-2-1722417926uuKZT23p.jpg",
        name: "Mẫu đồnh phục công nhân kiểu phối số 2",
        product_code: "SKU-081141",
        price: "288.000đ",
        discount_price: "199.000đ",
        color: "",
        size: "",
        amount: 10000,
        children: [
            {
                id: 11,
                image: "",
                name: "",
                product_code: "SKU-7749123",
                color: "Brown",
                size: "M",
                price: "288.000đ",
                discount_price: "199.000đ",
                amount: 200,
            },
            {
                id: 12,
                image: "",
                name: "",
                product_code: "SKU-7749124",
                color: "Brown",
                size: "L",
                price: "288.000đ",
                discount_price: "199.000đ",
                amount: 300,
            },
            {
                id: 13,
                image: "",
                name: "",
                product_code: "SKU-7749321",
                color: "Brown",
                size: "S",
                price: "288.000đ",
                discount_price: "199.000đ",
                amount: 1500,
            },
            {
                id: 14,
                image: "",
                name: "",
                product_code: "SKU-7749111",
                color: "Black",
                size: "S",
                price: "288.000đ",
                discount_price: "199.000đ",
                amount: 8000,
            },
        ],
    },
    {
        id: 3,
        image: "https://app.gak.vn/storage/media/products/mau-dong-phuc-cong-nhan-kieu-phoi-so-2-1722417926uuKZT23p.jpg",
        name: "Mẫu đồnh phục công nhân kiểu phối số 4",
        product_code: "SKU-081143",
        price: "350.000đ",
        discount_price: "250.000đ",
        amount: 8000,
        color: "White",
        size: "XL",
    },
    {
        id: 2,
        image: "https://app.gak.vn/storage/media/products/mau-dong-phuc-cong-nhan-kieu-phoi-so-2-1722417926uuKZT23p.jpg",
        name: "Mẫu đồnh phục công nhân kiểu phối số 3",
        product_code: "SKU-081142",
        price: "399.000đ",
        discount_price: "299.000đ",
        color: "",
        size: "",
        amount: 10000,
        children: [
            {
                product_code: "SKU-7749555",
                color: "Brown",
                size: "M",
                price: "399.000đ",
                discount_price: "299.000đ",
                amount: 200,
            },
            {
                product_code: "SKU-7749666",
                color: "Brown",
                size: "L",
                price: "399.000đ",
                discount_price: "299.000đ",
                amount: 300,
            },
            {
                product_code: "SKU-7749777",
                color: "Brown",
                size: "S",
                price: "399.000đ",
                discount_price: "299.000đ",
                amount: 5000,
            },
            {
                product_code: "SKU-7749888",
                color: "Black",
                size: "S",
                price: "399.000đ",
                discount_price: "299.000đ",
                amount: 9000,
            },
        ],
    },
    {
        id: 4,
        image: "https://app.gak.vn/storage/media/products/mau-dong-phuc-cong-nhan-kieu-phoi-so-2-1722417926uuKZT23p.jpg",
        name: "Mẫu đồnh phục công nhân kiểu phối số 5",
        product_code: "SKU-081144",
        price: "450.000đ",
        discount_price: "350.000đ",
        amount: 6000,
        color: "Black",
        size: "L",
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
const handleCreateProduct = () => {
    router.push({ name: "product-create" });
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

const handleDeleteWareHouse = async () => {
    if (selectedRow.value.length > 0) {
        selectedRow.value.forEach(async (id) => {
            try {
                loading.value = true;
                const response = await axios.delete(`/api/warehouses/${id}`);
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
    return axios.get("/api/warehouses", {
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
    router.push({ name: "product-edit", params: { id: record.id } });
}
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
