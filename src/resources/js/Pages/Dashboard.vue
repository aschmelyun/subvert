<script setup>
import App from '@/Layouts/App.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { route } from 'ziggy-js';

defineProps({
    media: {
        type: Array,
        required: true,
    },
    errors: {
        type: Object,
        required: false,
    },
});

const addMediaType = ref(null);

const form = useForm({
    link: null,
    file: null,
});
</script>

<template>
    <Head title="Welcome" />
    <App>
        <div v-if="media.length" class="grid grid-cols-3 gap-4 py-8">
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
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                <polyline points="17 8 12 3 7 8" />
                <line x1="12" x2="12" y1="3" y2="15" />
            </svg>

            <h3 class="mt-2 text-sm font-medium text-gray-900">No media yet</h3>
            <p class="mb-4 mt-1 text-sm text-gray-500">
                Get started by uploading or linking your first video or audio
                recording!
            </p>

            <div
                v-if="!addMediaType"
                class="flex items-center justify-center gap-4"
            >
                <button
                    @click="addMediaType = 'file'"
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

                    Upload File
                </button>
                <button
                    @click.prevent="addMediaType = 'link'"
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
                        <path
                            d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"
                        />
                        <path
                            d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"
                        />
                    </svg>

                    Paste Link
                </button>
            </div>
            <form
                v-if="addMediaType"
                @submit.prevent="form.post('/process')"
                enctype="multipart/form-data"
                class="flex items-center justify-center gap-4"
            >
                <input
                    v-if="addMediaType === 'link'"
                    v-model="form.link"
                    type="text"
                    id="link"
                    name="link"
                    placeholder="youtube.com/watch?v=dQw4w9WgXcQ"
                    class="inline-flex w-64 items-center gap-x-1 rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-sm font-medium text-gray-800 focus:border-violet-700 focus:shadow-sm focus:outline-none"
                />
                <input
                    v-if="addMediaType === 'file'"
                    @input="form.file = $event.target.files[0]"
                    type="file"
                    id="file"
                    name="file"
                    accept="video/*, audio/*"
                    class="inline-flex w-64 items-center gap-x-1 px-3 py-1 text-sm font-medium text-gray-800 focus:outline-none"
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
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                        <polyline points="22 4 12 14.01 9 11.01" />
                    </svg>

                    Start Processing
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
