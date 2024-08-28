import { defineStore } from 'pinia';

export const useRoleStore = defineStore({
    id: 'roles',
    state: () => ({
        role: null,
    }),
    actions: {
        setCurrentRole(role){
            this.role = role
        }
    }
});
