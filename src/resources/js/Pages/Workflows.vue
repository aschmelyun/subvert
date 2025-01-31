<script setup>
import App from '@/Layouts/App.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    workflows: {
        type: Array,
        required: true,
    },
    errors: {
        type: Object,
        required: false,
    },
});

const addWorkflowActive = ref(false);

const form = useForm({
    name: '',
});
</script>

<template>
    <Head title="Workflows" />
    <App>
        <div v-if="workflows.length" class="grid grid-cols-3 gap-4 py-8">
            <Link
                v-for="item in workflows"
                :key="item.id"
                :href="route('workflows.show', item.id)"
                class="group block cursor-pointer overflow-hidden rounded-lg border border-slate-200 bg-white px-6 py-4 transition-all duration-300 hover:scale-[1.02] hover:border-violet-600 hover:shadow-lg"
            >
                <div class="flex items-center gap-2">
                    <h2 class="text-lg font-bold text-slate-900">
                        {{ item.name }}
                    </h2>
                </div>
                <p class="mt-1 text-slate-600">
                    {{ item.description }}
                </p>
                <small class="mt-4 block text-xs text-slate-600"
                    >Last used 5 mins ago</small
                >
            </Link>
            <div
                class="flex items-center justify-center overflow-hidden rounded-lg border border-dashed border-slate-200 px-6 py-4 text-center"
            >
                <div>
                    <p
                        v-if="!addWorkflowActive"
                        class="mb-4 mt-1 text-sm text-slate-600"
                    >
                        Add a new workflow by clicking the button below.
                    </p>
                    <p
                        v-if="addWorkflowActive"
                        class="mb-4 mt-1 text-sm text-slate-600"
                    >
                        Name your workflow and click continue.
                    </p>
                    <button
                        @click="addWorkflowActive = true"
                        v-if="!addWorkflowActive"
                        type="button"
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

                        Create New Workflow
                    </button>
                    <form
                        v-if="addWorkflowActive"
                        @submit.prevent="form.post('/workflows/create')"
                        class="flex items-center justify-center gap-2"
                    >
                        <input
                            v-model="form.name"
                            type="text"
                            id="name"
                            name="name"
                            placeholder="My Workflow Name"
                            class="inline-flex w-48 items-center gap-x-1 rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-sm font-medium text-gray-800 focus:border-violet-700 focus:shadow-sm focus:outline-none"
                        />
                        <button
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
                                <polyline points="13 17 18 12 13 7" />
                                <polyline points="6 17 11 12 6 7" />
                            </svg>

                            Continue
                        </button>
                    </form>
                    <div class="mt-2 text-sm text-rose-700" v-if="errors">
                        <p v-for="error in Object.keys(errors)" :key="error">
                            {{
                                typeof errors[error] === 'object'
                                    ? errors[error][0]
                                    : errors[error]
                            }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div
            v-else
            class="flex flex-col items-center rounded-lg border-2 border-dashed border-slate-200 p-16 text-center"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                class="h-12 w-12 text-slate-400"
                fill="none"
                stroke="currentColor"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
            >
                <line x1="6" x2="6" y1="3" y2="15" />
                <circle cx="18" cy="6" r="3" />
                <circle cx="6" cy="18" r="3" />
                <path d="M18 9a9 9 0 0 1-9 9" />
            </svg>

            <h3 class="mt-2 text-sm font-medium text-gray-900">
                No workflows yet
            </h3>
            <p class="mb-4 mt-1 text-sm text-gray-500">
                Add a new workflow by clicking the button below.
            </p>

            <div
                v-if="!addWorkflowActive"
                class="flex items-center justify-center gap-4"
            >
                <button
                    @click="addWorkflowActive = true"
                    type="button"
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

                    Create New Workflow
                </button>
            </div>
            <form
                v-if="addWorkflowActive"
                @submit.prevent="form.post('/workflows/create')"
                class="flex items-center justify-center gap-4"
            >
                <input
                    v-model="form.name"
                    type="text"
                    id="name"
                    name="name"
                    placeholder="My Workflow Name"
                    class="inline-flex w-64 items-center gap-x-1 rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-sm font-medium text-gray-800 focus:border-violet-700 focus:shadow-sm focus:outline-none"
                />
                <button
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
                        <polyline points="13 17 18 12 13 7" />
                        <polyline points="6 17 11 12 6 7" />
                    </svg>

                    Start Building
                </button>
            </form>
            <div class="mt-2 text-sm text-rose-700" v-if="errors">
                <p v-for="error in Object.keys(errors)" :key="error">
                    {{
                        typeof errors[error] === 'object'
                            ? errors[error][0]
                            : errors[error]
                    }}
                </p>
            </div>
        </div>
    </App>
</template>
