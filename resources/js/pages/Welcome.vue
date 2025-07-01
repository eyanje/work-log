<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Welcome"> </Head>
    <div class="mx-auto flex min-h-screen max-w-2xl flex-col items-center p-6">
        <header class="mb-6 w-full">
            <nav class="flex items-center justify-end gap-4">
                <Button as-child v-if="$page.props.auth.user">
                    <Link :href="route('library')"> Library </Link>
                </Button>
                <template v-else>
                    <Button as-child variant="ghost">
                        <Link :href="route('login')"> Log in </Link>
                    </Button>
                    <Button as-child>
                        <Link :href="route('register')"> Register </Link>
                    </Button>
                </template>
            </nav>
        </header>
        <main class="w-full">
            <div class="flex flex-col">
                <div>
                    <img src="/front-banner.svg"
                    class="w-full aspect-3/2 border-6" />
                </div>
                <div class="p-4">
                    <h1>Work Log</h1>
                    <ol class="ml-md list-inside list-decimal">
                        <li>Record tasks when you start them.</li>
                        <li>Record tasks when you stop them.</li>
                    </ol>
                </div>
            </div>
        </main>
    </div>
</template>

<style>
h1 {
    font-weight: bold;
    font-size: xx-large;
}
</style>
