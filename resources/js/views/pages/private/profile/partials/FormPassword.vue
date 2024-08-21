<template>
    <Panel title="Cài đặt mật khẩu">
        <form @submit.prevent="onFormSubmit">
            <div class="mb-2">
                <TextInput type="password" name="current-password" :required="true" label="Mật khẩu hiện tại" error-input="current_password" v-model="form.currentPassword" class="mb-4"/>
            </div>
            <div class="mb-2">
                <TextInput type="password" name="password" :required="true" label="Mật khẩu mới" error-input="password" v-model="form.password" class="mb-4"/>
            </div>
            <div class="mb-4">
                <TextInput type="password" name="password-confirm" :required="true" label="Xác nhận mật khẩu" error-input="password_confirmation" v-model="form.passwordConfirm" class="mb-4"/>
            </div>
            <Button type="submit" label="Cập nhật"/>
        </form>
    </Panel>
</template>

<script setup>

import AuthService from "@/services/AuthService";
import {reactive, defineComponent} from "vue";
import {useAlertStore} from "@/stores";
import {getResponseError} from "@/helpers/api";
import Button from "@/views/components/input/Button";
import Panel from "@/views/components/Panel";
import TextInput from "@/views/components/input/TextInput.vue";

const authService = new AuthService();
const alertStore = useAlertStore();
const form = reactive({
  currentPassword: null,
  password: null,
  passwordConfirm: null,
})

function onFormSubmit() {
  const payload = {
    current_password: form.currentPassword,
    password: form.password,
    password_confirmation: form.passwordConfirm,
  };
  authService.updatePassword(payload)
      .then((response) => (alertStore.success("Cập nhật mật khẩu thành công")))
      .catch((error) => (alertStore.error(getResponseError(error), error.response.status)));
}
</script>
