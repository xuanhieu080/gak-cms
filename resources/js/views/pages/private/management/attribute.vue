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
                            v-model:value="selected_attr_group"
                            :options="data_storages"
                            :not-found-content="
                                data_storages_fetching ? undefinded : null
                            "
                            placeholder="Chọn nhóm thuộc tính"
                            @search="handleSearchAttrGroup"
                            @change="handleChangeAttrGroup"
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
                        <div class="search-item">
                            <a-input
                                v-model:value="atrribute_group_name"
                                placeholder="Tên nguyên liệu"
                            />
                        </div>
                        <a-button
                            type="primary"
                            danger
                            class="flex items-center"
                            @click="handleClearSearchTable"
                            v-if="selected_attr_group || atrribute_group_name"
                        >
                            <template #icon>
                                <DeleteOutlined />
                            </template>
                            Xóa
                        </a-button>
                        <a-button type="primary" @click="handleSearchTable"
                            >Tìm kiếm</a-button
                        >
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
                            <template v-if="column.dataIndex === 'group'">
                                {{ text.name }}
                            </template>
                            <template v-if="column.dataIndex === 'action'">
                                <div class="flex items-center justify-center">
                                    <a-tooltip>
                                        <template #title>Chỉnh sửa</template>
                                        <EditOutlined
                                            @click="
                                                handleOpenAttributeGroupDetails(
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
                                    trong số <b>{{ pagination.total }}</b> mục.
                                </div>
                                <div class="flex items-center flex-wrap gap-2">
                                    <a-button
                                        type="primary"
                                        class="flex items-center"
                                        @click="handleCreateAttributeGroup"
                                    >
                                        <template #icon>
                                            <PlusOutlined />
                                        </template>
                                        Tạo thuộc tính
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
                    v-if="openAttributeGroupDetails"
                    v-model:open="openAttributeGroupDetails"
                    title="Thông Tin thuộc tính"
                    :footer="null"
                >
                    <div class="py-4">
                        <a-form
                            v-if="formState"
                            :model="formState"
                            layout="vertical"
                            class="h-full"
                        >
                            <a-form-item
                                v-bind="validateInfos.attribute_group_id"
                            >
                                <template class="h-full" #label>
                                    <span class="font-medium"
                                        >Nhóm thuộc tính</span
                                    >
                                </template>
                                <a-select
                                    v-model:value="formState.attribute_group_id"
                                    :options="data_storages"
                                    :not-found-content="
                                        data_storages_fetching
                                            ? undefinded
                                            : null
                                    "
                                    placeholder="Chọn nhóm thuộc tính"
                                    @search="handleSearchAttrGroup"
                                    @change="handleChangeAttrGroup"
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
                            <a-form-item
                                v-bind="validateInfos.atrribute_group_name"
                            >
                                <template class="h-full" #label>
                                    <span class="font-medium"
                                        >Tên thuộc tính</span
                                    >
                                </template>
                                <a-input
                                    v-model:value="
                                        formState.atrribute_group_name
                                    "
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
                                        @click="handleCancelEditAttrGroup"
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
const atrribute_group_name = ref(null);
const selected_attr_group = ref(null);
const openAttributeGroupDetails = ref(false);
const attributeGroupDetails = ref(null);

const routes = ref([
    {
        name: "home",
        breadcrumbName: "Trang chủ",
    },
    {
        name: "management-materials",
        breadcrumbName: "Quản lý thuộc tính",
    },
]);

const columns = [
    {
        title: "Mã thuộc tính",
        dataIndex: "id",
        sorter: true,
    },
    {
        title: "Thuộc tính",
        dataIndex: "name",
        sorter: true,
    },
    {
        title: "Mã nhóm thuộc tính",
        dataIndex: "group_id",
        sorter: true,
    },
    {
        title: "Tên nhóm thuộc tính",
        dataIndex: "group",
        sorter: true,
    },
    {
        title: "Tạo bởi",
        dataIndex: "created_by_name",
        sorter: true,
    },
    {
        title: "Cập nhật bởi",
        dataIndex: "updated_by_name",
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

const handleOpenAttributeGroupDetails = (record) => {
    attributeGroupDetails.value = record;
    formState.value = {
        atrribute_group_name: record.name,
        attribute_group_id: record.group_id,
    };
    openAttributeGroupDetails.value = true;
};
const handleCreateAttributeGroup = () => {
    router.push({ name: "management-attribute-create" });
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
        attribute_group_id: [
            {
                required: true,
                message: "Vui lòng chọn mã nhóm thuộc tính",
            },
        ],
        atrribute_group_name: [
            {
                required: true,
                message: "Vui lòng nhập tên thuộc tính",
            },
        ],
    })
);

const onSubmit = async () => {
    validate()
        .then(async (res) => {
            const response = await axios.post(
                `/api/attributes/${attributeGroupDetails.value.id}`,
                {
                    name: formState.value.atrribute_group_name,
                    group_id: formState.value.attribute_group_id,
                }
            );
            if (response.data.code == 200) {
                message.success(response.data.message);
                handleReloadData();
                handleCancelEditAttrGroup();
            }
        })
        .catch((err) => {
            console.log(err.response);
            if (err.response.status == 422) {
                errorInfo.value = Object.values(err.response.data.errors);
                message.error("Vui lòng kiểm tra lại thông tin");
            } else {
                console.log("error", err);
            }
        });
};
const handleCancelEditAttrGroup = () => {
    formState.value = null;
    openAttributeGroupDetails.value = false;
    errorInfo.value = [];
    resetFields();
};

const queryData = (params) => {
    return axios.get("/api/attributes", {
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
    total: dataSource.value?.data?.meta.total,
    current: current.value,
    pageSize: pageSize.value,
}));

const handleReloadData = () => {
    run({
        limit: 10,
        page: 1,
    });
};

const handleSearchTable = () => {
    let params = {};
    if (selected_attr_group.value) {
        params.group_id = selected_attr_group.value;
    }
    if (atrribute_group_name.value) {
        params.name = atrribute_group_name.value;
    }
    run({
        limit: pagination.pageSize,
        page: 1,
        ...params,
    });
};
const handleClearSearchTable = () => {
    selected_attr_group.value = null;
    atrribute_group_name.value = null;
    handleSearchTable();
}

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
                const response = await axios.delete(`/api/attributes/${id}`);
                handleReloadData();
                message.success(response.data.message);
            } catch (e) {
                loading.value = false;
                message.error("Vui lòng thử lại sau");
                console.log("err: ", e);
            }
        });
    }
};

function fetchAttrGroup(value, callback) {
    if (timeout) {
        clearTimeout(timeout);
        timeout = null;
    }
    currentValue = value;
    timeout = setTimeout(searchAttrGroup(value, callback), 300);
}

const handleSearchAttrGroup = async (val) => {
    fetchAttrGroup(val, (data) => (data_storages.value = data));
};
const handleChangeAttrGroup = (val, item) => {
    formState.attribute_group_id = item;
    fetchAttrGroup("", (data) => (data_storages.value = data));
};

async function searchAttrGroup(value, callback) {
    data_storages_fetching.value = true;
    const params = new URLSearchParams({
        name: value,
    });
    if (value) {
        await axios.get(`/api/attribute-groups?${params}`).then((response) => {
            if (currentValue === value) {
                const result = response.data.data?.map((attr_group) => ({
                    label: attr_group.name + "(" + attr_group.id + ")",
                    value: attr_group.id,
                    data: attr_group,
                }));
                data_storages_fetching.value = false;
                callback(result);
            }
        });
    } else {
        await axios.get(`/api/attribute-groups`).then((response) => {
            if (currentValue === value) {
                const result = response.data.data?.map((attr_group) => ({
                    label: attr_group.name + "(" + attr_group.id + ")",
                    value: attr_group.id,
                    data: attr_group,
                }));
                data_storages_fetching.value = false;
                callback(result);
            }
        });
    }
}

watch(formState.attribute_group_id, () => {
    data_storages.value = [];
    data_storages_fetching.value = false;
});

onMounted(() => {
    searchAttrGroup("", (data) => (data_storages.value = data));
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
