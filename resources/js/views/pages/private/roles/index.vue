<template>
    <Page class="py-0">
        <div class="permision-page">
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
            <h2 class="text-3xl my-4">Quản Lý Vai Trò</h2>
            <a-card class="w-full max-w-[500px]">
                <a-form
                    layout="vertical"
                    :model="formState"
                    autocomplete="off"
                    @finish="onFinish"
                    @finishFailed="onFinishFailed"
                >
                    <a-form-item
                        label="Tên Vai trò"
                        name="role_name"
                        :rules="[
                            {
                                required: true,
                                message: 'Vui lòng nhập tên vai trò!',
                            },
                        ]"
                    >
                        <a-input v-model:value="formState.role_name" />
                    </a-form-item>

                    <a-form-item>
                        <a-button
                            :disabled="disabled"
                            type="primary"
                            html-type="submit"
                            @click="handleAddRole"
                            class="submit-button"
                        >
                            Thêm mới
                        </a-button>
                    </a-form-item>
                </a-form>
            </a-card>
            <a-card class="mt-4">
                <a-table
                    :columns="columns"
                    :pagination="pagination"
                    :loading="loading"
                    :row-key="(record) => record.id"
                    :data-source="dataSource?.data?.data"
                    :expand-column-width="100"
                    bordered
                    :scroll="{ x: 'max-content' }"
                >
                    <template #headerCell="{ column }">
                        <div class="text-blue-500">
                            {{ column.title }}
                        </div>
                    </template>
                    <template #bodyCell="{ column, text, record }">
                        <template v-if="column.dataIndex === 'action'">
                            <div
                                class="flex w-full items-center justify-center gap-2"
                            >
                                <button>
                                    <AppstoreOutlined
                                        :style="{
                                            color: 'blue',
                                            fontSize: '18px',
                                        }"
                                        @click="handleRoleDetails(record.id)"
                                    />
                                </button>
                            </div>
                        </template>
                    </template>
                </a-table>
            </a-card>
        </div>
    </Page>
</template>

<script setup>
import Page from "@/views/layouts/Page";
import { AppstoreOutlined } from "@ant-design/icons-vue";
import { reactive, computed, ref } from "vue";
import { usePagination } from "vue-request";
import { useRouter } from "vue-router";
import axios from "axios";
import { message } from "ant-design-vue";
const router = useRouter();
const formState = reactive({
    role_name: "",
});
const disabled = computed(() => {
    return !formState.role_name;
});

const routes = ref([
    {
        name: "home",
        breadcrumbName: "Trang chủ",
    },
    {
        name: "setting-roles",
        breadcrumbName: "Quản lý vai trò",
    },
]);

const columns = [
    {
        title: "ID",
        dataIndex: "id",
        key: "id",
    },
    {
        title: "TÊN VAI TRÒ",
        dataIndex: "name",
        key: "name",
    },
    {
        title: "NGÀY TẠO",
        dataIndex: "created_at",
        key: "created_at",
    },
    {
        title: "PHÂN QUYỀN",
        dataIndex: "action",
        align: "center",
        key: "action",
    },
];

const handleAddRole = async () => {
    try {
        let response = await axios.post("/api/roles", {
            name: formState.role_name,
        });
        if (response.data.code == 200) {
            message.success(response.data.message);
            formState.role_name = null;
            run();
        }
    } catch (error) {
        console.log(error);
    }
};
const queryData = (params) => {
    return axios.get("/api/roles", {
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
    total: 100,
    current: current.value,
    pageSize: pageSize.value,
}));
const handleRoleDetails = (id) => {
    router.push({ name: "roles-create", params: { id: id } });
};
</script>

<style lang="scss" scoped></style>
