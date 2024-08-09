import {default as PageDashboard} from "../views/pages/admin/Dashboard.vue";


// import permissions from "@/stub/permissions";

const routes = [
    {
        name: "home",
        path: "/",
        meta: {requiresAuth: true},
        component: PageDashboard
    },
    // {
    //     path: "/login",
    //     name: "login",
    //     meta: {requiresAuth: false},
    //     component: PageLogin,
    // },
    // {
    //     path: "/register",
    //     name: "register",
    //     meta: {requiresAuth: false},
    //     component: PageRegister,
    // },
    // {
    //     path: "/reset-password",
    //     name: "resetPassword",
    //     meta: {requiresAuth: false},
    //     component: PageResetPassword,
    // },
    // {
    //     path: "/forgot-password",
    //     name: "forgotPassword",
    //     meta: {requiresAuth: false},
    //     component: PageForgotPassword,
    // },
    // {
    //     name: "dashboard",
    //     path: "/dashboard",
    //     meta: {requiresAuth: true},
    //     component: PageDashboard,
    // },
    // {
    //     path: "/users",
    //     children: [
    //         {
    //             name: "users.list",
    //             path: "list",
    //             meta: {requiresAuth: true, requiresAbility: abilities.LIST_USER},
    //             component: PageUsers,
    //         },
    //         {
    //             name: "users.create",
    //             path: "create",
    //             meta: {requiresAuth: true, requiresAbility: abilities.CREATE_USER},
    //             component: PageUsersCreate,
    //         },
    //         {
    //             name: "users.edit",
    //             path: ":id/edit",
    //             meta: {requiresAuth: true, requiresAbility: abilities.EDIT_USER},
    //             component: PageUsersEdit,
    //         },
    //     ]
    // },

    // {
    //     path: "/:catchAll(.*)",
    //     name: "notFound",
    //     meta: {requiresAuth: false},
    //     component: PageNotFound,
    // },
]

export default routes;
