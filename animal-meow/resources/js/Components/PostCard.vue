<script setup>
import { ref, reactive, onBeforeUnmount } from 'vue';
import { Inertia } from '@inertiajs/inertia'
import { usePage } from '@inertiajs/inertia-vue3'

const props = defineProps({
    post_id: {
        type: Number,
        default: 0
    },
    user_id: {
        type: Number,
        default: 0
    },
    user_name: {
        type: String,
        default: '投稿者名'
    },
    message: {
        type: String,
        default: '投稿メッセージ'
    },
    like_total_count: {
        type: Number,
        default: 0,
    },
    avatar_image_url: {
        type: String,
        default: 'http://abs.twimg.com/sticky/default_profile_images/default_profile.png'
    },
    post_created_at: {
        type: String,
        default: '23時間前'
    },
});

// ドロップダウンメニューを表示するかの変数
const isOpenDropDownMenu = ref(false);

// 投稿のいいね総数
const like_total_count_reactive = ref(props.like_total_count);

// 投稿時刻を加工する
const postCreatedAt = function(){
    const nowDate = new Date();
    const postDate = new Date(props.post_created_at.replace(/-/g,"/"));
    const diffSeconds = (nowDate - postDate) / 1000;

    // 投稿時刻が現在時刻の60秒前は秒表示
    if(diffSeconds < 60){
        return parseInt(diffSeconds) + '秒';
    }

    // 投稿時刻が現在時刻の60分前は分表示
    if(diffSeconds < 3600){
        return parseInt(diffSeconds / 60) + '分';
    }

    // 投稿時刻が現在時刻の24時間前は時間表示
    if(diffSeconds < 86400){
        return parseInt(diffSeconds / 3600) + '時間';
    }

    // 投稿時刻が現在時刻の365日前は月日表示 (※うるう年は考慮しない)
    if(diffSeconds < 31536000){
        return (postDate.getMonth() +1 ) + '月' + postDate.getDate() + '日';
    }

    // それら以外は年月日表示
    return postDate.getFullYear() + '年' + (postDate.getMonth() + 1) + '月' + postDate.getDate() + '日';
}

// ログインユーザーと投稿ユーザーが同一か判定する
const isSamePostUserAuthUser = function(){
    const authUser = usePage().props.value.auth.user;
    if(authUser == null){
        return false;
    }
    if(authUser.id != props.user_id){
        return false;
    }
    return true;
}

// ドロップダウンメニューを表示する
// ドロップダウンメニューは、メニュー以外の項目がクリックされた際に閉じるようにする
const openDropDownMenu = function(){
    isOpenDropDownMenu.value = true;
    document.addEventListener('click', closeDropDownMenu);
}

// ドロップダウンメニューを閉じる
const closeDropDownMenu = function(event){
    if(event.target.closest('.post-card-drop-down') != null){
        return;
    }
    isOpenDropDownMenu.value = false;
    document.removeEventListener('click', closeDropDownMenu);
}

// 投稿削除
const submitDeletePost = function(){
    Inertia.delete('/post/' + props.post_id);
}

// いいねを追加する
const addLikeCount = async function(){
    // ログインユーザーでない場合は終了
    if(usePage().props.value.auth.user  == null){
        return;
    }

    // いいね追加APIをcallする
    await axios.post(route('post.like.upsert', props.post_id))
               .then(response => like_total_count_reactive.value += 1)
               .catch(error => {
                    if(error.response){
                        if(error.response.status == 429){
                            alert('一定時間内にできるいいねの上限を超えました。しばらくお待ちください。');
                        }else{
                            alert('不明なエラーが発生しました。 status code : ' + error.response.status);
                        }
                    }else{
                        alert('不明なエラーが発生しました。');
                    }
                });
}

</script>

<style scoped>
.transition-dropdown-menu-enter-from,
.transition-dropdown-menu-leave-to
{
  opacity: 0;
  right: 1rem;
}
.transition-dropdown-menu-enter-to,
.transition-dropdown-menu-leave
{
  opacity: 1;
  right: 3rem;
}
.transition-dropdown-menu-enter-active{
  transition: all 0.1s;
}
.transition-dropdown-menu-leave-active{
  transition: all 0.05s;
}
</style>

<template>
    <!-- アバター画像 | その他 で横に分割 -->
    <div class="flex bg-white rounded-xl border border-gray-200 shadow-md mx-2 my-1 p-2 lg:mx-6">

        <!-- アバター画像 -->
        <img class="w-12 h-12 rounded-full object-cover" :src=avatar_image_url />
        
        <!-- ユーザー名・3点リーダーメニュー | 投稿文章・いいね数・投稿時間 で縦に分割 -->
        <div class="flex flex-col px-1 w-full">

            <!-- ユーザー名 | 3点リーダーメニュー で横に分割 -->
            <div class="flex">
                <span class="text-sm font-bold tracking-tight text-gray-900 dark:text-white">{{ user_name }}</span>
                <button v-if="isSamePostUserAuthUser()" @click="openDropDownMenu()" class="post-card-drop-down bg-[url('/images/three_point_leader_menu_icon.svg')] ml-auto w-4 h-4 hover:opacity-50 active:opacity-50" />
                <div v-if="isSamePostUserAuthUser()" class="post-card-drop-down relative">
                    <transition name="transition-dropdown-menu">
                        <div class="absolute z-5 top-1 right-12 w-32 bg-white rounded border border-gray-200 shadow-md" v-show="isOpenDropDownMenu">
                            <ul class="text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefault">
                                <li class="text-red-600 hover:bg-gray-100 active:bg-gray-100">
                                    <form @submit.prevent="submitDeletePost">
                                        <button type="submit" class="w-full text-left py-2 px-4">削除</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </transition>
                </div>
            </div>

            <!-- 投稿文章 -->
            <p class="text-sm text-gray-700">{{ message }}</p>

            <!-- いいね数 | 投稿時間 で横に分割 -->
            <div class="flex justify-start mt-1">
                <button v-bind:id="'like-button-'+post_id" class="bg-[url('/images/like_icon.svg')] hover:bg-[url('/images/like_icon_hover.svg')] active:bg-[url('/images/like_icon_hover.svg')] active:opacity-50 w-5 h-5 " @click="addLikeCount" />
                <label v-bind:for="'like-button-'+post_id" class="ml-1 text-sm hover:opacity-50 active:opacity-50">{{ like_total_count_reactive }}</label>
                <p class="ml-auto text-sm text-gray-400">{{ postCreatedAt() }}</p>
            </div>
        </div>
    </div>
</template>
