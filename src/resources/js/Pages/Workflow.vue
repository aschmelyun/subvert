<script setup>
import App from '@/Layouts/App.vue';
import { Head } from '@inertiajs/vue3';
import { Background } from '@vue-flow/background';
import { VueFlow } from '@vue-flow/core';
import '@vue-flow/core/dist/style.css';
import '@vue-flow/core/dist/theme-default.css';
import { ref } from 'vue';

defineProps({
    workflow: {
        type: Object,
        required: true,
    },
});

// these are our nodes
const nodes = ref([
    // an input node, specified by using `type: 'input'`
    {
        id: '1',
        type: 'input',
        position: { x: 250, y: 5 },
        // all nodes can have a data object containing any data you want to pass to the node
        // a label can property can be used for default nodes
        data: { label: 'Node 1' },
    },

    // default node, you can omit `type: 'default'` as it's the fallback type
    {
        id: '2',
        position: { x: 100, y: 100 },
        data: { label: 'Node 2' },
    },
]);

// these are our edges
const edges = ref([
    // default bezier edge
    // consists of an edge id, source node id and target node id
    {
        id: 'e1->2',
        source: '1',
        target: '2',
    },
]);

const addNode = () => {
    const id = Date.now().toString();

    nodes.value.push({
        id,
        position: { x: 150, y: 50 },
        data: { label: `Node ${id}` },
    });
};
</script>

<template>
    <Head title="Workflows" />
    <App>
        <div class="relative h-screen w-full">
            <div
                class="absolute left-8 top-8 z-10 w-96 rounded bg-white p-6 shadow-lg"
            >
                <button @click.prevent="addNode">Add Node</button>
            </div>
            <VueFlow :nodes="nodes" :edges="edges">
                <Background />
            </VueFlow>
        </div>
    </App>
</template>
