import {default as PageLogin} from "@/views/pages/auth/login/Main";
import {default as PageResetPassword} from "@/views/pages/auth/reset-password/Main";
import {default as PageForgotPassword} from "@/views/pages/auth/forgot-password/Main";
import {default as PageNotFound} from "@/views/pages/shared/404/Main";

import {default as PageDashboard} from "@/views/pages/private/dashboard/Main";
import PageProfile from "@/views/pages/private/profile/Main";
import Roles from '@/views/pages/private/roles/index';
import RolesCreate from '@/views/pages/private/roles/create';
import UsersManagement from '@/views/pages/private/permission-user';

import abilities from "@/stub/abilities";

const routes = [
    {
        name: "home",
        path: "/",
        meta: {requiresAuth: true},
        component: PageDashboard
    },
    {
        path: "/login",
        name: "login",
        meta: {requiresAuth: false},
        component: PageLogin,
    },
    {
        path: "/reset-password",
        name: "resetPassword",
        meta: {requiresAuth: false},
        component: PageResetPassword,
    },
    {
        path: "/forgot-password",
        name: "forgotPassword",
        meta: {requiresAuth: false},
        component: PageForgotPassword,
    },
    {
        name: "dashboard",
        path: "/dashboard",
        meta: {requiresAuth: true},
        component: PageDashboard,
    },
    {
        name: "profile",
        path: "/profile",
        meta: {requiresAuth: true},
        component: PageProfile,
    },
    {
        path: "/users",
        name: 'users',
        children: [
            {
                name: "users.list",
                path: "list",
                meta: {requiresAuth: true, requiresAbility: abilities.LIST_USER},
                component: PageDashboard
            },
            {
                name: "users.create",
                path: "create",
                meta: {requiresAuth: true, requiresAbility: abilities.CREATE_USER},
                component: PageDashboard
            },
            {
                name: "users.edit",
                path: ":id/edit",
                meta: {requiresAuth: true, requiresAbility: abilities.EDIT_USER},
                component: PageDashboard
            },
        ]
    },
    {
        path: "/users-management",
        name: 'users-management',
        component: UsersManagement,
        children: [
            {
                name: "users-management-create",
                path: "create",
                meta: {requiresAuth: true, requiresAbility: abilities.CREATE_USER},
                component: PageDashboard
            },
            {
                name: "users.edit",
                path: ":id/edit",
                meta: {requiresAuth: true, requiresAbility: abilities.EDIT_USER},
                component: PageDashboard
            },
        ]
    },
    {
        path: '/roles',
        name: 'roles',
        component: Roles,
        meta: {requiresAuth: true}
    },
    {
        path: "/:catchAll(.*)",
        name: "notFound",
        meta: {requiresAuth: false},
        component: PageNotFound,
    },
]

export default routes;
