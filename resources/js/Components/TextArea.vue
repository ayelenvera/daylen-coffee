<!-- resources/js/Components/TextArea.vue -->
<template>
    <textarea
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      :class="[
        'w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500',
        { 'border-red-500': error }
      ]"
      :placeholder="placeholder"
      :rows="rows"
    ></textarea>
    <p v-if="error" class="text-red-500 text-sm mt-1">{{ error }}</p>
  </template>
  
  <script setup>
  defineProps({
    modelValue: String,
    placeholder: {
      type: String,
      default: ''
    },
    rows: {
      type: Number,
      default: 3
    },
    error: String
  })
  
  defineEmits(['update:modelValue'])
  </script>