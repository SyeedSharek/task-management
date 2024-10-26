import { createRouter, createWebHashHistory } from "vue-router";
import AddNew from "./../crud/AddNew.vue";
import List from "./../crud/List.vue";
import Edit from "./../crud/Edit.vue";
import Login from "./../login/Login.vue";
import Register from "./../login/Register.vue";
import Dashboard from "./../deshboard/Dashboard.vue";
import PermissionList from "./../permission/PermissionList.vue";
import PermissionCreate from "./../permission/PermissionCreate.vue";
import PermissionEdit from "./../permission/PermissionEdit.vue";
import ListUserRole from "./../user/listUserRole.vue";

const routes = [
    {
        path: "/",
        name: "Login",
        component: Login,
    },
    {
        path: "/dashboard",
        name: "Dashboard",
        component: Dashboard,
        children: [
            {
                path: "/add-new",
                name: "AddNew",
                component: AddNew,
            },
            {
                path: "/list",
                name: "List",
                component: List,
            },
            {
                path: "/edit/:id",
                name: "Edit",
                component: Edit,
            },
            {
                path: "/permissionList",
                name: "PermissionList",
                component: PermissionList,
            },
            {
                path: "/permissionCreate",
                name: "PermissionCreate",
                component: PermissionCreate,
            },
            {
                path: "/permissionEdit/:id",
                name: "PermissionEdit",
                component: PermissionEdit,
            },
            {
                path: "/listUserRole",
                name: "ListUserRole",
                component: ListUserRole,
            },
        ],
    },
    {
        path: "/register",
        name: "Register",
        component: Register,
    },
];

const router = createRouter({
    history: createWebHashHistory(),
    routes,
});
export default router;
