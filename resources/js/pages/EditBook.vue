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
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const book = computed(() => page.props.book);

const breadcrumbs = [
    {
        title: book.value.name,
        href: route('book.show', { id: book.value.id }),
    },
    {
        title: 'Edit',
        href: route('book.edit', { id: book.value.id }),
    },
];

const form = useForm({
    name: book.value.name,
});

const submit = () => {
    form.patch(route('book.update', { id: book.value.id }));
};

const deleteBook = () => {
    router.delete(route('book.delete', { id: book.value.id }));
};
</script>

<template>
    <Head :title="`${book.name} | Edit`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mr-auto p-4">
            <h1>Edit {{book.name}}</h1>

            <h2>Metadata</h2>
            <form @submit.prevent="submit" class="grid gap-3">
                <div class="grid gap-2">
                    <Label for="name">Name</Label>
                    <Input v-model="form.name" name="name" placeholder="My Book" />
                </div>
                <Button type="submit" :disabled="!form.isDirty" class="col-span-full">Save and exit</Button>
            </form>

            <h2>Special Actions</h2>

            <Dialog>
                <DialogTrigger>
                    <Button variant="destructive">Delete Book</Button>
                </DialogTrigger>
                <DialogContent>
                    <form class="space-y-6" @submit.prevent="deleteBook">
                        <DialogHeader class="space-y-3">
                            <DialogTitle>Are you sure you want to delete book "{{ book.name }}"?</DialogTitle>
                            <DialogDescription>
                                All of its data will be permanently deleted.
                            </DialogDescription>
                        </DialogHeader>

                        <DialogFooter>
                            <DialogClose>
                                <Button type="button" variant="secondary">Cancel</Button>
                            </DialogClose>
                            <Button type="submit" variant="destructive" :disabled="form.processing">Delete</Button>
                        </DialogFooter>
                    </form>
                </DialogContent>
            </Dialog>
        </div>
    </AppLayout>
</template>

<style>
h1 {
    margin-top: calc(var(--spacing) * 4);
    margin-bottom: calc(var(--spacing) * 2);
    font-size: x-large;
    font-weight: bold;
}

h2 {
    margin-top: calc(var(--spacing) * 6);
    margin-bottom: calc(var(--spacing) * 3);
    font-size: large;
    font-weight: bold;
}
</style>
