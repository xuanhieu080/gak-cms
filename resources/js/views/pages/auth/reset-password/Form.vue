<template>
    <div>
        <Alert class="mb-4"/>
        <Form id="reset-password-form" @submit.prevent="onFormSubmit">
            <div class="mb-2">
                <TextInput type="email" error-input="email" :required="true" label="Email" name="email" v-model="form.email" class="mb-2"/>
            </div>
            <div class="mb-2">
                <TextInput type="password" error-input="password" :required="true" label="Mật khẩu" name="password" v-model="form.password" class="mb-2"/>
            </div>
            <div class="mb-4">
                <TextInput type="password" error-input="password_confirmation" :required="true" label="Xác nhận mật khẩu" name="password-confirm" v-model="form.passwordConfirm" class="mb-2"/>
            </div>
            <div class="text-center">
                <Button type="submit" label="Gửi"/>
            </div>
        </Form>
    </div>
</template>

<script setup>
import AuthService from "@/services/AuthService";
import {reactive} from "vue";
import {useRoute} from "vue-router"
import {useAlertStore} from "@/stores";
import {getResponseError} from "@/helpers/api";
import Button from "@/views/components/input/Button";
import Alert from "@/views/components/Alert";
import Form from "@/views/components/Form";
import TextInput from "@/views/components/input/TextInput.vue";

const authService = new AuthService();
const alertStore = useAlertStore();
const route = useRoute();
const form = reactive({
  email: null,
  password: null,
  passwordConfirm: null,
})

function onFormSubmit() {
  const payload = {
    email: form.email,
    password: form.password,
    password_confirmation: form.passwordConfirm,
    token: route.query.token,
  };
  authService.resetPassword(payload)
      .then((response) => (alertStore.success(response.data.message)))
      .catch((error) => (alertStore.error(getResponseError(error), error.response.status)));
}
</script>
