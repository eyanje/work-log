<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import type { Book } from '@/types/book.d.ts';
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import { Bookmark, Ellipsis, Pencil } from 'lucide-vue-next';
import { computed } from 'vue';

const page = usePage();

const { status, book, records } = defineProps<{
    status?: string;
    book: Book;
    records: {
        started_at: string;
        ended_at?: string;
    }[];
}>();

const recordsWithDate = computed(() => {
    // Add the showDate boolean field
    let lastDate = null;
    return records.map((record) => {
        const recordDate = new Date(record.started_at).toLocaleDateString();
        const showDate = lastDate != recordDate;
        lastDate = recordDate;
        return {
            showDate: showDate,
            ...record,
        };
    });
});

const breadcrumbs: BreadcrumbItem = [
    {
        title: book.title,
        href: page.url,
    },
];

const form = useForm({
    content: '',
    started_at: null,
});

const submit = () => {
    // Add the start date from the client
    form.started_at = new Date();

    form.post(route('record.create', { bookId: book.id }));
    form.reset();
};

const deleteRecord = (bookId: number, recordId: number) => {
    router.delete(route('record.delete', { bookId: bookId, recordId: recordId }));
};

const testAct = (id: number) => {
    console.log(`Test action for ${id}`);
};

const bookmark = (book: Book) => {
    if (book.bookmarked) {
        router.delete(route('book.unbookmark', { id: book.id }));
    } else {
        router.post(route('book.bookmark', { id: book.id }));
    }
};
</script>

<template>
    <Head :title="book.title" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div v-if="status" class="- mb-4 text-center text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <div class="flex flex-1 flex-col gap-4 p-4">
            <div class="flex flex-row items-baseline gap-2">
                <h1 class="text-2xl font-bold">{{ book.title }}</h1>
                <Button as-child variant="ghost">
                    <Link :href="route('book.edit', { id: book.id })"><Pencil />Edit</Link>
                </Button>
                <Button variant="ghost" @click="bookmark(book)">
                    <template v-if="book.bookmarked">
                        <Bookmark fill="#000" />
                        Bookmarked
                    </template>
                    <template v-else>
                        <Bookmark />
                        Not bookmarked
                    </template>
                </Button>
            </div>
            <form @submit.prevent="submit" class="flex flex-row gap-2">
                <Input v-model="form.content" id="content-input" name="content" placeholder="New entry" aria-label="New entry" autofocus required />
                <Button>Log</Button>
            </form>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Started</th>
                        <th>Ended</th>
                        <th>Entry</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="record in recordsWithDate" v-bind:key="record.id">
                        <td class="text-sm text-gray-700">
                            <template v-if="record.showDate">
                                {{ new Date(record.started_at).toLocaleDateString() }}
                            </template>
                        </td>
                        <td class="text-sm text-gray-700">
                            <time class="text-nowrap">{{
                                new Date(record.started_at).toLocaleTimeString([], {
                                    hour: '2-digit',
                                    minute: '2-digit',
                                })
                            }}</time>
                        </td>
                        <td class="text-sm text-gray-700">
                            <time v-if="record.ended_at" class="text-nowrap">{{
                                new Date(record.ended_at).toLocaleTimeString([], {
                                    hour: '2-digit',
                                    minute: '2-digit',
                                })
                            }}</time>
                            <div v-else></div>
                        </td>
                        <td class="w-full">
                            {{ record.content }}
                        </td>
                        <td>
                            <DropdownMenu>
                                <DropdownMenuTrigger>
                                    <Ellipsis class="size-5" />
                                </DropdownMenuTrigger>
                                <DropdownMenuContent>
                                    <Link :href="route('record.edit', { bookId: book.id, recordId: record.id })" as-child>
                                        <DropdownMenuItem> Edit </DropdownMenuItem>
                                    </Link>
                                    <DropdownMenuItem @click="() => deleteRecord(book.id, record.id)">Delete</DropdownMenuItem>
                                    <DropdownMenuItem @click="() => testAct(record.id)"> Test action </DropdownMenuItem>
                                </DropdownMenuContent>
                            </DropdownMenu>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AppLayout>
</template>

<style>
th,
td {
    padding-left: calc(var(--spacing) * 2);
    padding-right: calc(var(--spacing) * 2);
    text-align: left;
}

th:first-child,
td:first-child {
    padding-left: 0;
}

th:last-child,
td:last-child {
    padding-right: 0;
}
</style>
