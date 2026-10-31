<script setup>
import { Link } from "@inertiajs/inertia-vue3";
import CustomButton from "@/Components/CustomButton.vue";
import PostCard from "@/Components/PostCard.vue";
import PostNoneMessage from "@/Components/PostNoneMessage.vue";
import FlashSuccessMessages from "@/Components/FlashSuccessMessages.vue";
import FlashFailMessages from "@/Components/FlashFailMessages.vue";


defineProps({
    posts: Array,
});
</script>

<template>
    <!-- ログアウトの通知 -->
    <FlashSuccessMessages />

    <!-- ログイン失敗の通知 -->
    <FlashFailMessages />

    <!-- PCのみ2カラム構成のレイアウトにする -->
    <main class="lg:flex">

        <!-- サイト紹介セクション -->
        <section class="bg-white lg:flex lg:flex-col lg:w-full lg:sticky lg:h-screen lg:top-0">

            <!-- ヘッダー -->
            <header class="flex lg:flex-none">
                <Link class="flex-none hover:opacity-50 active:opacity-50" :href="route('index')"><img class="w-8 my-4 mx-4 sm:w-9 sm:my-5 sm:mx-5 lg:w-10 lg:my-6 lg:mx-6" src="/images/logo.svg"></Link>
            </header>

            <div class="lg:flex-1">

                <!-- サイトタイトル -->
                <h1 class="text-center font-bold text-6xl mt-8 lg:text-7xl lg:leading-tight 2xl:mt-16">
                    あにまる<br />にゃ〜ん
                </h1>

                <!-- サイトの説明 -->
                <p class="mt-8 text-center font-bold text-xl lg:text-2xl">
                    人間社会に疲れた人が作った<br />
                    動物の言葉でしか呟けないSNS<br />
                    (ベータ版)
                </p>

                <!-- ログインボタン -->
                <div class="text-center py-12">
                    <a :href="route('login.twitter')">
                        <CustomButton>Twitterでログイン</CustomButton>
                    </a>
                    <div class="mt-3 text-xs">
                        <Link class="hover:text-gray-500 active:text-gray-500" :href="route('terms')">利用規約</Link>・<Link class="hover:text-gray-500 active:text-gray-500" :href="route('privacy')">プライバシーポリシー</Link>に<br/>
                        同意の上でご利用ください
                    </div>
                </div>
            </div>

            <!-- PC用フッター -->
            <footer class="hidden my-10 lg:block lg:flex-none">
                <ul class="px-20 flex justify-around">
                    <Link class="hover:text-gray-500 active:text-gray-500" :href="route('terms')"><li>利用規約</li></Link>
                    <Link class="hover:text-gray-500 active:text-gray-500" :href="route('privacy')"><li>プライバシーポリシー</li></Link>
                    <a class="hover:text-gray-500 active:text-gray-500" href="https://twitter.com/plus_marumaru" target="_blank"><li>&copy; 2022 coffee-r</li></a>
                </ul>
            </footer>

        </section>

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

    <!-- SP用フッター -->
    <footer class="bg-white lg:hidden">
       <ul class="py-4 flex flex-col gap-y-4">
            <Link class="hover:text-gray-500  active:text-gray-500" :href="route('terms')"><li class="grid justify-items-center">利用規約</li></Link>
            <Link class="hover:text-gray-500 active:text-gray-500" :href="route('privacy')"><li class="grid justify-items-center">プライバシーポリシー</li></Link>
            <a class="hover:text-gray-500 active:text-gray-500" href="https://twitter.com/plus_marumaru" target="_blank"><li class="grid justify-items-center">&copy; 2022 coffee-r</li></a>
        </ul>
    </footer>

</template>
