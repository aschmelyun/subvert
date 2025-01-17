<script setup>
import { ref } from 'vue';

defineProps({
    color: {
        type: String,
        required: false,
        default: 'blue',
    },
    label: {
        type: String,
        required: false,
    },
});

const wrapperColorClasses = {
    blue: 'checked:border-blue-200 checked:text-blue-100 checked:before:bg-blue-600 focus:ring-blue-600 focus:checked:border-blue-200 dark:checked:border-blue-800 dark:checked:bg-blue-800/30 dark:checked:before:bg-blue-500',
    red: 'checked:border-red-200 checked:text-red-100 checked:before:bg-red-600 focus:ring-red-600 focus:checked:border-red-200 dark:checked:border-red-800 dark:checked:bg-red-800/30 dark:checked:before:bg-red-500',
    green: 'checked:border-green-200 checked:text-green-100 checked:before:bg-green-600 focus:ring-green-600 focus:checked:border-green-200 dark:checked:border-green-800 dark:checked:bg-green-800/30 dark:checked:before:bg-green-500',
    yellow: 'checked:border-yellow-200 checked:text-yellow-100 checked:before:bg-yellow-600 focus:ring-yellow-600 focus:checked:border-yellow-200 dark:checked:border-yellow-800 dark:checked:bg-yellow-800/30 dark:checked:before:bg-yellow-500',
    purple: 'checked:border-purple-200 checked:text-purple-100 checked:before:bg-purple-600 focus:ring-purple-600 focus:checked:border-purple-200 dark:checked:border-purple-800 dark:checked:bg-purple-800/30 dark:checked:before:bg-purple-500',
    indigo: 'checked:border-indigo-200 checked:text-indigo-100 checked:before:bg-indigo-600 focus:ring-indigo-600 focus:checked:border-indigo-200 dark:checked:border-indigo-800 dark:checked:bg-indigo-800/30 dark:checked:before:bg-indigo-500',
    pink: 'checked:border-pink-200 checked:text-pink-100 checked:before:bg-pink-600 focus:ring-pink-600 focus:checked:border-pink-200 dark:checked:border-pink-800 dark:checked:bg-pink-800/30 dark:checked:before:bg-pink-500',
    gray: 'checked:border-gray-200 checked:text-gray-100 checked:before:bg-gray-600 focus:ring-gray-600 focus:checked:border-gray-200 dark:checked:border-gray-800 dark:checked:bg-gray-800/30 dark:checked:before:bg-gray-500',
};

const innerColorClasses = {
    blue: 'peer-checked:text-blue-600',
    red: 'peer-checked:text-red-600',
    green: 'peer-checked:text-green-600',
    yellow: 'peer-checked:text-yellow-600',
    purple: 'peer-checked:text-purple-600',
    indigo: 'peer-checked:text-indigo-600',
    pink: 'peer-checked:text-pink-600',
    gray: 'peer-checked:text-gray-600',
};

const checked = ref(false);
</script>

<template>
    <div class="flex items-center justify-between">
        <span class="text-sm font-medium text-gray-600">{{ label }}</span>
        <div class="relative inline-block">
            <input
                type="checkbox"
                id="hs-default-switch-soft-with-icons"
                :checked="checked"
                @change="checked = !checked"
                :class="wrapperColorClasses[color]"
                class="peer relative h-7 w-[3.25rem] cursor-pointer rounded-full border border-gray-200 bg-gray-100 p-px text-transparent transition-colors duration-200 ease-in-out before:inline-block before:size-6 before:translate-x-0 before:transform before:rounded-full before:bg-white before:ring-0 before:transition before:duration-200 before:ease-in-out checked:bg-none checked:before:translate-x-full disabled:pointer-events-none disabled:opacity-50 dark:border-neutral-700 dark:bg-neutral-800 dark:before:bg-neutral-400 dark:focus:ring-offset-gray-600"
            />
            <label for="hs-default-switch-soft-with-icons" class="sr-only"
                >switch</label
            >
            <span
                :class="innerColorClasses[color]"
                class="pointer-events-none absolute start-0.5 top-0.5 flex size-6 items-center justify-center text-gray-500 transition-colors duration-200 ease-in-out dark:text-neutral-500"
            >
            </span>
            <span
                class="pointer-events-none absolute end-0.5 top-0.5 flex size-6 items-center justify-center transition-colors duration-200 ease-in-out peer-checked:text-white dark:text-neutral-500"
            >
            </span>
        </div>
    </div>
</template>
