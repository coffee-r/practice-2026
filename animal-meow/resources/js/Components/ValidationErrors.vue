<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/inertia-vue3';

const errors = computed(() => usePage().props.value.errors);

const hasErrors = computed(() => Object.keys(errors.value).length > 0);
</script>

<template>
    <div v-if="hasErrors">
        <ul class="list-disc list-inside p-4 text-sm text-red-700 bg-red-100 dark:bg-red-200 dark:text-red-800" role="alert">
            <li v-for="(error, key) in errors" :key="key" >
                <span class="font-bold">{{ error }}</span> 
            </li>
        </ul>
    </div>
</template>
