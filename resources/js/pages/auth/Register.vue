<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import TextLink from '@/components/TextLink.vue';
import { login } from '@/routes';
import { create } from '@/routes/waitlist';

defineOptions({
    layout: {
        title: 'Signup is closed',
        description: 'Join the waitlist and we will be in touch when accounts open.',
    },
});
</script>

<template>
    <Head title="Signup is closed" />

    <div class="grid gap-6 text-center text-sm text-muted-foreground">
        <p>New accounts are not open yet.</p>
        <TextLink :href="create()">Join the waitlist</TextLink>
        <div>
            Already have an account?
            <TextLink :href="login()" class="underline underline-offset-4">
                Log in
            </TextLink>
        </div>
    </div>
</template>
