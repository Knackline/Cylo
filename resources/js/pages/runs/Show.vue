<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { SidebarTrigger } from '@/components/ui/sidebar';
import {
    type AgentId,
    type JobStatus,
    findRun,
} from '@/data/sampleRuns';
import { index as agentsIndex } from '@/routes/agents';
import { index as runsIndex } from '@/routes/runs';

const props = defineProps<{
    run: string;
}>();

defineOptions({
    layout: {
        breadcrumbs: [],
    },
});

type Tone = 'active' | 'attention' | 'quiet';

const toneDotClass: Record<Tone, string> = {
    active: 'bg-forest-500',
    attention: 'bg-gold-500',
    quiet: 'bg-stone-400',
};

const agentCopy: Record<AgentId, string> = {
    'bug-triage':
        'Triage investigates a ticket, issue, Slack report, or error group and posts a diagnosis. It does not open a pull request.',
    'bug-fix':
        'Bug fix opens a draft pull request only. A person merges it.',
    feature:
        'Feature plans first, then implements after approval. Draft pull requests only.',
};

const record = computed(() => findRun(props.run));

function statusTone(status: JobStatus): Tone {
    if (
        status === 'Needs review' ||
        status === 'Awaiting approval' ||
        status === 'Reviewing'
    ) {
        return 'attention';
    }

    if (status === 'Merged') {
        return 'quiet';
    }

    return 'active';
}
</script>

<template>
    <Head :title="record ? record.key : 'Run not found'" />

    <div class="flex h-full flex-1 flex-col">
        <header
            class="flex flex-col gap-4 border-b border-border px-4 py-4 md:px-6"
        >
            <div class="flex min-w-0 items-start gap-2">
                <SidebarTrigger class="mt-0.5 -ml-1" />
                <div class="min-w-0">
                    <template v-if="record">
                        <div class="flex flex-wrap items-center gap-2">
                            <h1
                                class="text-lg font-semibold tracking-tight text-foreground"
                            >
                                {{ record.key }}
                            </h1>
                            <span
                                class="inline-flex items-center gap-1.5 text-xs text-foreground"
                            >
                                <span
                                    class="size-1.5 rounded-full"
                                    :class="
                                        toneDotClass[statusTone(record.status)]
                                    "
                                    aria-hidden="true"
                                />
                                {{ record.status }}
                            </span>
                        </div>
                        <p class="mt-0.5 text-sm text-muted-foreground">
                            {{ record.title }}
                        </p>
                    </template>
                    <template v-else>
                        <h1
                            class="text-lg font-semibold tracking-tight text-foreground"
                        >
                            Run not found
                        </h1>
                        <p class="mt-0.5 text-sm text-muted-foreground">
                            No sample run matches this key.
                        </p>
                    </template>
                    <p class="mt-2">
                        <Link
                            :href="runsIndex()"
                            class="text-sm text-muted-foreground underline-offset-4 hover:text-foreground hover:underline"
                        >
                            All runs
                        </Link>
                    </p>
                </div>
            </div>
        </header>

        <div
            v-if="record"
            class="flex flex-1 flex-col gap-6 px-4 py-5 md:px-6"
        >
            <section
                class="overflow-hidden rounded-md border border-border"
                aria-labelledby="trigger-heading"
            >
                <div class="border-b border-border px-4 py-3">
                    <h2
                        id="trigger-heading"
                        class="text-sm font-semibold text-foreground"
                    >
                        Triggered by
                    </h2>
                </div>
                <dl class="divide-y divide-border text-sm">
                    <div class="flex flex-col gap-0.5 px-4 py-3 sm:flex-row sm:gap-6">
                        <dt class="w-24 shrink-0 text-muted-foreground">
                            Source
                        </dt>
                        <dd class="text-foreground">
                            {{ record.trigger.source }}
                        </dd>
                    </div>
                    <div class="flex flex-col gap-0.5 px-4 py-3 sm:flex-row sm:gap-6">
                        <dt class="w-24 shrink-0 text-muted-foreground">
                            Event
                        </dt>
                        <dd class="text-foreground">
                            {{ record.trigger.event }}
                        </dd>
                    </div>
                    <div class="flex flex-col gap-0.5 px-4 py-3 sm:flex-row sm:gap-6">
                        <dt class="w-24 shrink-0 text-muted-foreground">
                            Time
                        </dt>
                        <dd class="font-mono text-foreground">
                            {{ record.trigger.at }}
                        </dd>
                    </div>
                </dl>
            </section>

            <section
                class="overflow-hidden rounded-md border border-border"
                aria-labelledby="context-heading"
            >
                <div class="border-b border-border px-4 py-3">
                    <h2
                        id="context-heading"
                        class="text-sm font-semibold text-foreground"
                    >
                        Context
                    </h2>
                </div>
                <dl class="divide-y divide-border text-sm">
                    <div class="flex flex-col gap-0.5 px-4 py-3 sm:flex-row sm:gap-6">
                        <dt class="w-24 shrink-0 text-muted-foreground">
                            Summary
                        </dt>
                        <dd class="text-foreground">
                            {{ record.context.summary }}
                        </dd>
                    </div>
                    <div class="flex flex-col gap-0.5 px-4 py-3 sm:flex-row sm:gap-6">
                        <dt class="w-24 shrink-0 text-muted-foreground">Mode</dt>
                        <dd class="text-foreground">
                            {{ record.context.mode }}
                        </dd>
                    </div>
                    <div class="flex flex-col gap-0.5 px-4 py-3 sm:flex-row sm:gap-6">
                        <dt class="w-24 shrink-0 text-muted-foreground">
                            Confidence
                        </dt>
                        <dd class="font-mono text-foreground">
                            {{
                                record.confidence !== null
                                    ? record.confidence.toFixed(2)
                                    : 'Not scored'
                            }}
                        </dd>
                    </div>
                </dl>
            </section>

            <section
                class="overflow-hidden rounded-md border border-border"
                aria-labelledby="agent-heading"
            >
                <div class="border-b border-border px-4 py-3">
                    <h2
                        id="agent-heading"
                        class="text-sm font-semibold text-foreground"
                    >
                        Agent
                    </h2>
                </div>
                <div class="space-y-2 px-4 py-3 text-sm">
                    <p>
                        <Link
                            :href="agentsIndex()"
                            class="font-mono text-foreground underline-offset-4 hover:underline"
                        >
                            {{ record.agent }}
                        </Link>
                    </p>
                    <p class="text-muted-foreground">
                        {{ agentCopy[record.agent] }}
                    </p>
                </div>
            </section>

            <section
                v-if="record.links.length"
                class="overflow-hidden rounded-md border border-border"
                aria-labelledby="links-heading"
            >
                <div class="border-b border-border px-4 py-3">
                    <h2
                        id="links-heading"
                        class="text-sm font-semibold text-foreground"
                    >
                        Links
                    </h2>
                </div>
                <ul class="divide-y divide-border text-sm">
                    <li
                        v-for="link in record.links"
                        :key="link.href"
                        class="px-4 py-3"
                    >
                        <a
                            :href="link.href"
                            target="_blank"
                            rel="noreferrer"
                            class="text-foreground underline-offset-4 hover:underline"
                        >
                            {{ link.label }}
                        </a>
                    </li>
                </ul>
            </section>
        </div>
    </div>
</template>
