<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { Ellipsis } from 'lucide-vue-next';
import { computed, ref } from 'vue';

const page = usePage();

const book = page.props.book;
const records = computed(() => page.props.records);

const breadcrumbs: BreadcrumbItem = [
    {
        title: book.name,
        href: page.url,
    },
];

const form = useForm({
    content: '',
});

const submit = () => {
    form.post(route('book.append', { id: book.id }));
    form.reset();
};

const testAct = (id: number) => {
    console.log(`Test action for ${id}`);
};
</script>

<template>
    <Head :title="book.name" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <form @submit.prevent="submit" class="flex flex-row gap-2">
                <Input v-model="form.content" id="content-input" name="content" placeholder="New entry" aria-label="New entry" autofocus required />
                <Button>Log</Button>
            </form>
            <div>
                <div class="flex flex-row gap-4" v-for="record in records" v-bind:key="record.id">
                    <div class="w-24 flex-none">
                        {{ new Date(record.created_at).toLocaleTimeString() }}
                    </div>
                    <div class="flex-1">
                        {{ record.content }}
                    </div>
                    <div class="flex-none">
                        <DropdownMenu>
                            <DropdownMenuTrigger>
                                <Ellipsis class="size-5" />
                            </DropdownMenuTrigger>
                            <DropdownMenuContent>
                                <DropdownMenuItem @click="() => testAct(record.id)"> Test action </DropdownMenuItem>
                            </DropdownMenuContent>
                        </DropdownMenu>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
