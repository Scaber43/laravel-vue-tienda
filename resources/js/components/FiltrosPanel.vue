<template>
  <div>
    <input v-model="filtros.busqueda" placeholder="Buscar..." />

    <select v-model="filtros.categoria_id">
      <option value="">Todas</option>
      <option v-for="c in categorias" :key="c.id" :value="c.id">
        {{ c.nombre }}
      </option>
    </select>

    <input type="number" v-model="filtros.precio_min" placeholder="Min" />
    <input type="number" v-model="filtros.precio_max" placeholder="Max" />
  </div>
</template>

<script setup>
defineProps(['filtros', 'categorias'])
</script>