<script setup>
import { Link } from "@inertiajs/inertia-vue3";
import PostCard from "@/Components/PostCard.vue";
import PostNoneMessage from "@/Components/PostNoneMessage.vue";
import LoginUserSpHeader from "@/Components/LoginUserSpHeader.vue";
import FixedPostButton from "@/Components/FixedPostButton.vue";
import LoginUserPcSideMenu from "@/Components/LoginUserPcSideMenu.vue";
import FlashSuccessMessages from "@/Components/FlashSuccessMessages.vue";
import FlashFailMessages from "@/Components/FlashFailMessages.vue";

defineProps({
    posts: Array,
});
</script>

<template>
    <!-- スマホ・タブレットのヘッダーメニュー -->
    <LoginUserSpHeader />

    <!-- スマホ・タブレットの投稿画面リンク -->
    <FixedPostButton />

    <!-- 投稿やログインの通知 -->
    <FlashSuccessMessages />
    <FlashFailMessages />

    <!-- PCのみ2カラム構成のレイアウトにする -->
    <main class="lg:flex">

        <!-- PCのサイドメニュー -->
        <LoginUserPcSideMenu />

        <!-- タイムライン -->
        <article class="lg:w-full lg:h-auto lg:mt-0">

            <!-- 投稿がない時のメッセージ -->
            <PostNoneMessage v-if="posts.length == 0" />

            <!-- 背景色表示用padding -->
            <div v-if="posts.length != 0" class="py-0.5 lg:px-0 lg:py-6">

                <!-- 投稿 -->
                <PostCard
                    v-for="post in posts"
                    v-bind:key="post.post_id"
                    v-bind:post_id="post.post_id"
                    v-bind:user_id="post.user_id"
                    v-bind:user_name="post.user_name"
                    v-bind:message="post.message"
                    v-bind:like_total_count="post.like_total_count"
                    v-bind:avatar_image_url="post.avatar_image_url"
                    v-bind:post_created_at="post.post_created_at"
                ></PostCard>

            </div>

        </article>
    </main>
</template>
