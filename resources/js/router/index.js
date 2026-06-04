import { createRouter, createWebHistory } from 'vue-router'

import CatalogoView from '../views/CatalogoView.vue'
import ProductoCreateView from '../views/ProductoCreateView.vue'

const routes = [
    {
        path: '/',
        component: CatalogoView
    },
    {
        path: '/productos/create',
        component: ProductoCreateView
    }
]

export default createRouter({
    history: createWebHistory(),
    routes
})