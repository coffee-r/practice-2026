<script setup>
const props = defineProps({
    type: {
        type: String,
        default: 'button',
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    color: {
        type: String,
        default: 'bg-gray-900'
    },
    hoverColor: {
        type: String,
        default: 'hover:bg-gray-500'
    },
    activeColor: {
        type: String,
        default: 'active:bg-gray-500'
    },
});

const buttonClass = props.color + " " + props.hoverColor + " " + props.activeColor + " disabled:opacity-50 text-white font-bold py-3 px-9 rounded-full text-xl lg:py-4 lg:px-12 lg:text-2xl"

</script>

<template>
    <button :type="type" :disabled="disabled" :class="buttonClass">
        <slot />
    </button>
</template>
