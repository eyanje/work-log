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
import type { Book } from '@/types/book.d.ts';
import { Head, Link, useForm } from '@inertiajs/vue3';

const { book } = defineProps<{
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
        <div class="m-4 w-xl pb-4">
            <h1>Edit {{ book.title }}</h1>

            <section>
                <h2>Metadata</h2>
                <form @submit.prevent="submit">
                    <div class="input-group">
                        <Label for="title">Title</Label>
                        <Input v-model="form.title" name="title" placeholder="My Book" />
                    </div>
                    <Button type="submit" :disabled="!form.isDirty">Save</Button>
                </form>
            </section>

            <section>
                <h2>Import</h2>
                <p>Update books with iCalendar journal data.</p>
                <form class="gap-2" @submit.prevent="submitImport">
                    <div class="input-group">
                        <Label for="book">iCalendar data</Label>
                        <Input type="file" accept="text/calendar" name="book" @input="importForm.book = $event.target.files[0]" required />
                    </div>
                    <Button type="submit">Import</Button>
                </form>
            </section>

            <section>
                <h2>Export</h2>
                <p>Export notebooks regularly to avoid losing data.</p>
                <Button as-child>
                    <a :href="route('book.export', { id: book.id })">Export as iCalendar</a>
                </Button>
            </section>

            <section>
                <h2>Special Actions</h2>
                <Dialog>
                    <DialogTrigger>
                        <Button variant="destructive" class="w-full">Delete Book</Button>
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
            </section>
        </div>
    </AppLayout>
</template>

<style>
h1 {
    margin-bottom: calc(var(--spacing) * 6);
    font-size: xx-large;
    font-weight: bold;
}

section {
    margin-top: calc(var(--spacing) * 9);
    margin-bottom: calc(var(--spacing) * 9);
}

h2 {
    margin-top: calc(var(--spacing) * 4);
    margin-bottom: calc(var(--spacing) * 4);
    font-size: x-large;
    font-weight: bold;
}

p,
.input-group {
    margin-top: calc(var(--spacing) * 4);
    margin-bottom: calc(var(--spacing) * 4);
}

.input-group {
    display: grid;
    gap: calc(var(--spacing) * 2);
}

form button {
    display: block;
    width: 100%;
}
</style>
