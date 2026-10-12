<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { SidebarTrigger } from '@/components/ui/sidebar';

defineOptions({
    layout: {
        breadcrumbs: [],
    },
});

const connectors = [
    {
        name: 'Jira',
        via: 'Atlassian',
        purpose:
            'Receives ai-ready, ai-implement-approved, and ai-revise-plan, and stores the diagnosis.',
        connected: true,
    },
    {
        name: 'Confluence',
        via: 'Atlassian',
        purpose: 'Searched during triage.',
        connected: true,
    },
    {
        name: 'Slack',
        via: 'Slack',
        purpose:
            'channel-router watches support channels and posts status back to the thread.',
        connected: true,
    },
    {
        name: 'Zendesk',
        via: 'Zendesk',
        purpose: 'A ticket tagged bug is what starts triage.',
        connected: false,
    },
    {
        name: 'Claude Code',
        via: 'Anthropic CLI',
        purpose:
            'The bug-fix agent uses it to clone, edit, and open the draft PR.',
        connected: true,
    },
    {
        name: 'Cursor CLI',
        via: 'Cursor',
        purpose: 'Alternate code agent for the same draft-PR step.',
        connected: false,
    },
] as const;
</script>

<template>
    <Head title="Connectors" />

    <div class="flex h-full flex-1 flex-col">
        <header
            class="flex flex-col gap-4 border-b border-border px-4 py-4 md:px-6"
        >
            <div class="flex min-w-0 items-start gap-2">
                <SidebarTrigger class="mt-0.5 -ml-1" />
                <div class="min-w-0">
                    <h1 class="text-lg font-semibold tracking-tight text-foreground">
                        Connectors
                    </h1>
                    <p class="mt-0.5 text-sm text-muted-foreground">
                        Systems agents read and write. Connection state is
                        sample data.
                    </p>
                </div>
            </div>
        </header>

        <div class="flex flex-1 flex-col gap-6 px-4 py-5 md:px-6">
            <ul class="grid gap-3 sm:grid-cols-2">
                <li
                    v-for="connector in connectors"
                    :key="connector.name"
                    class="flex flex-col rounded-md border border-border bg-card px-4 py-3"
                >
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <h2 class="text-sm font-semibold text-foreground">
                                {{ connector.name }}
                            </h2>
                            <p class="mt-0.5 text-xs text-muted-foreground">
                                via {{ connector.via }}
                            </p>
                        </div>
                        <span
                            class="inline-flex shrink-0 items-center gap-1.5 text-xs text-foreground"
                        >
                            <span
                                class="size-1.5 rounded-full"
                                :class="
                                    connector.connected
                                        ? 'bg-forest-500'
                                        : 'bg-stone-400'
                                "
                                aria-hidden="true"
                            />
                            {{
                                connector.connected
                                    ? 'Connected'
                                    : 'Not connected'
                            }}
                        </span>
                    </div>
                    <p class="mt-2 flex-1 text-sm text-muted-foreground">
                        {{ connector.purpose }}
                    </p>
                    <div v-if="!connector.connected" class="mt-3">
                        <Button type="button" size="sm" disabled>
                            Connect
                        </Button>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>
