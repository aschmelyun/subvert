<script setup>
import App from '@/Layouts/App.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    workflow: {
        type: Object,
        required: true,
    },
    errors: {
        type: Object,
        required: false,
    },
});

const steps = ref(props.workflow.steps);

const addStep = () => {
    steps.value.push({ id: steps.value.length, text: '' });
};

const deleteStep = (id) => {
    steps.value = steps.value.filter((step) => step.id !== id);
};

const form = useForm({
    name: props.workflow.name,
    description: props.workflow.description,
    color: props.workflow.color,
});

const saveWorkflow = () => {
    // Implement the save functionality here
    console.log('Saving workflow...');
};
</script>

<template>
    <Head :title="workflow.name" />
    <App>
        <div class="flex gap-8">
            <!-- Workflow Details Column (1/3 width) -->
            <div class="w-1/3">
                <div class="rounded-lg border border-slate-200 bg-white p-6">
                    <h2
                        class="mb-4 text-2xl font-bold"
                        :style="{ color: workflow.color }"
                    >
                        {{ workflow.name }}
                    </h2>
                    <div class="mb-4">
                        <label
                            class="mb-1 block text-sm font-medium text-gray-700"
                            >Description</label
                        >
                        <p class="text-gray-600">{{ workflow.description }}</p>
                    </div>
                    <div class="mb-4">
                        <label
                            class="mb-1 block text-sm font-medium text-gray-700"
                            >Highlight Color</label
                        >
                        <div class="flex items-center">
                            <div
                                class="h-6 w-6 rounded-full"
                                :style="{ backgroundColor: workflow.color }"
                            ></div>
                            <span class="ml-2 text-gray-600">{{
                                workflow.color
                            }}</span>
                        </div>
                    </div>
                    <div class="mb-6">
                        <label
                            class="mb-1 block text-sm font-medium text-gray-700"
                            >Usage</label
                        >
                        <p class="text-gray-600">
                            Used 5 times in the last week
                        </p>
                    </div>
                    <button
                        @click="saveWorkflow"
                        type="submit"
                        class="inline-flex items-center gap-x-1 rounded-lg border border-violet-700 bg-violet-600 px-3 py-1.5 text-sm font-medium text-white shadow-sm hover:bg-violet-700 focus:bg-violet-700 focus:outline-none disabled:pointer-events-none disabled:opacity-50 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            class="mr-1 h-4 w-4 text-violet-300"
                            fill="none"
                            stroke="currentColor"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                        >
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                            <polyline points="22 4 12 14.01 9 11.01" />
                        </svg>

                        Save Workflow
                    </button>
                </div>
            </div>

            <!-- Workflow Steps Column (2/3 width) -->
            <div class="w-2/3">
                <div class="space-y-10">
                    <div class="relative">
                        <div class="rounded-lg border border-slate-200 p-6">
                            <p class="text-sm font-medium">Media Transcript</p>
                            <div class="mt-2 flex flex-col gap-2">
                                <div
                                    class="h-3 w-10/12 rounded-full bg-gradient-to-r from-slate-300 to-slate-200"
                                ></div>
                                <div
                                    class="h-3 w-7/12 rounded-full bg-gradient-to-r from-slate-300 to-slate-200"
                                ></div>
                                <div
                                    class="h-3 w-9/12 rounded-full bg-gradient-to-r from-slate-300 to-slate-200"
                                ></div>
                            </div>
                        </div>
                        <div
                            v-if="steps.length"
                            class="absolute left-1/2 -translate-x-1/2 py-1"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-8 w-8 text-gray-300"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l4.293-4.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </div>
                    </div>
                    <div
                        v-for="(step, index) in steps"
                        :key="step.id"
                        class="relative"
                    >
                        <div
                            class="rounded-lg border border-slate-200 bg-white p-6"
                            :class="{ 'bg-gray-100': step.isMediaTranscript }"
                        >
                            <label
                                :for="'step-' + step.id"
                                class="mb-2 block text-sm font-medium text-gray-700"
                            >
                                {{ `Step ${index + 1}` }}
                            </label>
                            <textarea
                                v-if="!step.isMediaTranscript"
                                :id="'prompt-' + step.id"
                                v-model="step.prompt"
                                rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 font-mono text-sm shadow-sm focus:border-violet-300 focus:ring focus:ring-violet-200 focus:ring-opacity-50"
                                placeholder="Enter your prompt here..."
                            ></textarea>
                            <p v-else class="text-gray-500">
                                {{ step.prompt }}
                            </p>
                            <button
                                v-if="!step.isMediaTranscript"
                                @click="deleteStep(step.id)"
                                class="absolute right-3 top-3 text-red-500 hover:text-red-700"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </button>
                        </div>
                        <!-- Arrow connecting to the next node -->
                        <div
                            v-if="index < steps.length - 1"
                            class="absolute left-1/2 -translate-x-1/2 py-1"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-8 w-8 text-gray-300"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l4.293-4.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="mt-4 flex items-center justify-center">
                    <button
                        @click="addStep"
                        class="inline-flex items-center gap-x-1 rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-sm font-medium text-gray-800 shadow-sm hover:bg-gray-50 focus:bg-gray-50 focus:outline-none disabled:pointer-events-none disabled:opacity-50 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            class="h-4 w-4"
                            fill="none"
                            stroke="currentColor"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                        >
                            <line x1="12" x2="12" y1="5" y2="19" />
                            <line x1="5" x2="19" y1="12" y2="12" />
                        </svg>
                        Add Workflow Step
                    </button>
                </div>
            </div>
        </div>
    </App>
</template>
