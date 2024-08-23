import { createRouter, createWebHistory } from "vue-router";
import UserForm from "@components/user/userForm.vue";
import UserList from "@components/user/userList.vue";

const routes = [
    {
        path: "/",
        name: "UserForm",
        component: UserForm,
    },
    {
        path: "/user/:id",
        name: "UserForm",
        component: UserForm,
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;