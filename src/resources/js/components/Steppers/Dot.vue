<template>
    <div :class="classes">
        <span v-if="props.status === 'working'" class="animate-ping absolute inline-flex top-3.5 left-3.5 h-4 w-4 rounded-full bg-purple-500 opacity-75"></span>
        <span v-if="props.status === 'working'" class="absolute inline-block top-3.5 left-3.5 h-4 w-4 rounded-full bg-purple-500"></span>
        <svg v-if="props.status === 'complete'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="text-white absolute inline-block top-2.5 left-2.5 w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
        </svg>
    </div>
</template>
<script setup>
import { computed } from 'vue'
const props = defineProps({
    status: {
        type: String,
        required: true
    }
})

const classes = computed(() => {
    let classes = 'w-12 h-12 border-2 rounded-full relative'

    switch (props.status) {
        case 'complete':
            classes += ' bg-purple-500 border-purple-500'
            break
        case 'working':
            classes += ' bg-white border-purple-500'
            break
        case 'inactive':
            classes += ' bg-white border-gray-200'
            break
    }

    return classes
})
</script>