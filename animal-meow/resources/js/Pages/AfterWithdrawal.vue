<script setup>
import OneColumnPageHeader from "@/Components/OneColumnPageHeader.vue";
import OneColumnPageFooter from "@/Components/OneColumnPageFooter.vue";
import FlashSuccessMessages from "@/Components/FlashSuccessMessages.vue";
</script>

<template>

    <!-- 退会の通知 -->
    <FlashSuccessMessages />
        
    <!-- ヘッダー -->
    <OneColumnPageHeader />

    <article class="prose prose-sm sm:prose-base lg:prose-base max-w-5xl mx-auto bg-white border border-gray-200 rounded-xl shadow-md px-4 py-8 sm:px-8">

        <h1 class="text-center">Thank you!</h1>
        <p>
            この度はあにまるにゃ〜んをご利用頂き、ありがとうございました。僅かでもお役に立てたのであれば幸いです。また機会がありましたらご利用ください。
        </p>

    </article>

    <!-- フッター -->
    <OneColumnPageFooter />
        
</template>