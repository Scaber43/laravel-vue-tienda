<script setup>
import { ref } from 'vue'
import axios from 'axios'

import { useForm, useField } from 'vee-validate'

import { productoSchema } from '../schemas/productoSchema'

import InputField from '../components/InputField.vue'

const erroresServidor = ref({})

const { handleSubmit, errors, resetForm } = useForm({
    validationSchema: productoSchema
})

const { value: nombre } = useField('nombre')
const { value: descripcion } = useField('descripcion')
const { value: precio } = useField('precio')
const { value: stock } = useField('stock')

const imagen = ref(null)

const subirImagen = (e) => {
    imagen.value = e.target.files[0]
}

const onSubmit = handleSubmit(async (values) => {

    try {

        const formData = new FormData()

        formData.append('nombre', values.nombre)
        formData.append('descripcion', values.descripcion ?? '')
        formData.append('precio', values.precio)
        formData.append('stock', values.stock)

        if (imagen.value) {
            formData.append('imagen', imagen.value)
        }

        await axios.post(
            '/api/productos',
            formData,
            {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }
        )

        alert('Producto guardado')

        erroresServidor.value = {}

        resetForm()

    } catch (e) {

        if (e.response?.status === 422) {

            erroresServidor.value =
                e.response.data.errors
        }
    }
})
</script>

<template>

<div style="padding:20px">

    <h1>Nuevo Producto</h1>

    <form @submit.prevent="onSubmit">

        <InputField
            label="Nombre"
            v-model="nombre"
            :error="errors.nombre || erroresServidor.nombre?.[0]"
        />

        <InputField
            label="Descripción"
            v-model="descripcion"
            :error="errors.descripcion || erroresServidor.descripcion?.[0]"
        />

        <InputField
            label="Precio"
            type="number"
            v-model="precio"
            :error="errors.precio || erroresServidor.precio?.[0]"
        />

        <InputField
            label="Stock"
            type="number"
            v-model="stock"
            :error="errors.stock || erroresServidor.stock?.[0]"
        />

        <div style="margin-bottom:15px">

            <label>Imagen</label>

            <input
                type="file"
                @change="subirImagen"
            >

            <div
                v-if="erroresServidor.imagen"
                style="color:red"
            >
                {{ erroresServidor.imagen[0] }}
            </div>

        </div>

        <button type="submit">
            Guardar
        </button>

    </form>

</div>

</template>
