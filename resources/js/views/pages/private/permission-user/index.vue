<template>
    <Page>
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
            <h2 class="text-3xl my-4">Danh Sách Users</h2>
            <a-card class="mt-4">
                <a-table
                    :columns="columns"
                    :loading="loading"
                    :pagination="pagination"
                    :row-key="(record) => record.id"
                    :data-source="dataSource?.data?.data"
                    bordered
                    @change="handleTableChange"
                    :scroll="{ x: 'max-content' }"
                >
                    <template #bodyCell="{ column, text, record }">
                        <template v-if="column.dataIndex === 'action'">
                            <div
                                class="flex w-full items-center justify-center gap-3"
                            >
                                <button
                                    @click="handleOpenTicketDetails(record)"
                                    class="flex items-center"
                                >
                                    <EyeOutlined
                                        :style="{ color: '#3b82f6' }"
                                    />
                                </button>
                                <a-popconfirm
                                    title="Vui lòng xác nhận để xóa!"
                                    @confirm="confirmDelete(record.id)"
                                    @cancel="cancel"
                                >
                                    <template #icon
                                        ><DeleteOutlined style="color: red"
                                    /></template>
                                    <DeleteOutlined :style="{ color: 'red' }" />
                                </a-popconfirm>
                            </div>
                        </template>
                        <div v-else class="data-column">
                            <span
                                :class="
                                    !text ? 'text-red-400 italic text-xs' : ''
                                "
                                >{{ text ? text : "(không có)" }}</span
                            >
                        </div>
                    </template>
                    <template #title>
                        <div
                            class="flex items-start gap-4 justify-between w-full"
                        >
                            <div class="space-y-2">
                                <div class="title">Tìm kiếm</div>
                                <a-input
                                    v-model:value="keyword_search"
                                    @input="handleSearchTable"
                                />
                            </div>
                            <div class="flex items-center flex-wrap gap-2">
                                <a-button
                                    type="primary"
                                    class="flex items-center"
                                    @click="handleCreateUser"
                                >
                                    <template #icon>
                                        <UserOutlined />
                                    </template>
                                    Thêm User
                                </a-button>
                                <a-button
                                    type="primary"
                                    class="flex items-center"
                                    @click="handleCreateUserPermission"
                                >
                                    <template #icon>
                                        <PlusOutlined />
                                    </template>
                                    Thêm Quyền User
                                </a-button>
                                <a-button
                                    class="flex items-center"
                                    @click="refreshData"
                                >
                                    <template #icon>
                                        <SyncOutlined />
                                    </template>
                                    Làm mới
                                </a-button>
                            </div>
                        </div>
                    </template>
                </a-table>
                <a-modal
                    v-model:open="openTicketDetails"
                    title="Thông Tin Tài Khoản"
                >
                    <div class="py-4">
                        <div
                            class="grid w-full border border-gray-300 divide-y"
                        >
                            <div
                                class="divide-x divide-gray-300 grid grid-cols-4 w-full bg-gray-100"
                            >
                                <div class="title p-2 font-bold">
                                    Tên tài khoản
                                </div>
                                <div class="information col-span-3 p-2">
                                    {{ ticketDetails.name }}
                                </div>
                            </div>
                            <div
                                class="divide-x divide-gray-300 grid grid-cols-4 w-full"
                            >
                                <div class="title p-2 font-bold">Email</div>
                                <div class="information col-span-3 p-2">
                                    {{ ticketDetails.email }}
                                </div>
                            </div>
                            <div
                                class="divide-x divide-gray-300 grid grid-cols-4 w-full bg-gray-100"
                            >
                                <div class="title p-2 font-bold">Vai trò</div>
                                <div class="information col-span-3 p-2">
                                    {{ ticketDetails.role }}
                                </div>
                            </div>
                            <div
                                v-if="false"
                                class="divide-x divide-gray-300 grid grid-cols-4 w-full"
                            >
                                <div class="title p-2 font-bold">
                                    Quyền được cấp
                                </div>
                                <div class="information col-span-3 p-2">
                                    <div
                                        class="inline italic"
                                        v-for="(
                                            permission, index
                                        ) in ticketDetails.permissions"
                                        :key="permission"
                                    >
                                        {{
                                            permission.title +
                                            (index <
                                            ticketDetails.permissions.length - 1
                                                ? ", "
                                                : "")
                                        }}
                                    </div>
                                </div>
                            </div>
                            <div
                                class="divide-x divide-gray-300 grid grid-cols-4 w-full bg-gray-100"
                            >
                                <div class="title p-2 font-bold">Ngày Tạo</div>
                                <div class="information col-span-3 p-2">
                                    {{ ticketDetails.created_at }}
                                </div>
                            </div>
                            <div
                                class="divide-x divide-gray-300 grid grid-cols-4 w-full"
                            >
                                <div class="title p-2 font-bold">
                                    Cập nhật lúc
                                </div>
                                <div class="information col-span-3 p-2">
                                    {{ ticketDetails.updated_at }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <template #footer>
                        <a-button key="back" @click="openTicketDetails = false"
                            >Close</a-button
                        >
                    </template>
                </a-modal>
                <a-modal
                    v-model:open="openAddNewUser"
                    :closable="false"
                    :maskClosable="false"
                    title="Thông Tin Tài Khoản"
                    :footer="null"
                >
                    <a-form
                        :model="formState"
                        name="login"
                        class="user-form"
                        layout="vertical"
                        @submit="handleSubmitUser"
                        @finishFailed="onFinishFailed"
                    >
                        <p class="text-sm">
                            Vui lòng điền vào phần bắt buộc để tạo tài khoản
                        </p>
                        <a-form-item
                            label="Tên Người Dùng"
                            name="name"
                            :rules="[
                                {
                                    required: true,
                                    message: 'Vui lòng nhập tên người dùng!',
                                },
                            ]"
                        >
                            <a-input v-model:value="formState.name" type="text">
                            </a-input>
                        </a-form-item>
                        <a-form-item
                            label="Email"
                            name="email"
                            :rules="[
                                {
                                    required: true,
                                    message: 'Vui lòng nhập Email!',
                                },
                            ]"
                        >
                            <a-input
                                v-model:value="formState.email"
                                type="email"
                            >
                            </a-input>
                        </a-form-item>
                        <a-form-item
                            label="Mật khẩu"
                            name="password"
                            :rules="[
                                {
                                    required: true,
                                    message: 'Vui lòng nhập mật khẩu!',
                                },
                            ]"
                        >
                            <a-input-password
                                v-model:value="formState.password"
                            >
                                <template #prefix>
                                    <LockOutlined class="site-form-item-icon" />
                                </template>
                            </a-input-password>
                        </a-form-item>
                        <a-form-item label="Hình đại diện" name="image">
                            <a-upload
                                :before-upload="beforeUpload"
                                @remove="handleRemove"
                                list-type="picture-card"
                                :previewIcon="false"
                                v-model:file-list="formState.image"
                            >
                                <div>
                                    <PlusOutlined />
                                    <div style="margin-top: 8px">Upload</div>
                                </div>
                            </a-upload>
                        </a-form-item>
                        <a-form-item>
                            <div
                                class="flex items-center gap-4 justify-end w-full"
                            >
                                <a-button
                                    type="primary"
                                    danger
                                    key="back"
                                    @click="handleCloseFormUser"
                                    >Hủy</a-button
                                >
                                <a-button
                                    :disabled="disabled"
                                    html-type="submit"
                                    type="primary"
                                    >Tạo mới</a-button
                                >
                            </div>
                        </a-form-item>
                    </a-form>
                </a-modal>
            </a-card>
        </div>
    </Page>
</template>

<script setup>
import {
    DeleteOutlined,
    PlusOutlined,
    SyncOutlined,
    EyeOutlined,
    UserOutlined,
} from "@ant-design/icons-vue";
import { reactive, computed, ref } from "vue";
import { usePagination } from "vue-request";
import { useRouter } from "vue-router";
import axios from "axios";
import { message } from "ant-design-vue";
import Page from "@/views/layouts/Page";

const routes = ref([
    {
        name: "home",
        breadcrumbName: "Trang chủ",
    },
    {
        name: "users-management",
        breadcrumbName: "Quản lý User",
    },
]);
let formState = ref({
    email: "",
    password: "",
    name: "",
    image: null,
});
const clearFormData = () => {
    formState.value = {
        email: "",
        password: "",
        name: "",
        image: null,
    };
};
const handleSubmitUser = async (values) => {
    try {
        const response = await axios.post("/api/users", {
            ...formState.value,
        });
        message.success(response.data.message);
        openAddNewUser.value = false;
        clearFormData();
        refreshData();
    } catch (err) {
        console.log(err);
    }
    // const authStore = useAuthStore();
    // await authStore.login({email: formState.email, password: formState.password});
};
const onFinishFailed = (errorInfo) => {
    console.log("Failed:", errorInfo);
};
const disabled = computed(() => {
    return !(formState.value.name && formState.value.email && formState.value.password);
});

const columns = [
    {
        title: "ID",
        dataIndex: "id",
        key: "id",
        width: 100,
    },
    {
        title: "Tên tài khoản",
        dataIndex: "name",
        key: "name",
    },
    {
        title: "Email",
        dataIndex: "email",
        key: "email",
    },
    {
        title: "Vai trò",
        dataIndex: "role",
        key: "role",
    },
    {
        title: "Tạo bởi",
        dataIndex: "created_by",
        key: "created_by",
    },
    {
        title: "Ngày tạo",
        dataIndex: "created_at",
        key: "created_at",
    },
    {
        title: "Ngày cập nhật",
        dataIndex: "updated_at",
        key: "updated_at",
    },
    {
        title: "Hành động",
        dataIndex: "action",
        key: "action",
        fixed: "right",
    },
];

const openTicketDetails = ref(false);
const openAddNewUser = ref(false);
const ticketDetails = ref(null);

const handleOpenTicketDetails = (record) => {
    ticketDetails.value = record;
    openTicketDetails.value = true;
};

const confirmDelete = async (id) => {
    // handle Delete User
    try {
        let response = await axios.delete(`/api/users/${id}`);
        message.success(response.data.message);
        refreshData();
    } catch (err) {
        console.log(err)
    }
};

const refreshData = () => {
    run({
        limit: pageSize.value,
        page: 1,
    });
};

const queryData = (params) => {
    return axios.get("/api/users", {
        params,
    });
};

const handleSearchTable = (e) => {
    console.log();
    if (e.target.value) {
        run({
            limit: pageSize.value,
            page: 1,
            search: e.target.value,
        });
    } else {
        run({
            limit: pageSize.value,
            page: 1,
        });
    }
};

const beforeUpload = (file) => {
    fileList.value = [...(fileList.value || []), file];
    return false;
};
const handleUpload = () => {
    //   const formData = new FormData();
    //   fileList.value.forEach(file => {
    //     formData.append('files[]', file);
    //   });
    //   uploading.value = true;
    //   // You can use any AJAX library you like
    //   request('https://www.mocky.io/v2/5cc8019d300000980a055e76', {
    //     method: 'post',
    //     data: formData,
    //   })
    //     .then(() => {
    //       fileList.value = [];
    //       uploading.value = false;
    //       message.success('upload successfully.');
    //     })
    //     .catch(() => {
    //       uploading.value = false;
    //       message.error('upload failed.');
    //     });
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

const {
    data: dataSource,
    run,
    loading,
    current,
    totalPage,
    pageSize,
} = usePagination(queryData, {
    debounceInterval: 700,
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

const router = useRouter();

const handleCreateUser = () => {
    openAddNewUser.value = true;
};
const handleCloseFormUser = () => {
    clearFormData();
    openAddNewUser.value = false;
};
const handleCreateUserPermission = () => {
    router.push({ name: "roles" });
};
</script>

<style lang="scss" scoped></style>
<style lang="scss">
.user-form {
    .ant-form-item {
        .ant-form-item-explain-error {
            padding: 4px 0;
            text-align: left;
        }
    }
}
</style>
