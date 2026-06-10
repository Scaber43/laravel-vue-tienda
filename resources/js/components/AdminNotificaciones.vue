<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import Echo from '../plugins/echo'

const pedidosNuevos = ref([])
const alertasStock = ref([])

onMounted(() => {
    console.log('AdminNotificaciones montado, conectando a admin-panel...')
    Echo.private('admin-panel')
        .listen('NuevoPedidoRecibido', (e) => {
            console.log('Nuevo pedido:', e)
            pedidosNuevos.value.unshift(e)
            setTimeout(() => pedidosNuevos.value.pop(), 10000)
        })
        .listen('StockBajoAlerta', (e) => {
            console.log('Stock bajo:', e)
            alertasStock.value.unshift(e)
        })
})

onUnmounted(() => Echo.leave('admin-panel'))
</script>

<template>
<div style="position:fixed;top:16px;right:16px;z-index:9999;width:320px">
    <TransitionGroup name="toast" tag="div">
        <div
            v-for="p in pedidosNuevos"
            :key="p.id"
            style="background:#18181b;color:white;padding:12px 16px;border-radius:8px;margin-bottom:8px;box-shadow:0 4px 12px rgba(0,0,0,0.3)"
        >
            🛒 Nuevo pedido #{{ p.id }} de {{ p.cliente }} — ${{ p.total }}
        </div>
    </TransitionGroup>
    <div
        v-for="a in alertasStock"
        :key="a.producto_id"
        style="background:#fef3c7;color:#92400e;padding:12px 16px;border-radius:8px;margin-bottom:8px;border:1px solid #fcd34d"
    >
        ⚠️ Stock bajo: {{ a.nombre }} ({{ a.stock_actual }} unidades)
    </div>
</div>
</template>

<style scoped>
.toast-enter-active, .toast-leave-active { transition: all 0.3s ease; }
.toast-enter-from, .toast-leave-to { opacity: 0; transform: translateX(20px); }
</style>
