<script setup>
import CustomButton from "@/Components/CustomButton.vue";
import { ref } from "vue"
import { Link } from "@inertiajs/inertia-vue3";

defineProps({
    posts: Array,
});

const isOpenModalMenu = ref(false);

const showModalMenu = function(value){
    isOpenModalMenu.value = value;
}

const isSelectedUrlPath = function(path){
    if(location.pathname == path){
        return true
    }
}
</script>

<style scoped>
.transition-opacity-enter-from,
.transition-opacity-leave-to
{
  opacity: 0;
}
.transition-opacity-enter-to,
.transition-opacity-leave
{
  opacity: 1;
}
.transition-opacity-enter-active{
  transition: opacity 0.075s;
}
.transition-opacity-leave-active{
  transition: opacity 0.05s;
}
</style>

<!-- スマホのヘッダーメニュー -->
<template>
    <header>
        <!-- アイコン -->
        <div class="flex justify-between bg-white sticky top-0 lg:hidden">
            <Link :href="route('home')"><img class="hover:opacity-50 active:opacity-50 w-8 my-4 mx-4 sm:w-9 sm:my-5 sm:ml-5 lg:w-10 lg:my-6 lg:ml-6" src="/images/logo.svg"></Link>
            <img @click="showModalMenu(true)" class="hover:opacity-50 active:opacity-50 w-8 my-4 mx-4 sm:w-9 sm:my-5 sm:mr-5 lg:w-10 lg:my-6 lg:mr-6" src="/images/hamburger_menu_icon.svg">
        </div>

        <!-- モーダルのナビゲーション -->
        <transition name="transition-opacity">
            <nav class="overflow-y-auto overflow-x-hidden fixed z-10 top-0 left-0 w-full h-screen bg-gray-900/25 lg:hidden" @click.self="showModalMenu(false)" v-show="isOpenModalMenu">
                <div class="text-center bg-white h-auto p-4 shadow-xl rounded-xl mt-10 mx-6">
                    <ul class="mt-12 flex flex-col gap-8">
                        <Link class="hover:text-gray-500 active:text-gray-500" :href="route('home')"><li class="text-2xl" v-bind:class="{'font-bold':isSelectedUrlPath('/home') }">ホーム</li></Link>
                        <Link class="hover:text-gray-500 active:text-gray-500" :href="route('me')"><li class="text-2xl" v-bind:class="{'font-bold':isSelectedUrlPath('/me') }">自分の投稿</li></Link>
                        <Link class="hover:text-gray-500 active:text-gray-500" :href="route('others')"><li class="text-2xl" v-bind:class="{'font-bold':isSelectedUrlPath('/others') }">その他</li></Link>
                        <li><Link :href="route('post.create')"><CustomButton>鳴く</CustomButton></Link></li>
                    </ul>

                    <footer class="mt-16 mb-2">
                        <ul class="flex flex-col justify-around gap-2">
                            <li><Link class="hover:text-gray-500 active:text-gray-500" :href="route('terms')">利用規約</Link></li>
                            <li><Link class="hover:text-gray-500 active:text-gray-500" :href="route('privacy')">プライバシーポリシー</Link></li>
                            <li><a class="hover:text-gray-500 active:text-gray-500" href="https://twitter.com/plus_marumaru" target="_blank">&copy; 2022 coffee-r</a></li>
                        </ul>
                    </footer>
                </div>
            </nav>
        </transition>
    </header>
</template>