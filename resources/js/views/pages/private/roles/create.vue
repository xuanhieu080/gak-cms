<template>
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
        <a-card class="w-full max-w-[500px] my-4">
            <div class="title mb-2 font-semibold text-xl">Vai trò</div>
            <a-button type="primary" block>{{ role?.name }}</a-button>
        </a-card>
        <a-card class="mt-4">
            <div class="permission-list space-y-4">
                <div
                    v-for="(permission, index) in dataSource?.data?.data"
                    class="permission-item"
                    :key="permission"
                >
                    <div
                        class="permission-name w-full bg-blue-800 text-white p-2 rounded-md"
                    >
                        {{ permission.name }}
                    </div>
                    <div
                        v-if="permission.permissions.length > 0"
                        class="w-full py-4"
                    >
                        <a-checkbox-group
                            v-model:value="checkedList[index]"
                            style="width: 100%"
                        >
                            <a-row>
                                <a-col
                                    class="gutter-row"
                                    v-for="child_permission in permission.permissions"
                                    :jey="child_permission"
                                    :md="{ span: 10 }"
                                    :lg="{ span: 6 }"
                                >
                                    <a-checkbox
                                        :value="child_permission.name"
                                        >{{
                                            child_permission.title
                                        }}</a-checkbox
                                    >
                                </a-col>
                            </a-row>
                        </a-checkbox-group>
                    </div>
                </div>
            </div>
            <a-button class="mt-4" type="primary" @click="handleAddPermission"
                >Cập nhật</a-button
            >
        </a-card>
    </div>
</template>

<script setup>
import { reactive, computed, ref, watch } from "vue";
import { usePagination } from "vue-request";
import { useRouter } from "vue-router";
import axios from "axios";
import { message } from "ant-design-vue";
import { useRoleStore } from "@/stores";
import { storeToRefs } from "pinia";

const roleStore = useRoleStore();
const { role } = storeToRefs(roleStore);
const router = useRouter();

const formState = reactive({
    role_name: "",
});
const disabled = computed(() => {
    return !formState.role_name;
});
const checkedList = ref({});

const routes = ref([
    {
        name: "home",
        breadcrumbName: "Trang chủ",
    },
    {
        name: "setting-roles",
        breadcrumbName: "Quản lý vai trò",
    },
    {
        name: "setting-create-roles",
        breadcrumbName: "Chi tiết vai trò",
    },
]);

const handleAddPermission = async () => {
    let permissionList = [];
    let params = {};
    if (Object.values(checkedList.value).length > 0) {
        Object.values(checkedList.value).forEach((item) => {
            Object.values(item).forEach((permission_name, index) => {
                permissionList.push(permission_name);
            });
        });
    }
    try {
        let response = await axios.post(
            `/api/roles/${router.currentRoute.value.params.id}/sync`,
            {
                permissions: permissionList,
            }
        );
        if (response.data.code == 200) {
            message.success(response.data.message);
            refreshRole();
        }
    } catch (error) {
        console.log(error);
    }
};
const queryData = (params) => {
    return axios.get("/api/permissions");
};
const queryRole = (params) => {
    return axios.get("/api/roles", {
        params: {
            id: router.currentRoute.value.params.id,
        },
    });
};
const { data: dataSource, run, loading } = usePagination(queryData);

const {
    data: roleData,
    run: refreshRole,
    loading: loadingRole,
} = usePagination(queryRole);
watch(
    () => roleData.value,
    () => {
        roleStore.setCurrentRole(roleData.value?.data.data[0]);
    }
);

watch(
    () => (dataSource.value, roleData.value),
    () => {
        if (roleData.value && dataSource.value) {
            const permissionList =
                roleData.value?.data?.data[0]?.permissions?.map(
                    (item) => item.name
                ) || [];

            if (
                dataSource.value?.data?.data?.length > 0 &&
                permissionList.length > 0
            ) {
                checkedList.value = dataSource.value.data.data.reduce(
                    (acc, ele, index) => {
                        acc[index] = ele.permissions
                            .filter((list) =>
                                permissionList.includes(list.name)
                            )
                            .map((list) => list.name);
                        return acc;
                    },
                    {}
                );
            }
        }
    }
);
</script>

<style lang="scss">
.ant-input {
    box-sizing: border-box;
    margin: 0;
    padding: 4px 11px;
    color: rgba(0, 0, 0, 0.88);
    font-size: 14px;
    line-height: 1.5714285714285714;
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
</style>
