import { createRouter, createWebHistory } from 'vue-router'

const routes = [
    {
        path: '/',
        component: () => import('../views/CatalogoView.vue')
    },
    {
        path: '/productos/create',
        component: () => import('../views/ProductoCreateView.vue')
    },
    {
        path: '/carrito',
        component: () => import('../views/CarritoView.vue')
    },
    {
        path: '/pedido/:id',
        component: () => import('../views/PedidoConfirmacionView.vue')
    },
]

export default createRouter({
    history: createWebHistory(),
    routes
})
