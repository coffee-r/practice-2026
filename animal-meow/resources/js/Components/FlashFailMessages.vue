<template>
    <div v-if="$page.props.flash.failMessages">
        <ul class="list-disc list-outside pl-8 p-4 text-sm text-red-700 bg-red-100 dark:bg-red-200 dark:text-red-800" role="alert">
            <li v-for="message in $page.props.flash.failMessages" :key="message" >
                <span v-html="message" class="font-bold"></span> 
            </li>
        </ul>
    </div>
</template>
