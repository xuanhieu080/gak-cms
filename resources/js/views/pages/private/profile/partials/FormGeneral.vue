<template>
    <Panel title="Cài đặt chung">
        <form @submit.prevent="onFormSubmit">
            <TextInput type="text" error-input="first_name" :required="true" label="Họ" name="first_name" v-model="form.first_name" class="mb-2"/>
            <TextInput type="text" error-input="last_name" :required="true" label="Tên" name="last_name" v-model="form.last_name" class="mb-2"/>
            <TextInput type="email" error-input="email" :required="true" label="Email" name="email" v-model="form.email" autocomplete="email" class="mb-4"/>
            <Button type="submit" label="Cập nhật"/>
        </form>
    </Panel>
</template>

<script>
import AuthService from "@/services/AuthService";
import {defineComponent, reactive, onMounted} from "vue";
import {getResponseError} from "@/helpers/api";
import {useAuthStore} from "@/stores/auth";
import {useAlertStore} from "@/stores";
import Button from "@/views/components/input/Button";
import TextInput from "@/views/components/input/TextInput";
import Panel from "@/views/components/Panel";

export default defineComponent({
    components: {
        Panel,
        Button,
        TextInput,
    },

    setup: function () {

        const authService = new AuthService();
        const alertStore = useAlertStore();
        const authStore = useAuthStore();
        const form = reactive({
            first_name: null,
            last_name: null,
            email: null,
        })

        onMounted(() => {
            if (!authStore.user) {
                return;
            }
            form.first_name = authStore.user.first_name;
            form.last_name = authStore.user.last_name;
            form.email = authStore.user.email;
        })

        function onFormSubmit() {
            authService.updateUser(form)
                .then(() => authStore.getCurrentUser())
                .then((response) => (alertStore.success("Cập nhật hồ sơ thành công")))
                .catch((error) => (alertStore.error(getResponseError(error), error.response.status)));
        }

        return {
            onFormSubmit,
            form
        }
    },
});
</script>
