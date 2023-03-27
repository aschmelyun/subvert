<template>
    <div class="mt-12 text-center">
        <div class="flex justify-between max-w-xl mx-auto mb-4 relative">
            <Dot :status="determineStatus(1)" />
            <div :class="lineClass(1)"></div>
            <Dot :status="determineStatus(2)" />
            <div :class="lineClass(2)"></div>
            <Dot :status="determineStatus(3)" />
            <div :class="lineClass(3)"></div>
            <Dot :status="determineStatus(4)" />
            <div :class="lineClass(4)"></div>
            <Dot :status="determineStatus(5)" />
        </div>
        <h3 class="text-lg text-gray-500">
            <span v-if="step !== 98">Step {{ props.step }}: </span>{{ props.message }}
        </h3>
    </div>
</template>
<script setup>
import { defineProps } from 'vue'
import Dot from './Steppers/Dot.vue'
const props = defineProps({
    step: {
        type: Number,
        required: true
    },
    message: {
        type: String,
        required: true
    }
})

const determineStatus = (step) => {
    if (props.step > step) {
        return 'complete'
    }

    if (props.step === step) {
        return 'working'
    }

    return 'inactive'
}

const lineClass = (step) => {
    let classes = 'w-1/4 h-0.5 absolute top-1/2 -z-10'

    switch(step) {
        case 1:
            break
        case 2:
            classes += ' left-1/4'
            break
        case 3:
            classes += ' left-1/2'
            break
        case 4:
            classes += ' left-3/4'
            break
    }

    if (props.step > step) {
        classes += ' bg-purple-500'
    } else {
        classes += ' bg-gray-200'
    }

    return classes
}
</script>