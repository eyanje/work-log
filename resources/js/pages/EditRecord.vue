<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import AppLayout from '@/layouts/AppLayout.vue';
import type { Book, Record } from '@/types/book.d.ts';
import { Head, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const { book, record } = defineProps<{
    book: Book;
    record: Record;
}>();

const breadcrumbs = computed(() => [
    {
        title: 'Library',
        href: route('library'),
    },
    {
        title: book.title,
        href: route('book.show', { id: book.id }),
    },
    {
        title: `Record #${record.id}`,
        href: route('record.edit', { bookId: book.id, recordId: record.id }),
    },
]);

const form = useForm({
    started_at: record.started_at,
    ended_at: record.ended_at,
    content: record.content,
});

const submit = () => {
    form.put(route('record.update', { bookId: book.id, recordId: record.id }));
};
</script>

<template>
    <Head title="Edit entry" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <h1>Editing record #{{ record.id }}</h1>
            <p>UID {{ record.uid }}</p>

            <form @submit.prevent="submit" class="mt-5 mr-auto grid w-xl gap-3">
                <div>
                    <Label for="started-at">Started at</Label>
                    <Input name="started-at" v-model="form.started_at" />
                </div>
                <div>
                    <Label for="ended-at">Ended at</Label>
                    <Input name="ended-at" v-model="form.ended_at" />
                </div>
                <div>
                    <Label for="content">Content</Label>
                    <Input name="content" v-model="form.content" />
                </div>
                <Button type="submit" :active="form.isDirty">Save changes</Button>
            </form>
        </div>
    </AppLayout>
</template>

<style scoped>
h1 {
    font-weight: bold;
    font-size: x-large;
}
</style>
