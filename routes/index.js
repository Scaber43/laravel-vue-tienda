import { createRouter, createWebHistory } from 'vue-router'

import CatalogoView from '../views/CatalogoView.vue'

const routes = [
    {
        path: '/',
        name: 'catalogo',
        component: CatalogoView
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router