<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';

defineProps<{
    status?: string;
}>();

const breadcrumbs: BreadcrumbItem = [
    {
        title: 'New book',
        href: '/books/new',
    },
];

const form = useForm({
    title: '',
});

const submit = () => {
    form.put(route('books.create'));
};
</script>

<template>
    <Head title="New book" />

    <div v-if="status" class="mb-4 text-center text-sm font-medium text-green-600">
        {{ status }}
    </div>

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full w-96 flex-1 flex-col gap-4 rounded-xl p-4">
            <form @submit.prevent="submit" class="grid gap-4">
                <div class="grid gap-2">
                    <Label for="title">Title</Label>
                    <Input v-model="form.title" required placeholder="My Notebook" />
                    <InputError :message="form.errors.title" />
                </div>
                <Button type="submit" class="col-span-full" :disabled="form.processing">Create book</Button>
            </form>
        </div>
    </AppLayout>
</template>
