<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import axios from 'axios'

const props = defineProps(['pedidoId'])
const emailListo = ref(false)
let intervalo = null

onMounted(() => {
    const token = localStorage.getItem('token')
    intervalo = setInterval(async () => {
        try {
            const { data } = await axios.get(`/api/v1/pedidos/${props.pedidoId}`, {
                headers: { Authorization: `Bearer ${token}` }
            })
            emailListo.value = !!data.email_enviado_at
            if (emailListo.value) clearInterval(intervalo)
        } catch (e) {
            console.error('Error consultando pedido:', e)
        }
    }, 3000)
})

onUnmounted(() => clearInterval(intervalo))
</script>

<template>
<div class="pedido-estado">
    <div v-if="!emailListo" class="estado procesando">
        ⏳ Procesando tu pedido...
    </div>
    <div v-else class="estado listo">
        ✅ ¡Pedido confirmado! Revisa tu correo.
    </div>
</div>
</template>

<style scoped>
.pedido-estado { text-align: center; padding: 1.5rem; font-size: 1.1rem; }
.estado { padding: 1rem 2rem; border-radius: 8px; display: inline-block; }
.procesando { background: #fff8e1; color: #f59e0b; border: 1px solid #fcd34d; }
.listo { background: #ecfdf5; color: #10b981; border: 1px solid #6ee7b7; }
</style>
