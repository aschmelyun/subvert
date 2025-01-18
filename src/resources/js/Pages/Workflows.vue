<script setup>
import App from '@/Layouts/App.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { route } from 'ziggy-js';

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
                v-for="item in media"
                :key="item.id"
                :href="route('media.show', item.id)"
                class="group block cursor-pointer overflow-hidden rounded-lg bg-white transition-all duration-300 hover:scale-[1.02] hover:shadow-lg"
            >
                <div class="relative">
                    <img
                        src="https://images.unsplash.com/photo-1731902062588-4dce45ccc0cb?q=80&w=2940&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                        class="h-48 w-full object-cover"
                    />
                    <div class="absolute right-3 top-3">
                        <span
                            class="inline-flex animate-pulse items-center rounded-full border border-blue-200 bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800"
                            >processing</span
                        >
                    </div>
                    <button
                        class="absolute left-3 top-3 rounded-full bg-white/90 p-1.5 text-gray-600 opacity-0 transition-opacity hover:text-red-600 group-hover:opacity-100"
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
                            <polyline points="3 6 5 6 21 6" />
                            <path
                                d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"
                            />
                            <line x1="10" x2="10" y1="11" y2="17" />
                            <line x1="14" x2="14" y1="11" y2="17" />
                        </svg>
                    </button>
                </div>
                <div class="p-4">
                    <h3 class="truncate text-lg font-medium text-gray-900">
                        {{ item.title }}
                    </h3>

                    <div
                        class="mt-2 flex items-center space-x-4 text-sm text-gray-500"
                    >
                        <div class="flex items-center">
                            {{ item.created_at }}
                        </div>
                        <div class="flex items-center">
                            {{ item.duration }}
                        </div>
                    </div>

                    <div class="mt-4 flex flex-wrap gap-2">
                        <div
                            class="focus:ring-ring bg-secondary text-secondary-foreground hover:bg-secondary/80 inline-flex items-center rounded-md border border-transparent bg-purple-100 px-2.5 py-0.5 text-xs font-semibold text-purple-800 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2"
                        >
                            subtitles
                        </div>
                        <div
                            class="focus:ring-ring bg-secondary text-secondary-foreground hover:bg-secondary/80 inline-flex items-center rounded-md border border-transparent bg-green-100 px-2.5 py-0.5 text-xs font-semibold text-green-800 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2"
                        >
                            description
                        </div>
                        <div
                            class="focus:ring-ring bg-secondary text-secondary-foreground hover:bg-secondary/80 inline-flex items-center rounded-md border border-transparent bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-600 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2"
                        >
                            chapters
                        </div>
                        <div
                            class="focus:ring-ring bg-secondary text-secondary-foreground hover:bg-secondary/80 inline-flex items-center rounded-md border border-transparent bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-600 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2"
                        >
                            article
                        </div>
                    </div>
                </div>
            </Link>
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
                        class="h-4 w-4"
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
