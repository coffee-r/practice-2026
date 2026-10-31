<script setup>
import { reactive, computed } from "vue"
import { useForm } from '@inertiajs/inertia-vue3'
import CustomButton from "@/Components/CustomButton.vue";
import LoginUserSpHeader from "@/Components/LoginUserSpHeader.vue";
import FixedPostButton from "@/Components/FixedPostButton.vue";
import LoginUserPcSideMenu from "@/Components/LoginUserPcSideMenu.vue";
import ValidationErrors from "@/Components/ValidationErrors.vue";


// ログアウトフォーム
const logoutForm = useForm();

// 退会フォーム
const withdrawalForm = useForm({
    confirmText: null,
})

// 退会フォームに入力しているか
const isFilledConfirmText = computed(() => withdrawalForm.confirmText == "退会する")

// ログアウト
const submitLogout = function(){
    logoutForm.post(route('logout'));
}

// 退会
const submitWithdrawal = function(){
    withdrawalForm.post(route('withdrawal'), withdrawalForm);
}

</script>

<template>
    <!-- スマホのヘッダーメニュー -->
    <LoginUserSpHeader />

    <!-- バリデーションエラー表示 -->
    <ValidationErrors />

    <!-- PCのみ2カラム構成のレイアウトにする -->
    <main class="lg:flex">

        <!-- PCのサイドメニュー -->
        <LoginUserPcSideMenu />

        <!-- その他 -->
        <article class="lg:w-full lg:h-auto lg:mt-0">

            <!-- 背景色表示用padding -->
            <div class="py-0.5 lg:px-0 lg:py-6">

                <!-- ご意見など -->
                <div class="bg-white rounded-xl border border-gray-200 shadow-md mx-2 mt-1 mb-4 px-4 py-4 lg:mx-6 lg:mt-1 lg:mb-6 lg:px-8 lg:py-8">
                    <h2 class="font-bold text-2xl lg:text-3xl">ご要望や不具合報告など</h2>

                    <p class="mt-4">
                        <a class="font-bold underline" href="https://twitter.com/intent/tweet?hashtags=あにまるにゃ〜ん要望" target="_blank">#あにまるにゃ〜ん要望</a> のタグをつけてツイートして頂けますと、当サイトの開発者の気まぐれで対応するかもしれません。<br/>
                        サイトが全く閲覧出来ないなどの致命的な問題が発生している際は、@plus_marumaru 宛てまでDM頂けますと幸いです。
                    </p>
                </div>
                
                <!-- 利用した技術や素材サイトなどの紹介 -->
                <!-- Thanks! -->
                <div class="bg-white rounded-xl border border-gray-200 shadow-md mx-2 my-4 px-4 py-4 lg:mx-6 lg:my-6 lg:px-8 lg:py-8">
                    <h2 class="font-bold text-2xl lg:text-3xl">開発のために主に利用や参考にさせて頂いたもの</h2>
            
                    <ul class="list-disc list-outside pl-5 mt-4">
                        <li>Twitter</li>
                        <li>Github</li>
                        <li>Fly.io</li>
                        <li>Docker</li>
                        <li>Laravel</li>
                        <li>PostgreSQL</li>
                        <li>Redis</li>
                        <li>Vite</li>
                        <li>Vue.js</li>
                        <li>Inertia.js</li>
                        <li>Tailwind CSS</li>
                        <li>Visual Studio Code</li>
                        <li>Figma</li>
                        <li>Inkscape</li>
                        <li>Draw.io</li>
                        <li>icooon-mono.com</li>
                        <li>kiyaku.jp</li>
                    </ul>
            
                    <p class="mt-4">
                        その他、世に広まっているたくさんの技術やサービスのおかげでこのサイトを開発することが出来ています。
                    </p>
                </div>

                <!-- ログアウトフォーム -->
                <div class="bg-white rounded-xl border border-gray-200 shadow-md mx-2 my-4 px-4 py-4 lg:mx-6 lg:my-6 lg:px-8 lg:py-8">
                    <h2 class="font-bold text-2xl lg:text-3xl">ログアウト</h2>
            
                    <form @submit.prevent="submitLogout" class="text-center m-4 lg:text-left">
                        <CustomButton type="submit" v-bind:disabled="logoutForm.processing">ログアウトする</CustomButton>
                    </form>
                </div>

                <!-- 退会フォーム -->
                <div class="bg-white rounded-xl border border-gray-200 shadow-md mx-2 my-4 px-4 py-4 lg:mx-6 lg:my-6 lg:px-8 lg:py-8">
                    <h2 class="font-bold text-2xl lg:text-3xl">退会</h2>
            
                    <ul class="list-disc list-outside pl-5 mt-4">
                        <li>当サイトからあなたのアカウントを削除します。</li>
                        <li>当サイトでのあなたの全ての投稿を削除します。</li>
                        <li>他のユーザー投稿にしたいいねの数は削除しません。</li>
                    </ul>
            
                    <form @submit.prevent="submitWithdrawal" class="text-center m-4 lg:text-left">
                        <input type="text" v-model="withdrawalForm.confirmText" class="appearance-none border w-full py-2 px-4 bg-white rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"  placeholder="「退会する」と入力してボタンを押す">
                        <CustomButton class="mt-6" type="submit"  v-bind:disabled="withdrawalForm.processing || !isFilledConfirmText" color='bg-red-600' hoverColor="hover:bg-red-400" activeColor="active:bg-red-400">退会する</CustomButton>
                    </form>
                </div>

            </div>

        </article>
    </main>
</template>
