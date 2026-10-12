<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { SidebarTrigger } from '@/components/ui/sidebar';

defineOptions({
    layout: {
        breadcrumbs: [],
    },
});

const repositories = [
    {
        name: 'Microsoft/microsoft',
        branch: 'main',
        usedBy: 'bug fix, feature',
        mode: 'Draft PRs',
    },
    {
        name: 'Microsoft/microsoft-ui',
        branch: 'main',
        usedBy: 'bug fix',
        mode: 'Draft PRs',
    },
] as const;
</script>

<template>
    <Head title="Repositories" />

    <div class="flex h-full flex-1 flex-col">
        <header
            class="flex flex-col gap-4 border-b border-border px-4 py-4 md:flex-row md:items-center md:justify-between md:px-6"
        >
            <div class="flex min-w-0 items-start gap-2">
                <SidebarTrigger class="mt-0.5 -ml-1" />
                <div class="min-w-0">
                    <h1 class="text-lg font-semibold tracking-tight text-foreground">
                        Repositories
                    </h1>
                    <p class="mt-0.5 text-sm text-muted-foreground">
                        GitHub repositories agents use when they open a draft
                        pull request.
                    </p>
                </div>
            </div>
            <Button type="button" size="sm" disabled class="self-start md:self-center">
                Connect GitHub
            </Button>
        </header>

        <div class="flex flex-1 flex-col gap-6 px-4 py-5 md:px-6">
            <div class="overflow-hidden rounded-md border border-border">
                <ul class="divide-y divide-border lg:hidden">
                    <li
                        v-for="repo in repositories"
                        :key="repo.name"
                        class="px-4 py-3"
                    >
                        <p class="font-mono text-sm text-foreground">
                            {{ repo.name }}
                        </p>
                        <p class="mt-1 text-xs text-muted-foreground">
                            {{ repo.branch }} · {{ repo.usedBy }} ·
                            {{ repo.mode }}
                        </p>
                    </li>
                </ul>

                <div class="hidden overflow-x-auto lg:block">
                    <table class="w-full text-left text-sm">
                        <thead
                            class="border-b border-border text-xs text-muted-foreground"
                        >
                            <tr>
                                <th class="px-4 py-2 font-medium">
                                    Repository
                                </th>
                                <th
                                    class="px-4 py-2 font-medium whitespace-nowrap"
                                >
                                    Default branch
                                </th>
                                <th class="px-4 py-2 font-medium">Used by</th>
                                <th class="px-4 py-2 font-medium">Mode</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            <tr
                                v-for="repo in repositories"
                                :key="repo.name"
                            >
                                <td
                                    class="px-4 py-2.5 font-mono text-xs text-foreground"
                                >
                                    {{ repo.name }}
                                </td>
                                <td
                                    class="px-4 py-2.5 font-mono text-xs text-muted-foreground"
                                >
                                    {{ repo.branch }}
                                </td>
                                <td class="px-4 py-2.5 text-muted-foreground">
                                    {{ repo.usedBy }}
                                </td>
                                <td class="px-4 py-2.5 text-muted-foreground">
                                    {{ repo.mode }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>
