import { createRouter, createWebHistory } from "vue-router";

import invoices from "../components/invoices/index.vue";
import notFound from "../components/notFound.vue";




const routes = [
    {
        path: "/",
        component: invoices,
    },
    {
        path: "/:pathMatch(.*)*",
        component: notFound,
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;