<script setup>
import CustomButton from "@/Components/CustomButton.vue";
import { ref } from "vue"
import { Link } from "@inertiajs/inertia-vue3";

defineProps({
    posts: Array,
});

const isSelectedUrlPath = function(path){
    if(location.pathname == path){
        return true
    }
}
</script>

<!-- PCのサイドメニュー -->
<template>
    <aside class="hidden bg-white lg:flex lg:flex-col lg:block lg:sticky lg:h-screen lg:top-0 lg:w-1/2">
        <header class="flex lg:flex-none">
            <Link class="flex-none hover:opacity-50 active:opacity-50" :href="route('home')"><img class="mx-6 my-6 w-10" src="/images/logo.svg"></Link>
        </header>

        <ul class="lg:flex-1 lg:text-center lg:mt-24 lg:flex lg:flex-col lg:gap-8">
            <li class="text-3xl" v-bind:class="{'font-bold':isSelectedUrlPath('/home') }"><Link class="hover:text-gray-500 active:text-gray-500" :href="route('home')">ホーム</Link></li>
            <li class="text-3xl" v-bind:class="{'font-bold':isSelectedUrlPath('/me') }"><Link class="hover:text-gray-500 active:text-gray-500" :href="route('me')">自分の投稿</Link></li>
            <li class="text-3xl" v-bind:class="{'font-bold':isSelectedUrlPath('/others') }"><Link class="hover:text-gray-500 active:text-gray-500" :href="route('others')">その他</Link></li>
            <li><Link :href="route('post.create')"><CustomButton>鳴く</CustomButton></Link></li>
        </ul>

        <footer class="lg:flex-none mt-16 mb-2">
            <ul class="flex justify-around gap-2">
                <li><Link class="hover:opacity-50 active:opacity-50" :href="route('terms')">利用規約</Link></li>
                <li><Link class="hover:opacity-50 active:opacity-50" :href="route('privacy')">プライバシーポリシー</Link></li>
                <li><a class="hover:opacity-50 active:opacity-50" href="https://twitter.com/plus_marumaru" target="_blank">&copy; 2022 coffee-r</a></li>
            </ul>
        </footer>
    </aside>
</template>