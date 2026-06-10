<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const carrito = ref(JSON.parse(localStorage.getItem('carrito') || '[]'))
const cargando = ref(false)
const error = ref('')
const token = localStorage.getItem('token')

const total = () => carrito.value.reduce((s, i) => s + i.precio * i.cantidad, 0)

const eliminar = (index) => {
    carrito.value.splice(index, 1)
    localStorage.setItem('carrito', JSON.stringify(carrito.value))
}

const hacerPedido = async () => {
    if (!token) { error.value = 'Debes iniciar sesión'; return }
    cargando.value = true
    try {
        const { data } = await axios.post('/api/v1/pedidos', {
            items: carrito.value.map(i => ({
                producto_id: i.producto_id,
                cantidad: i.cantidad,
                precio: i.precio
            }))
        }, {
            headers: { Authorization: `Bearer ${token}` }
        })
        localStorage.removeItem('carrito')
        router.push(`/pedido/${data.pedido_id}`)
    } catch (e) {
        error.value = 'Error al procesar el pedido'
        console.error(e)
    } finally {
        cargando.value = false
    }
}
</script>

<template>
<div style="padding:20px;max-width:600px">
    <button @click="$router.push('/')" style="margin-bottom:16px;cursor:pointer">← Volver</button>
    <h1>🛒 Tu Carrito</h1>

    <div v-if="!carrito.length">
        <p>El carrito está vacío.</p>
    </div>

    <div v-for="(item, i) in carrito" :key="i" style="border-bottom:1px solid #eee;padding:10px 0">
        <strong>{{ item.nombre }}</strong>
        <p>Cantidad: {{ item.cantidad }} × ${{ item.precio }} = <strong>${{ (item.cantidad * item.precio).toFixed(2) }}</strong></p>
        <button @click="eliminar(i)" style="color:red;border:none;background:none;cursor:pointer">Eliminar</button>
    </div>

    <div v-if="carrito.length" style="margin-top:20px">
        <h3>Total: ${{ total().toFixed(2) }}</h3>
        <p v-if="error" style="color:red">{{ error }}</p>
        <button
            @click="hacerPedido"
            :disabled="cargando"
            style="background:#18181b;color:white;padding:10px 24px;border-radius:6px;border:none;cursor:pointer;font-size:16px"
        >
            {{ cargando ? 'Procesando...' : 'Hacer Pedido' }}
        </button>
    </div>
</div>
</template>
