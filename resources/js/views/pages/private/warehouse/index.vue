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
                            <a-input v-model:value="storage_id" />
                        </div>
                        <div class="search-item">
                            <div class="title">Tên kho</div>
                            <a-input v-model:value="storage_name" />
                        </div>
                        <div class="search-item">
                            <div class="title">Địa chỉ</div>
                            <a-input v-model:value="storage_address" />
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
                    Quản lý kho hàng
                </div>
                <div class="table-container px-3">
                    <a-table
                        :row-selection="rowSelection"
                        :columns="columns"
                        :pagination="pagination"
                        :loading="loading"
                        :row-key="(record) => record.manager_id"
                        :data-source="dataSource?.data?.data"
                        @change="handleTableChange"
                        bordered
                        :scroll="{ x: 'max-content' }"
                    >
                        <template #bodyCell="{ column, text }">
                            <template v-if="column.dataIndex === 'manager_id'">
                                <router-link :to="{name: 'warehouse-details', params: {id: text}}" class="text-blue-500">{{ text }}</router-link>
                            </template>
                            <template
                                v-if="column.dataIndex === 'customer_request'"
                            >
                                <div
                                    :class="
                                        !text ? 'text-red-500 italic fs-12' : ''
                                    "
                                >
                                    {{ text ? text : "(Không có)" }}
                                </div>
                            </template>
                            <template
                                v-if="column.dataIndex === 'manager'"
                            >
                                {{ text ? text.name : '(Không có)' }}
                            </template>
                            <template
                                v-if="column.dataIndex === 'materials'"
                            >
                                <div class="inline" v-for="(item, index) in text">
                                    {{ item.name + (index < text.length - 1 ? ', ' : '')}}
                                </div>
                            </template>
                            <template
                                v-if="
                                    column.dataIndex === 'address' ||
                                    column.dataIndex === 'phone'
                                "
                            >
                                <div class="underline flex items-center gap-2">
                                    {{ text }}
                                    <PhoneTwoTone
                                        class="filter-white"
                                        v-if="column.dataIndex === 'phone'"
                                    />
                                </div>
                            </template>
                        </template>
                        <template #title="{}">
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
                                    trong số <b>{{ "100" }}</b> mục.
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
                                        @click="handleCreateStorage"
                                    >
                                        <template #icon>
                                            <PlusOutlined />
                                        </template>
                                        Tạo kho
                                    </a-button>
                                    <a-button
                                        type="primary"
                                        danger
                                        class="flex items-center"
                                        @click="handleDeleteWareHouse"
                                    >
                                        <template #icon>
                                            <DeleteOutlined />
                                        </template>
                                        Xóa
                                    </a-button>
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
    ReloadOutlined,
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
const  selectedRow = ref([]);
const routes = ref([
    {
        name: "home",
        breadcrumbName: "Trang chủ",
    },
    {
        name: "warehouse",
        breadcrumbName: "Quản lý kho hàng",
    },
]);

const columns = [
    {
        title: "ID",
        dataIndex: "manager_id",
        sorter: true,
        width: "70px",
    },
    {
        title: "Tên kho",
        dataIndex: "name",
        sorter: true,
        width: "150px",
    },
    {
        title: "Mã Kho",
        dataIndex: "code",
        sorter: true,
    },
    {
        title: "Địa chỉ",
        dataIndex: "address",
        sorter: true,
        width: "150px",
    },
    {
        title: "Số điện thoại",
        dataIndex: "phone",
        sorter: true,
        width: "150px",
    },
    {
        title: "Email",
        dataIndex: "email",
        sorter: true,
        width: "150px",
    },
    {
        title: "Người quản lý",
        dataIndex: "manager",
        sorter: true,
        width: "150px",
    },
    {
        title: "Nguyên Liệu",
        dataIndex: "materials",
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
const handleCreateStorage = () => {
    router.push({ name: "warehouse-create" });
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
    console.log(selectedRow.value);
    if(selectedRow.value.length > 0) {
        selectedRow.value.forEach(async (id) => {
            try {
                loading.value = true;
                const response = await axios.delete(`/api/warehouses/${id}`);
                message.success(response.data.message);
                handleReloadData();
            } catch (e) {
                loading.value = false;
                message.error("Vui lòng thử lại sau");
                console.log('err: ', e)
            }
        })
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
        manager_id: record.manager_id,
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
