<template>
    <div v-if="$page.props.flash.successMessages">
        <ul class="list-disc list-outside pl-8 p-4 text-sm text-green-700 bg-green-100 dark:bg-green-200 dark:text-green-800" role="alert">
            <li v-for="message in $page.props.flash.successMessages" :key="message" >
                <span v-html="message" class="font-bold"></span> 
            </li>
        </ul>
    </div>
</template>
