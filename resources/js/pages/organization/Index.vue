<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { SidebarTrigger } from '@/components/ui/sidebar';

defineOptions({
    layout: {
        breadcrumbs: [],
    },
});

const members = [
    { email: 'owner@example.com', role: 'Owner' },
    { email: 'engineer@example.com', role: 'Engineer' },
] as const;
</script>

<template>
    <Head title="Organization" />

    <div class="flex h-full flex-1 flex-col">
        <header
            class="flex flex-col gap-4 border-b border-border px-4 py-4 md:px-6"
        >
            <div class="flex min-w-0 items-start gap-2">
                <SidebarTrigger class="mt-0.5 -ml-1" />
                <div class="min-w-0">
                    <h1 class="text-lg font-semibold tracking-tight text-foreground">
                        Organization
                    </h1>
                    <p class="mt-0.5 text-sm text-muted-foreground">
                        Workspace identity and who can review agent output.
                    </p>
                </div>
            </div>
        </header>

        <div class="flex flex-1 flex-col gap-6 px-4 py-5 md:px-6">
            <section
                class="overflow-hidden rounded-md border border-border"
                aria-labelledby="identity-heading"
            >
                <div class="border-b border-border px-4 py-3">
                    <h2
                        id="identity-heading"
                        class="text-sm font-semibold text-foreground"
                    >
                        Identity
                    </h2>
                </div>
                <dl class="divide-y divide-border text-sm">
                    <div
                        class="flex flex-col gap-0.5 px-4 py-3 sm:flex-row sm:gap-6"
                    >
                        <dt class="w-24 shrink-0 text-muted-foreground">Name</dt>
                        <dd class="text-foreground">Unimarket</dd>
                    </div>
                    <div
                        class="flex flex-col gap-0.5 px-4 py-3 sm:flex-row sm:gap-6"
                    >
                        <dt class="w-24 shrink-0 text-muted-foreground">Slug</dt>
                        <dd class="font-mono text-foreground">unimarket</dd>
                    </div>
                </dl>
            </section>

            <section
                class="overflow-hidden rounded-md border border-border"
                aria-labelledby="members-heading"
            >
                <div class="border-b border-border px-4 py-3">
                    <h2
                        id="members-heading"
                        class="text-sm font-semibold text-foreground"
                    >
                        Members
                    </h2>
                </div>
                <ul class="divide-y divide-border text-sm">
                    <li
                        v-for="member in members"
                        :key="member.email"
                        class="flex items-center justify-between gap-3 px-4 py-3"
                    >
                        <span class="font-mono text-foreground">{{
                            member.email
                        }}</span>
                        <span class="text-muted-foreground">{{
                            member.role
                        }}</span>
                    </li>
                </ul>
            </section>
        </div>
    </div>
</template>
