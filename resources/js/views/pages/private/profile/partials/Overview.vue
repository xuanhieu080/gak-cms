<template>
    <Panel>
        <div class="flex">
            <div class="w-1/6 px-2">
                <img v-if="user.avatar_url" :src="authStore.user.avatar_url" class="w-full rounded-full" :alt="user.full_name"/>
                <div v-else class="rounded-full">
                    <Avatar></Avatar>
                </div>
            </div>
            <div class="w-5/6 px-6 pt-2">
                <div class="items-center">
                    <ul class="mt-2">
                        <li class="mb-1 text-2xl font-bold">{{ user.full_name }}
                            <Badge theme="success" class="inline" v-if="user.email_verified_at">
                                Đã xác minh
                            </Badge>
                        </li>
                        <li class="text-gray-700"><i class="fa fa-envelope"></i> {{ user.email }}</li>
                        <li class="mt-5 text-gray-500">
                                Thành viên từ{{user.created_at}}

                        </li>
                    </ul>
                    <div class="mt-4">
                        <Button @click.prevent="onChangeAvatar" type="success" label="Thay đổi hình ảnh"/>
                        <form @submit.prevent="onVerificationSend" class="inline-block ml-3" v-if="!user.email_verified_at">
                            <Button type="submit" label="Xác minh email"/>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </Panel>
</template>

<script>
import AuthService from "@/services/AuthService";
import {getResponseError} from "@/helpers/api";

import {computed, defineComponent} from 'vue'
import {useAuthStore} from "@/stores/auth";
import {useAlertStore} from "@/stores";
import Avatar from "@/views/components/icons/Avatar";
import Button from "@/views/components/input/Button";
import Panel from "@/views/components/Panel";
import Badge from "@/views/components/Badge";

export default defineComponent({
    components: {
        Panel,
        Badge,
        Avatar,
        Button
    },
    emits: ['changeAvatar'],
    setup(props, {emit}) {
        const authService = new AuthService();
        const alertStore = useAlertStore();
        const authStore = useAuthStore()
        const {user} = useAuthStore()

        const avatarUrl = computed(() => {
            return user && user.hasOwnProperty('avatar_url') && user.avatar_url;
        });

        function onVerificationSend() {
            authService.sendVerification({user: user.id})
                .then((response) => (alertStore.success("Đã gửi liên kết xác minh email.")))
                .catch((error) => (alertStore.error(getResponseError(error), error.response.status)));
        }

        function onChangeAvatar() {
            emit('changeAvatar');
        }

        return {
            user,
            onVerificationSend,
            onChangeAvatar,
            avatarUrl,
            authStore
        }
    }
});
</script>
