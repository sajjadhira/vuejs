import { createRouter, createWebHistory } from "vue-router";

import invoices from "../components/invoices/index.vue";
import notFound from "../components/notFound.vue";
import newInvoice from "../components/invoices/new.vue";
import showInvoice from "../components/invoices/show.vue";
import editInvoice from "../components/invoices/edit.vue";



const routes = [
    {
        path: "/",
        component: invoices,
    },
    {
        path: "/invoices/new",
        component: newInvoice,
    },
    {
        path: "/invoices/show/:id",
        component: showInvoice,
        props: true,
    },
    {
        path: "/invoices/edit/:id",
        component: editInvoice,
        props: true,
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