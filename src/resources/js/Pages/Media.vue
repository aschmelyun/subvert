<script setup>
import Switch from '@/Components/Switch.vue';
import App from '@/Layouts/App.vue';
import { ref } from 'vue';

const props = defineProps({
    media: {
        type: Object,
        required: true,
    },
    workflows: {
        type: Array,
        required: true,
    },
});

const title = ref(props.media.title);
const description = ref(props.media.description);

const updateMedia = () => {
    // Implement the update logic here
    // This could be an API call to update the media information
};

const runWorkflow = (workflowId) => {
    // Implement the logic to run a workflow
    // This could be an API call to start the selected workflow
};
</script>

<template>
    <App>
        <div class="rounded-lg border border-slate-200 bg-white p-6">
            <div class="flex">
                <!-- Left section: Media information -->
                <div class="w-1/2 pr-4">
                    <img
                        src="https://images.unsplash.com/photo-1731902062588-4dce45ccc0cb?q=80&w=2940&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                        class="h-48 w-full rounded object-cover"
                    />
                    <input
                        v-model="title"
                        @blur="updateMedia"
                        class="mb-2 w-full text-2xl font-bold"
                    />
                    <textarea
                        v-model="description"
                        @blur="updateMedia"
                        class="mb-4 w-full rounded border p-2"
                    ></textarea>
                    <div class="text-sm text-gray-600">
                        <p>Type: {{ media.type }}</p>
                        <p>Duration: {{ media.duration }} seconds</p>
                        <p>
                            File size:
                            {{ (media.file_size / 1024 / 1024).toFixed(2) }} MB
                        </p>
                        <p>MIME type: {{ media.mime_type }}</p>
                        <p>Status: {{ media.status }}</p>
                        <p v-if="media.transcribed_at">
                            Transcribed at: {{ media.transcribed_at }}
                        </p>
                    </div>
                </div>

                <!-- Right section: Workflows -->
                <div class="w-1/2 pl-4">
                    <h2 class="mb-4 text-xl font-bold">Media Workflows</h2>
                    <div class="mb-2 rounded border border-slate-200 px-4 py-3">
                        <Switch color="red" label="Subtitles"></Switch>
                    </div>
                    <div class="mb-2 rounded border border-slate-200 px-4 py-3">
                        <Switch color="blue" label="Description"></Switch>
                    </div>
                    <div class="mb-2 rounded border border-slate-200 px-4 py-3">
                        <Switch color="green" label="Chapters"></Switch>
                    </div>
                    <div class="rounded border border-slate-200 px-4 py-3">
                        <Switch color="purple" label="Article"></Switch>
                    </div>
                </div>
            </div>
            <h2 class="mb-4 text-xl font-bold">Media Output</h2>
            <div class="border-b border-gray-200 dark:border-neutral-700">
                <nav
                    class="flex gap-x-1"
                    aria-label="Tabs"
                    role="tablist"
                    aria-orientation="horizontal"
                >
                    <button
                        type="button"
                        class="active inline-flex items-center gap-x-2 whitespace-nowrap border-b-2 border-blue-600 border-transparent px-1 py-4 text-sm font-semibold text-blue-600 text-gray-500 hover:text-blue-600 focus:text-blue-600 focus:outline-none disabled:pointer-events-none disabled:opacity-50 dark:text-neutral-400 dark:hover:text-blue-500"
                        id="tabs-with-underline-item-1"
                        aria-selected="true"
                        data-hs-tab="#tabs-with-underline-1"
                        aria-controls="tabs-with-underline-1"
                        role="tab"
                    >
                        Subtitles
                        <span
                            class="inline-block h-2 w-2 rounded-full bg-green-500"
                        ></span>
                    </button>
                    <button
                        type="button"
                        class="hs-tab-active:font-semibold hs-tab-active:border-blue-600 hs-tab-active:text-blue-600 inline-flex items-center gap-x-2 whitespace-nowrap border-b-2 border-transparent px-1 py-4 text-sm text-gray-500 hover:text-blue-600 focus:text-blue-600 focus:outline-none disabled:pointer-events-none disabled:opacity-50 dark:text-neutral-400 dark:hover:text-blue-500"
                        id="tabs-with-underline-item-2"
                        aria-selected="false"
                        data-hs-tab="#tabs-with-underline-2"
                        aria-controls="tabs-with-underline-2"
                        role="tab"
                    >
                        Description
                    </button>
                    <button
                        type="button"
                        class="hs-tab-active:font-semibold hs-tab-active:border-blue-600 hs-tab-active:text-blue-600 inline-flex items-center gap-x-2 whitespace-nowrap border-b-2 border-transparent px-1 py-4 text-sm text-gray-500 hover:text-blue-600 focus:text-blue-600 focus:outline-none disabled:pointer-events-none disabled:opacity-50 dark:text-neutral-400 dark:hover:text-blue-500"
                        id="tabs-with-underline-item-3"
                        aria-selected="false"
                        data-hs-tab="#tabs-with-underline-3"
                        aria-controls="tabs-with-underline-3"
                        role="tab"
                    >
                        Chapters
                    </button>
                    <button
                        type="button"
                        class="hs-tab-active:font-semibold hs-tab-active:border-blue-600 hs-tab-active:text-blue-600 inline-flex items-center gap-x-2 whitespace-nowrap border-b-2 border-transparent px-1 py-4 text-sm text-gray-500 hover:text-blue-600 focus:text-blue-600 focus:outline-none disabled:pointer-events-none disabled:opacity-50 dark:text-neutral-400 dark:hover:text-blue-500"
                        id="tabs-with-underline-item-3"
                        aria-selected="false"
                        data-hs-tab="#tabs-with-underline-3"
                        aria-controls="tabs-with-underline-3"
                        role="tab"
                    >
                        Article
                    </button>
                </nav>
            </div>
        </div>
    </App>
</template>
