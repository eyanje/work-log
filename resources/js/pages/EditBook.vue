<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { Book } from '@/types/book.d.ts';
import { Head, Link, useForm } from '@inertiajs/vue3';

const { book } = defineProps<{
    status?: string;
    book: Book;
}>();

const breadcrumbs = [
    {
        title: book.title,
        href: route('book.show', { id: book.id }),
    },
    {
        title: 'Edit',
        href: route('book.edit', { id: book.id }),
    },
];

const form = useForm({
    title: book.title,
});

const submit = () => {
    form.patch(route('book.update', { id: book.id }));
};

const importForm = useForm({
    book: null,
});

const submitImport = () => {
    importForm.post(route('book.import', { id: book.id }));
};
</script>

<template>
    <Head :title="`${book.title} | Edit`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div v-if="status" class="mb-4 text-center text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <div class="m-4">
            <h1>Edit {{ book.title }}</h1>

            <h2>Metadata</h2>
            <form @submit.prevent="submit" class="mr-auto w-fit">
                <p class="grid gap-2">
                    <Label for="title">Title</Label>
                    <Input v-model="form.title" name="title" placeholder="My Book" />
                </p>
                <p>
                    <Button type="submit" :disabled="!form.isDirty" class="col-span-full">Save</Button>
                </p>
            </form>

            <h2>Import</h2>
            <p>Update books with iCalendar journal data.</p>
            <form class="mr-auto w-fit gap-2" @submit.prevent="submitImport">
                <p class="grid gap-2">
                    <Label for="book">iCalendar data</Label>
                    <Input type="file" accept="text/calendar" name="book"
                    @input="importForm.book = $event.target.files[0]" required />
                </p>
                <p>
                    <Button type="submit">Import</Button>
                </p>
            </form>

            <h2>Export</h2>
            <p>Export notebooks regularly to avoid losing data.</p>
            <p>
                <Button as-child>
                    <a :href="route('book.export', { id: book.id })">Export as iCalendar</a>
                </Button>
            </p>

            <h2>Special Actions</h2>
            <Dialog>
                <DialogTrigger>
                    <Button variant="destructive">Delete Book</Button>
                </DialogTrigger>
                <DialogContent>
                    <DialogHeader class="space-y-3">
                        <DialogTitle>Are you sure you want to delete book "{{ book.title }}"?</DialogTitle>
                        <DialogDescription> All of its data will be permanently deleted. </DialogDescription>
                    </DialogHeader>
                    <DialogFooter>
                        <DialogClose>
                            <Button type="button" variant="secondary">Cancel</Button>
                        </DialogClose>
                        <Button as-child variant="destructive">
                            <Link :href="route('book.delete', { id: book.id })" method="delete">Delete</Link>
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </div>
    </AppLayout>
</template>

<style>
h1 {
    margin-bottom: calc(var(--spacing) * 3);
    font-size: x-large;
    font-weight: bold;
}

h2 {
    margin-top: calc(var(--spacing) * 6);
    margin-bottom: calc(var(--spacing) * 1);
    font-size: large;
    font-weight: bold;
}

form,
p {
    margin-bottom: calc(var(--spacing) * 1);
}
</style>
