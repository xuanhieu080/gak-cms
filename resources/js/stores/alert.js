import {defineStore} from 'pinia';

export const useAlertStore = defineStore({
    id: 'alert',
    state: () => {
        return {
            messages: [],
            errors: [],
            type: null,
        }
    },
    actions: {
        success(message) {
            this.clear();
            this.push(message);
            this.type = 'success'
        },
        error(message, status = 400) {
            this.clear();
            this.push(message, status);
            this.type = 'error'
        },
        push(message, status = 400) {
            if (status == 422) {
                this.errors = message;
            } else {
                if (Array.isArray(message) || (typeof message === 'object' && message != null)) {
                    for (let i in message) {
                        this.messages.push(message[i]);
                    }
                } else {
                    this.messages.push(message);
                }
            }
        },
        clear() {
            this.errors = [];
            this.messages = [];
            this.type = null;
        },
        isSuccess() {
            return this.type === 'success';
        },
        isError() {
            return this.type === 'error';
        },
        hasAlert() {
            return this.messages.length > 0 && this.type != null;
        },
        hasMultiple() {
            return (Array.isArray(this.messages.length) && this.messages.length > 1) || ((typeof this.messages === 'object' && this.messages != null)  &&Object.keys(this.messages).length > 1);
        },
    }
});
