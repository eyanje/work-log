<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
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

/// Format a Date into YYYY-MM-ddTHH:mm for datetime-local
const formatDate = (dateString: string) => {
    if (dateString.length == 0) {
        return '';
    }

    const d = new Date(dateString);

    const year = `${d.getFullYear()}`.padStart(4, '0');
    const month = `${d.getMonth()}`.padStart(2, '0');
    const date = `${d.getDate()}`.padStart(2, '0');
    const hours = `${d.getHours()}`.padStart(2, '0');
    const minutes = `${d.getMinutes()}`.padStart(2, '0');

    const formatted = `${year}-${month}-${date}T${hours}:${minutes}`;
    return formatted;
}

const form = useForm({
    started_at: record.started_at,
    local_started_at: formatDate(record.started_at),
    ended_at: record.ended_at,
    local_ended_at: formatDate(record.ended_at),
    content: record.content,
});

const submit = () => {
    // Format local dates as ISO
    form.started_at = new Date(form.local_started_at).toISOString();
    form.ended_at = form.local_ended_at.length == 0 ? '' : new Date(form.local_ended_at).toISOString();

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
                <div class="grid gap-1">
                    <Label for="started-at">Started at</Label>
                    <Input name="started-at" v-model="form.local_started_at"
                                 type="datetime-local" />
                </div>
                <div class="grid gap-1">
                    <Label for="ended-at">Ended at</Label>
                    <Input name="ended-at" v-model="form.local_ended_at"
                                 type="datetime-local" />
                    <button type="button" class="w-auto mr-auto underline"
                        @click="form.local_ended_at = ''"
                        >Clear</button>
                </div>
                <div class="grid gap-1">
                    <Label for="content">Content</Label>
                    <Input name="content" v-model="form.content" />
                </div>
                <Button type="submit" :active="form.isDirty">Save and exit</Button>
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
