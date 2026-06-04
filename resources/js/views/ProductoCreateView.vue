<script setup>
import { ref } from 'vue'
import axios from 'axios'

import { useForm, useField } from 'vee-validate'
import { productoSchema } from '../schemas/productoSchema'

const erroresServidor = ref({})

const { handleSubmit, errors, resetForm } = useForm({
    validationSchema: productoSchema
})

const { value: nombre } = useField('nombre')
const { value: descripcion } = useField('descripcion')
const { value: precio } = useField('precio')
const { value: stock } = useField('stock')

const onSubmit = handleSubmit(async (values) => {

    try {

        await axios.post('/api/productos', values)

        alert('Producto guardado correctamente')

        erroresServidor.value = {}

        resetForm()

    } catch (e) {

        if (e.response?.status === 422) {
            erroresServidor.value = e.response.data.errors
        }

        console.error(e)
    }
})
</script>

<template>

<div class="page">

    <div class="card">

        <div class="header">
            <h1>Nuevo Producto</h1>
            <p>Registrar un nuevo producto en el catálogo</p>
        </div>

        <form @submit.prevent="onSubmit">

            <div class="campo">

                <label>Nombre del producto</label>

                <input
                    v-model="nombre"
                    type="text"
                    placeholder="Ej. Sartén Antiadherente"
                >

                <span
                    class="error"
                    v-if="errors.nombre"
                >
                    {{ errors.nombre }}
                </span>

                <span
                    class="error"
                    v-if="erroresServidor.nombre"
                >
                    {{ erroresServidor.nombre[0] }}
                </span>

            </div>

            <div class="campo">

                <label>Descripción</label>

                <textarea
                    v-model="descripcion"
                    placeholder="Describe el producto..."
                ></textarea>

                <span
                    class="error"
                    v-if="erroresServidor.descripcion"
                >
                    {{ erroresServidor.descripcion[0] }}
                </span>

            </div>

            <div class="grid">

                <div class="campo">

                    <label>Precio</label>

                    <input
                        type="number"
                        step="0.01"
                        v-model="precio"
                        placeholder="0.00"
                    >

                    <span
                        class="error"
                        v-if="errors.precio"
                    >
                        {{ errors.precio }}
                    </span>

                    <span
                        class="error"
                        v-if="erroresServidor.precio"
                    >
                        {{ erroresServidor.precio[0] }}
                    </span>

                </div>

                <div class="campo">

                    <label>Stock</label>

                    <input
                        type="number"
                        v-model="stock"
                        placeholder="0"
                    >

                    <span
                        class="error"
                        v-if="errors.stock"
                    >
                        {{ errors.stock }}
                    </span>

                    <span
                        class="error"
                        v-if="erroresServidor.stock"
                    >
                        {{ erroresServidor.stock[0] }}
                    </span>

                </div>

            </div>

            <button
                type="submit"
                class="btn-guardar"
            >
                Guardar Producto
            </button>

        </form>

    </div>

</div>

</template>

<style scoped>

.page{
    min-height:100vh;
    background:#f4f6f9;
    display:flex;
    justify-content:center;
    align-items:center;
    padding:40px 20px;
}

.card{
    width:100%;
    max-width:800px;
    background:white;
    border-radius:16px;
    padding:35px;
    box-shadow:0 10px 30px rgba(0,0,0,.08);
}

.header{
    margin-bottom:30px;
}

.header h1{
    margin:0;
    font-size:32px;
    color:#1f2937;
}

.header p{
    margin-top:8px;
    color:#6b7280;
}

.campo{
    margin-bottom:22px;
}

label{
    display:block;
    margin-bottom:8px;
    font-weight:600;
    color:#374151;
}

input,
textarea{
    width:100%;
    padding:12px 14px;
    border:1px solid #d1d5db;
    border-radius:10px;
    font-size:15px;
    transition:.2s;
    box-sizing:border-box;
}

input:focus,
textarea:focus{
    outline:none;
    border-color:#2563eb;
    box-shadow:0 0 0 3px rgba(37,99,235,.15);
}

textarea{
    min-height:120px;
    resize:vertical;
}

.grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:20px;
}

.error{
    display:block;
    margin-top:6px;
    color:#dc2626;
    font-size:14px;
}

.btn-guardar{
    width:100%;
    padding:14px;
    border:none;
    border-radius:10px;
    background:#2563eb;
    color:white;
    font-size:16px;
    font-weight:600;
    cursor:pointer;
    transition:.2s;
}

.btn-guardar:hover{
    transform:translateY(-2px);
}

@media (max-width:768px){

    .grid{
        grid-template-columns:1fr;
    }

    .card{
        padding:25px;
    }

    .header h1{
        font-size:26px;
    }
}

</style>