<script setup>
import { Link } from '@inertiajs/inertia-vue3';
</script>

<!-- スマホ用の投稿ボタン -->
<template>
    <Link :href="route('post.create')">
        <button class="fixed z-5 bottom-6 right-6 hover:opacity-50 active:opacity-50 w-14 h-14 lg:hidden"><img src="/images/post_add_icon.svg"></button>
    </Link>
</template>
