<template>
    <div class="permision-page">
        <a-breadcrumb :routes="routes" class="bg-gray-100 p-2">
            <template #itemRender="{ route, params, routes, paths }">
                <span v-if="routes.indexOf(route) === routes.length - 1" class="text-blue-500">{{ route.breadcrumbName }}</span>
                <router-link v-else :to="{ name: route.name }" class="!text-black">{{ route.breadcrumbName }}</router-link>
            </template>
        </a-breadcrumb>
        <h2 class="text-3xl my-4">Quản Lý Phân Quyền Người Dùng</h2>
        <a-card class="w-full max-w-[500px]">
            <a-form layout="vertical" :model="formState" autocomplete="off" @finish="onFinish" @finishFailed="onFinishFailed">
                <a-form-item label="Chọn tài khoản" name="user" :rules="[{ required: true, message: 'Vui lòng chọn người dùng!' }]">
                    <a-select
                        v-model:value="formState.user"
                        :options="formState.data_user"
                        :not-found-content="formState.fetching ? undefinded : null"
                        placeholder="Chọn user"
                        :disabled="isSetAccount"
                        @search="handleSearch"
                        @change="handleChange"
                        :filter-option="false"
                        show-search>
                        <template #notFoundContent>
                            <a-spin v-if="formState.fetching" size="small" />
                        </template>
                    </a-select>
                </a-form-item>
                <a-form-item>
                    <div class="flex items-center gap-2">
                        <a-button
                            v-if="!isSetAccount"
                            :disabled="disabledAccount"
                            type="primary"
                            html-type="submit"
                            class="submit-button"
                            @click="handleSetAccount">
                            Tiếp tục
                        </a-button>
                        <a-button v-if="isSetAccount" danger @click="handleChangeAccount"> Thay đổi </a-button>
                    </div>
                </a-form-item>
            </a-form>
        </a-card>
        <a-card v-if="isSetAccount" class="mt-4">
            <div class="bg-red-500 w-full p-3 rounded-md text-white font-medium mb-4 text-lg">
                Cấp quyền cho tài khoản: {{ formState.user?.label }}
            </div>
            <div class="permission-list space-y-4">
                <div v-for="(data, index) in dataSource?.data?.data" class="permission-item" :key="data">
                    <div class="permission-name w-full bg-blue-800 text-white p-2 rounded-md">
                        {{ data.title }}
                    </div>
                    <div v-if="data.permissions.length > 0" class="w-full py-4">
                        <a-checkbox-group v-model:value="checkedList[index]" style="width: 100%">
                            <a-row>
                                <a-col
                                    class="gutter-row"
                                    v-for="child_permission in data.permissions"
                                    :jey="child_permission"
                                    :md="{ span: 10 }"
                                    :lg="{ span: 6 }">
                                    <a-checkbox :value="child_permission.name">{{ child_permission.title }}</a-checkbox>
                                </a-col>
                            </a-row>
                        </a-checkbox-group>
                    </div>
                </div>
            </div>
            <a-button :disabled="disabled" class="mt-4" type="primary" @click="handleUpdatePermissionForUser">Cập nhật</a-button>
        </a-card>
    </div>
</template>

<script setup>
import { reactive, computed, ref, watch, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { usePagination } from 'vue-request';
import { message } from 'ant-design-vue';
import axios from 'axios';

const router = useRouter();

const routes = ref([
    {
        name: 'home',
        breadcrumbName: 'Trang chủ',
    },
    {
        name: 'setting-user-permission',
        breadcrumbName: 'Quản lý User',
    },
    {
        name: 'setting-create-user-permission',
        breadcrumbName: 'Chi tiết User',
    },
]);

const formState = reactive({
    user: null,
    data_user: [],
    fetching: false,
});
let lastFetchId = 0;
const disabled = computed(() => {
    return !isSetAccount.value;
});
const disabledAccount = computed(() => {
    return !formState.user;
});
const checkedList = ref({});
const isSetAccount = ref(false);
let timeout;
let currentValue = '';
const columns = [
    {
        title: 'ID',
        dataIndex: 'id',
        key: 'id',
    },
    {
        title: 'TÊN VAI TRÒ',
        dataIndex: 'role_name',
        key: 'role_name',
    },
    {
        title: 'NGÀY TẠO',
        dataIndex: 'created_at',
        key: 'created_at',
    },
    {
        title: 'PHÂN QUYỀN',
        dataIndex: 'action',
        align: 'center',
        key: 'action',
    },
];

const handleSetAccount = () => {
    isSetAccount.value = true;
};
const handleChangeAccount = () => {
    isSetAccount.value = false;
    formState.user = null;
    checkedList.value = {};
};

function fetchUserDropdown(value, callback) {
    if (timeout) {
        clearTimeout(timeout);
        timeout = null;
    }
    currentValue = value;
    timeout = setTimeout(searchUser(value, callback), 300);
}

const handleSearch = async (val) => {
    fetchUserDropdown(val, (data) => (formState.data_user = data));
};
const handleChange = (val, item) => {
    formState.user = item;
    fetchUserDropdown('', (data) => (formState.data_user = data));
};

async function searchUser(value,callback) {
    lastFetchId += 1;
    formState.fetching = true;
    const params = new URLSearchParams({
        name: value,
    });
    if (value) {
        await axios.get(`/api/users?${params}`).then((response) => {
            if (currentValue === value) {
                const result = response.data.data?.map((user) => ({
                    label: user.name,
                    value: user.id,
                    data: user,
                }));
                formState.fetching = false;
                callback(result);
            }
        });
    } else {
        await axios.get(`/api/users`).then((response) => {
            if (currentValue === value) {
                const result = response.data.data?.map((user) => ({
                    label: user.name,
                    value: user.id,
                    data: user,
                }));
                formState.fetching = false;
                callback(result);
            }
        });
    }
}

onMounted(() => {
    searchUser('', (data) => (formState.data_user = data));
});
const handleUpdatePermissionForUser = async () => {
    let permissionList = [];
    if (Object.values(checkedList.value).length > 0) {
        Object.values(checkedList.value).forEach((item) => {
            Object.values(item).forEach((permission_name, index) => {
                permissionList.push(permission_name);
            });
        });
    }
    try {
        let response = await axios.post(`/api/users/${formState.user.value}/sync-permission`, {
            permissions: permissionList,
        });
        if (response.data.code == 200) {
            message.success(response.data.message);
            handleChangeAccount();
            // refreshRole();
        }
    } catch (error) {
        console.log(error);
    }
};

const queryData = (params) => {
    return axios.get('/api/permissions/list');
};
const { data: dataSource, run, loading } = usePagination(queryData);

watch(formState.user, () => {
    formState.data_user = [];
    formState.fetching = false;
});

watch(
    () => (dataSource.value, isSetAccount.value),
    () => {
        if (isSetAccount.value && dataSource.value) {
            console.log(formState)
            const permissionList = formState.user?.data.permissions?.map((item) => item.name) || [];

            if (dataSource.value?.data?.data?.length > 0 && permissionList.length > 0) {
                checkedList.value = dataSource.value.data.data.reduce((acc, ele, index) => {
                    acc[index] = ele.permissions.filter((list) => permissionList.includes(list.name)).map((list) => list.name);
                    return acc;
                }, {});
            }
        }
    },
);
</script>

<style lang="scss" scoped></style>
