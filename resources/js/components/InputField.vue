<script setup>
defineProps({
    label: String,
    modelValue: [String, Number],
    error: String,
    type: {
        type: String,
        default: 'text'
    }
})

defineEmits(['update:modelValue'])
</script>

<template>
    <div style="margin-bottom:15px">
        <label>
            <strong>{{ label }}</strong>
        </label>

        <br>

        <input
            :type="type"
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            style="width:100%;padding:8px"
        >

        <div
            v-if="error"
            style="color:red;margin-top:5px"
        >
            {{ error }}
        </div>
    </div>
</template>