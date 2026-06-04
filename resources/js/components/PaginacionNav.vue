<template>
  <div v-if="meta">
    <button @click="$emit('cambio-pagina', meta.current_page - 1)" :disabled="meta.current_page === 1">
      Anterior
    </button>

    <span>
      Página {{ meta.current_page }} de {{ meta.last_page }}
    </span>

    <button @click="$emit('cambio-pagina', meta.current_page + 1)" :disabled="meta.current_page === meta.last_page">
      Siguiente
    </button>
  </div>
</template>

<script setup>
defineProps(['meta'])
defineEmits(['cambio-pagina'])
</script>