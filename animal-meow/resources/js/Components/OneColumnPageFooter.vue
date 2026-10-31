<script setup>
import { Link } from '@inertiajs/inertia-vue3';
</script>

<template>
    <footer>
        <ul class="py-4 flex flex-col gap-y-4 lg:py-8 lg:flex-row lg:justify-center lg:gap-x-24">
            <Link class="hover:opacity-50 active:opacity-50" :href="route('terms')"><li class="grid justify-items-center ">利用規約</li></Link>
            <Link class="hover:opacity-50 active:opacity-50" :href="route('privacy')"><li class="grid justify-items-center">プライバシーポリシー</li></Link>
            <a class="hover:opacity-50 active:opacity-50" href="https://twitter.com/plus_marumaru" target="_blank"><li class="grid justify-items-center">&copy; 2022 coffee-r</li></a>
        </ul>
    </footer>
</template>
