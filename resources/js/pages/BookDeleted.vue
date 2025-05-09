<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Book deleted" />
    <AppLayout>
        <div class="mt-10 text-center">
            <Heading title="Book Deleted" />

            <p>Your book has been successfully deleted.</p>
        </div>
    </AppLayout>
</template>
