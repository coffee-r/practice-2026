<script setup>

const props = defineProps({
    words: Array
});

const emits = defineEmits([
    "addWordNotification",
    "addSpaceNotification",
    "removeWordNotification"
])

const addWord = function(word){
    emits("addWordNotification", word);
}

const addSpace = function(){
    emits("addSpaceNotification", ' ');
}

const removeWord = function(){
    emits("removeWordNotification");
}
</script>

<script>
import { defineComponent } from "@vue/runtime-core";
export default defineComponent({
    inheritAttrs: false,
});
</script>



<!-- 動物言葉のIME -->
<template>
    <div class="py-2 flex flex-wrap justify-center lg:justify-start gap-3">
        <button v-for="word in words" :key="word" @click="addWord(word)" type="button" class="bg-white active:bg-gray-300 font-bold border-2 border-gray-900 rounded-full w-10 h-10">{{ word }}</button>
    </div>

    <div class="flex justify-start gap-2">
        <button v-if="words.length != 0" @click="addSpace()" type="button" class="mt-4 bg-white active:bg-gray-300 font-bold border-2 border-gray-900 px-4 py-2 rounded-full">スペース</button>
        <button v-if="words.length != 0" @click="removeWord()" type="button" class="mt-4 bg-white active:bg-gray-300 font-bold border-2 border-gray-900 px-4 py-2 rounded-full">一つ戻る</button>
    </div>
</template>