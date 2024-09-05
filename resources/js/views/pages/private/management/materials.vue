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
            <a-collapse
                v-model:activeKey="openSearchTicket"
                :bordered="false"
                ghost
            >
                <a-collapse-panel
                    key="1"
                    class="bg-blue-500 !text-white"
                    :show-arrow="false"
                >
                    <template #header>
                        <div class="text-white">Tìm kiếm</div>
                    </template>
                    <div class="grid grid-cols-6 gap-6 w-full">
                        <div class="search-item">
                            <div class="title">Mã kho</div>
                            <a-input v-model:value="warehouse_id" />
                        </div>
                        <div class="search-item">
                            <div class="title">Tên sản phẩm</div>
                            <a-input v-model:value="customer_name" />
                        </div>
                        <div class="search-item">
                            <div class="title">Địa chỉ</div>
                            <a-input v-model:value="customer_address" />
                        </div>
                        <div class="search-item">
                            <div class="title">Trạng thái</div>
                            <a-select
                                v-model:value="selected_status"
                                show-search
                                placeholder="Chọn"
                                :options="options"
                                :filter-option="filterOption"
                                @change="handleChange"
                                class="w-full"
                            ></a-select>
                        </div>
                        <div class="search-item col-span-2">
                            <div class="title">Ngày yêu cầu</div>
                            <a-range-picker
                                v-model:value="selected_date_request"
                            />
                        </div>
                    </div>
                    <div class="filter-buttons mt-6 flex items-center gap-4">
                        <a-button type="primary">Tìm kiếm</a-button>
                        <a-button @click="resetSearchBox">Làm mới</a-button>
                    </div>
                </a-collapse-panel>
            </a-collapse>
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
                        <a-select
                            v-model:value="selected_product"
                            show-search
                            placeholder="Chọn sản phẩm"
                            :options="options"
                            :filter-option="filterOption"
                            @change="handleChange"
                            class="w-[200px]"
                        ></a-select>
                        <a-button type="primary">Tìm kiếm</a-button>
                    </div>
                    <div
                        v-if="false"
                        class="statistic-ticket flex items-center gap-4 flex-wrap"
                    >
                        <div class="statistic-ticket-item">
                            <div class="title">Chờ xử lý:</div>
                            <div class="counting-box pending">57/57</div>
                        </div>
                        <div class="statistic-ticket-item">
                            <div class="title">Đang xử lý:</div>
                            <div class="counting-box progress">88/88</div>
                        </div>
                        <div class="statistic-ticket-item">
                            <div class="title">Đã xử lý:</div>
                            <div class="counting-box finish">5/559303</div>
                        </div>
                        <div class="statistic-ticket-item">
                            <div class="title">Đang theo dõi:</div>
                            <div class="counting-box following">234/234</div>
                        </div>
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
                            <template v-if="column.dataIndex != 'phone'">
                                <div class="text-blue-500">
                                    {{ column.title }}
                                </div>
                            </template>
                        </template>
                        <template #bodyCell="{ column, text }">
                            <template v-if="column.dataIndex === 'warehouse_id'">
                                <div class="text-blue-500">{{ text }}</div>
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
                                        @click="handleToggleSearchBox"
                                    >
                                        <template #icon>
                                            <SearchOutlined />
                                        </template>
                                        Tìm kiếm
                                    </a-button>
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
                                    <a-button
                                        type="primary"
                                        danger
                                        class="flex items-center"
                                    >
                                        <template #icon>
                                            <DeleteOutlined />
                                        </template>
                                        Xóa
                                    </a-button>
                                    <a-button
                                        type="primary"
                                        class="flex items-center"
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
    ReloadOutlined,
    UserOutlined,
    PhoneTwoTone,
} from "@ant-design/icons-vue";
import { ref, computed } from "vue";
import { usePagination } from "vue-request";
import { useRouter } from "vue-router";
import axios from "axios";
import Page from "@/views/layouts/Page";

const openSearchTicket = ref([]);
const keyword_search = ref(null);
const warehouse_id = ref(null);
const selected_shift_information = ref(null);
const customer_name = ref(null);
const customer_address = ref(null);
const type_of_device = ref(null);
const selected_engineer = ref(null);
const selected_produce = ref(null);
const selected_status = ref(null);
const selected_result = ref(null);
const selected_result_ticket = ref(null);
const selected_agency_code = ref(null);
const selected_date_request = ref(null);
const selected_storage = ref(null);
const selected_product = ref(null);
const selected_ticket_engineer = ref(null);

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
];


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

const queryData = (params) => {
    return axios.get('/api/materials', {
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
    onChange: (selectedRowKeys, selectedRows) => {},
    getCheckboxProps: (record) => ({
        // Column configuration not to be checked
        warehouse_id: record.warehouse_id,
    }),
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
