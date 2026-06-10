<script setup>
import { ref, watch, onMounted } from 'vue'
import axios from 'axios'
import { useRoute, useRouter } from 'vue-router'
import { useFiltros } from '../composables/useFiltros'
import PaginacionNav from '../components/PaginacionNav.vue'

const route = useRoute()
const router = useRouter()
const { filtros } = useFiltros()
const categorias = ref([])
const resultado = ref({ data: [], meta: {} })
const usuario = ref(null)
const cargando = ref(false)
const carrito = ref(JSON.parse(localStorage.getItem('carrito') || '[]'))

const cargarCategorias = async () => {
    const { data } = await axios.get('/api/v1/categorias')
    categorias.value = data.data
}
const cargarProductos = async () => {
    cargando.value = true
    const { data } = await axios.get('/api/v1/productos', {
        params: {
            busqueda: filtros.busqueda,
            categoria_id: filtros.categoria_id,
            precio_min: filtros.precio_min,
            precio_max: filtros.precio_max,
            page: filtros.pagina,
        }
    })
    resultado.value = data
    cargando.value = false
}
const cargarUsuario = async () => {
    try {
        const token = localStorage.getItem('token')
        if (!token) return
        const { data } = await axios.get('/api/v1/me', {
            headers: { Authorization: `Bearer ${token}` }
        })
        usuario.value = data
    } catch (error) {
        console.error('Error obteniendo usuario:', error)
    }
}
const agregarAlCarrito = (producto) => {
    const existente = carrito.value.find(i => i.producto_id === producto.id)
    if (existente) {
        existente.cantidad++
    } else {
        carrito.value.push({
            producto_id: producto.id,
            nombre: producto.nombre,
            precio: producto.precio,
            cantidad: 1
        })
    }
    localStorage.setItem('carrito', JSON.stringify(carrito.value))
}

watch(() => route.query, cargarProductos, { immediate: true })
onMounted(() => {
    cargarCategorias()
    cargarUsuario()
})
</script>

<template>
<div style="padding:20px">
    <!-- USUARIO -->
    <div v-if="usuario">
        <h2>Usuario autenticado</h2>
        <p><strong>Nombre:</strong> {{ usuario.name }}</p>
        <p><strong>Correo:</strong> {{ usuario.email }}</p>
        <p><strong>Rol:</strong> {{ usuario.rol }}</p>
        <hr />
    </div>

    <div style="display:flex; justify-content:space-between; align-items:center">
        <h1>Catálogo Completo</h1>
        <button
            v-if="carrito.length"
            @click="$router.push('/carrito')"
            style="background:#18181b;color:white;padding:8px 16px;border-radius:6px;border:none;cursor:pointer"
        >
            🛒 Carrito ({{ carrito.length }})
        </button>
    </div>

    <!-- FILTROS -->
    <div>
        <input v-model="filtros.busqueda" placeholder="Buscar..." />
        <select v-model="filtros.categoria_id">
            <option value="">Todas</option>
            <option v-for="c in categorias" :key="c.id" :value="c.id">{{ c.nombre }}</option>
        </select>
        <input v-model="filtros.precio_min" type="number" placeholder="Min" />
        <input v-model="filtros.precio_max" type="number" placeholder="Max" />
    </div>
    <hr />

    <!-- PRODUCTOS -->
    <div v-if="cargando">Cargando...</div>
    <div v-for="p in resultado.data" :key="p.id">
        <h3>{{ p.nombre }}</h3>
        <p>{{ p.descripcion }}</p>
        <strong>${{ p.precio }}</strong>
        <br>
        <small>{{ p.categoria?.nombre }}</small>
        <br>
        <button
            @click="agregarAlCarrito(p)"
            style="margin-top:6px;background:#18181b;color:white;padding:6px 12px;border-radius:4px;border:none;cursor:pointer"
        >
            + Agregar al carrito
        </button>
        <hr>
    </div>

    <PaginacionNav
        :meta="resultado.meta"
        @cambio-pagina="filtros.pagina = $event"
    />
</div>
</template>
