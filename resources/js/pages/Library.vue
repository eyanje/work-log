<script setup lang="ts">
import TextLink from '@/components/TextLink.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { Book } from '@/types/book.d.ts';
import { Head, usePage } from '@inertiajs/vue3';

const { status, books } = defineProps<{
    status?: string;
    books: Book[];
}>();

const page = usePage();

const breadcrumbs = [
    {
        href: page.url,
        title: 'Library',
    },
];
</script>

<template>
    <Head title="Library" />
    <AppLayout :breadcrumbs="breadcrumbs">
        {{ status }}
        <div class="mr-auto w-auto p-4">
            <div class="grid grid-cols-[auto_1fr] items-baseline gap-x-4">
                <template v-for="book in books" v-bind:key="book.id">
                    <TextLink :href="route('book.show', { id: book.id })">
                        {{ book.title }}
                    </TextLink>
                    <div class="ml-10 text-sm text-gray-500">Created {{ new Date(book.created_at).toLocaleDateString() }}</div>
                </template>
            </div>
        </div>
    </AppLayout>
</template>

<style>
a {
}

th {
    text-align: left;
}

td,
th {
    padding-left: calc(var(--spacing) * 2);
    padding-right: calc(var(--spacing) * 2);
    padding-top: calc(var(--spacing) * 1);
    padding-bottom: calc(var(--spacing) * 1);
}
</style>
