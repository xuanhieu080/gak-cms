<template>
    <div>
        <Alert class="mb-4"/>
        <Form id="forgot-password-form" @submit.prevent="onFormSubmit">
            <TextInput type="email" :required="true" label="Email" error-input="email" name="email" v-model="form.email" autocomplete="email" class="mb-4"/>
            <div class="text-center">
                <Button type="submit" label="Gửi"/>
            </div>
        </Form>
    </div>
</template>

<script>
import AuthService from "@/services/AuthService";
import {reactive, defineComponent} from "vue";
import {useAlertStore} from "@/stores";
import {getResponseError} from "@/helpers/api";
import Button from "@/views/components/input/Button";
import TextInput from "@/views/components/input/TextInput";
import Alert from "@/views/components/Alert";
import Form from "@/views/components/Form";

export default defineComponent({
    name: "ForgotPasswordForm",
    components: {
        Form,
        Alert,
        Button,
        TextInput,
    },
    setup() {

        const authService = new AuthService();
        const alertStore = useAlertStore();
        const form = reactive({
            email: null,
        })

        function onFormSubmit() {
            authService.forgotPassword({email: form.email})
                .then((response) => (alertStore.success(response.data.message)))
                .catch((error) => (alertStore.error(getResponseError(error), error.response.status)));
        }

        return {
            onFormSubmit,
            form
        }
    },
});
</script>
