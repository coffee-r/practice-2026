<script setup>
import { Link } from '@inertiajs/inertia-vue3';
</script>

<template>
    <header class="flex justify-between">
        <Link :href="route('index')"><img class="hover:opacity-50 active:opacity-50 w-8 my-4 mx-4 sm:w-9 sm:my-5 sm:mx-5 lg:w-10 lg:my-6 lg:mx-6" src="/images/logo.svg"></Link>
    </header>
</template>
