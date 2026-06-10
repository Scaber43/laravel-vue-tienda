<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import AdminNotificaciones from './AdminNotificaciones.vue'

const esAdmin = ref(false)
const cargado = ref(false)

onMounted(async () => {
    try {
        const token = localStorage.getItem('token')
        if (!token) { cargado.value = true; return }
        const { data } = await axios.get('/api/me', {
            headers: { Authorization: `Bearer ${token}` }
        })
        esAdmin.value = data.rol === 'admin'
    } catch (e) {}
    cargado.value = true
})
</script>

<template>
    <AdminNotificaciones v-if="cargado && esAdmin" />
    <router-view />
</template>
