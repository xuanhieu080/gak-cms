import abilities from "@/stub/abilities";
import {default as PageLogin} from "@/views/pages/auth/login/Main";
import {default as PageResetPassword} from "@/views/pages/auth/reset-password/Main";
import {default as PageForgotPassword} from "@/views/pages/auth/forgot-password/Main";
import {default as PageNotFound} from "@/views/pages/shared/404/Main";

import {default as PageDashboard} from "@/views/pages/private/dashboard/Main";
import PageProfile from "@/views/pages/private/profile/Main";
import Roles from '@/views/pages/private/roles/index';
import UsersManagement from '@/views/pages/private/permission-user';
import WereHouse from '@/views/pages/private/warehouse/index';
import CreateWereHouse from '@/views/pages/private/warehouse/create';
import DetailsWereHouse from '@/views/pages/private/warehouse/details';
import Materials from '@/views/pages/private/management/materials';
import AttributeGroup from '@/views/pages/private/management/attribute-group';
import CreateAttributeGroup from '@/views/pages/private/management/create-attribute-group';
import Attribute from '@/views/pages/private/management/attribute';
import AttributeCreate from '@/views/pages/private/management/create-attribute';
import CreateMaterial from '@/views/pages/private/management/create-material';
import Units from '@/views/pages/private/management/units';
import ProductManagement from '@/views/pages/private/products/management';
import ProductCreate from '@/views/pages/private/products/create';
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
        name: "product-management",
        path: "/products",
        meta: {requiresAuth: true},
        children: [
            {
                path: "",
                name: "product-index",
                meta: {requiresAuth: true},
                component: ProductManagement,
            },
            {
                name: "product-create",
                path: "create",
                meta: {requiresAuth: true},
                component: ProductCreate,
            },
        ]
    },
    {
        name: "warehouse",
        path: "/warehouse",
        meta: {requiresAuth: true},
        children: [
            {
                path: "",
                name: "warehouse-index",
                meta: {requiresAuth: true},
                component: WereHouse,
            },
            {
                name: "warehouse-create",
                path: "create",
                meta: {requiresAuth: true},
                component: CreateWereHouse,
            },
            {
                name: "warehouse-details",
                path: "details/:id",
                meta: {requiresAuth: true},
                component: DetailsWereHouse,
            },
        ]
    },
    {
        name: "management",
        path: "/management",
        meta: {requiresAuth: true},
        children: [
            {
                name: "management-materials",
                path: "materials",
                meta: {requiresAuth: true},
                component: Materials,
            },
            {
                name: "management-units",
                path: "units",
                meta: {requiresAuth: true},
                component: Units
            },
            {
                name: "management-materials-create",
                path: "materials/create",
                meta: {requiresAuth: true},
                component: CreateMaterial
            },
            {
                name: "management-attribute-group",
                path: "attribute-group",
                meta: {requiresAuth: true},
                component: AttributeGroup
            },
            {
                name: "attribute-group-create",
                path: "attribute-group/create",
                meta: {requiresAuth: true},
                component: CreateAttributeGroup
            },
            {
                name: "management-attribute",
                path: "attribute",
                meta: {requiresAuth: true},
                component: Attribute
            },
            {
                name: "management-attribute-create",
                path: "attribute/create",
                meta: {requiresAuth: true},
                component: AttributeCreate
            },
        ]
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
