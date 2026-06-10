<script setup>
import { useRoute } from 'vue-router'
import PedidoEstado from '../components/PedidoEstado.vue'

const route = useRoute()
const pedidoId = route.params.id
</script>

<template>
<div style="padding:40px;text-align:center">
    <h1>¡Gracias por tu compra!</h1>
    <p>Pedido #{{ pedidoId }}</p>
    <PedidoEstado :pedido-id="pedidoId" />
    <br>
    <button @click="$router.push('/')" style="margin-top:20px;cursor:pointer">← Seguir comprando</button>
</div>
</template>
