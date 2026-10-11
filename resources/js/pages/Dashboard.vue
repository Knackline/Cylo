<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Badge } from '@/components/ui/badge';
import { dashboard } from '@/routes';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Dashboard',
                href: dashboard(),
            },
        ],
    },
});

type Tone = 'active' | 'attention' | 'quiet';

type JobStatus =
    | 'Investigating'
    | 'Needs review'
    | 'Awaiting approval'
    | 'Developing'
    | 'Reviewing'
    | 'Merged';

const toneClass: Record<Tone, string> = {
    active: 'bg-forest-400',
    attention: 'bg-gold-400',
    quiet: 'bg-stone-400',
};

const metrics = [
    {
        label: 'Runs this week',
        value: '128',
        detail: '+12% agent run events',
    },
    {
        label: 'Needs review',
        value: '7',
        detail: '3 waiting on a person',
    },
    {
        label: 'Draft PRs',
        value: '11',
        detail: 'Labeled ai-built',
    },
    {
        label: 'Triage KB',
        value: '312',
        detail: '+21% past diagnoses',
    },
] as const;

const runtime = [
    { name: 'webhook-service', state: 'Up', tone: 'active' },
    { name: 'channel-router', state: 'Up', tone: 'active' },
    { name: 'bug-triage-agent', state: 'Running', tone: 'active' },
    { name: 'bug-fix-agent', state: 'Waiting', tone: 'attention' },
    { name: 'feature-agent', state: 'Running', tone: 'active' },
    { name: 'metrics-enrichment-agent', state: 'Scheduled', tone: 'quiet' },
    { name: 'support-rotation-agent', state: 'Idle', tone: 'quiet' },
] as const;

const queue: {
    key: string;
    work: string;
    intake: string;
    agent: string;
    status: JobStatus;
    confidence: number | null;
    updated: string;
}[] = [
    {
        key: 'ZD-1842',
        work: 'Payment webhook null intent',
        intake: 'Zendesk',
        agent: 'bug-triage',
        status: 'Investigating',
        confidence: 0.91,
        updated: '2m ago',
    },
    {
        key: 'SL-1831',
        work: 'Invoice PDF missing line items',
        intake: 'Slack',
        agent: 'bug-triage',
        status: 'Needs review',
        confidence: 0.54,
        updated: '18m ago',
    },
    {
        key: 'PROJ-441',
        work: 'Self-serve seat transfer',
        intake: 'Jira',
        agent: 'feature',
        status: 'Awaiting approval',
        confidence: null,
        updated: '41m ago',
    },
    {
        key: 'ZD-1794',
        work: 'Retry storm on checkout webhook',
        intake: 'Zendesk',
        agent: 'bug-fix',
        status: 'Reviewing',
        confidence: 0.88,
        updated: '2h ago',
    },
    {
        key: 'PROJ-438',
        work: 'SSO role mapping for contractors',
        intake: 'Jira',
        agent: 'feature',
        status: 'Developing',
        confidence: null,
        updated: '3h ago',
    },
    {
        key: 'ERR-2201',
        work: 'Null payment_intent after retry',
        intake: 'Error Reporting',
        agent: 'bug-fix',
        status: 'Merged',
        confidence: 0.93,
        updated: 'Yesterday',
    },
];

const reviews = [
    {
        key: 'SL-1831',
        title: 'Review diagnosis',
        detail: 'Confidence 0.54 is below 0.60. Accept or override before a fix starts.',
    },
    {
        key: 'PROJ-441',
        title: 'Approve feature plan',
        detail: 'Blocking questions on seat transfer. Add ai-implement-approved to continue.',
    },
    {
        key: 'ZD-1794',
        title: 'Review draft pull request',
        detail: 'Labeled ai-built. The agent opened the pull request. A person merges it.',
    },
] as const;

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
    <Head title="Dashboard" />

    <div class="flex h-full flex-1 flex-col gap-4 p-4 md:p-6">
        <div
            class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between"
        >
            <div class="max-w-3xl space-y-1">
                <h1
                    class="font-display text-2xl font-semibold tracking-tight text-foreground"
                >
                    Dashboard
                </h1>
                <p class="text-sm leading-relaxed text-muted-foreground">
                   Summary of the latest data from the system.
                </p>
            </div>
            <Badge
                variant="outline"
                class="w-fit shrink-0 font-mono text-[10px] tracking-wide uppercase"
            >
               UNIMARKET
            </Badge>
        </div>

        <section
            class="grid grid-cols-2 overflow-hidden rounded-lg border border-border bg-card lg:grid-cols-4"
            aria-label="Summary"
        >
            <div
                v-for="(metric, index) in metrics"
                :key="metric.label"
                :class="[
                    'px-4 py-3.5',
                    index === 0 || index === 2 ? 'border-r border-border' : '',
                    index === 1 ? 'border-border lg:border-r' : '',
                    index < 2 ? 'border-b border-border lg:border-b-0' : '',
                ]"
            >
                <p class="text-xs text-muted-foreground">{{ metric.label }}</p>
                <p
                    class="mt-1 font-display text-2xl font-semibold tracking-tight text-foreground tabular-nums"
                >
                    {{ metric.value }}
                </p>
                <p class="mt-1 text-xs text-muted-foreground">
                    {{ metric.detail }}
                </p>
            </div>
        </section>

        <section aria-labelledby="needs-review-heading">
            <h2
                id="needs-review-heading"
                class="mb-2 font-display text-base font-semibold tracking-tight"
            >
                Needs review
            </h2>
            <ul class="grid gap-3 md:grid-cols-3">
                <li
                    v-for="review in reviews"
                    :key="review.key"
                    class="rounded-lg border border-border border-l-2 border-l-gold-400 bg-card px-4 py-3"
                >
                    <div class="flex items-baseline justify-between gap-3">
                        <p class="text-sm font-medium">{{ review.title }}</p>
                        <span
                            class="shrink-0 font-mono text-xs text-muted-foreground"
                            >{{ review.key }}</span
                        >
                    </div>
                    <p class="mt-1 text-sm leading-relaxed text-muted-foreground">
                        {{ review.detail }}
                    </p>
                </li>
            </ul>
        </section>

        <section
            class="overflow-hidden rounded-lg border border-border bg-card"
            aria-labelledby="work-queue-heading"
        >
            <div class="border-b border-border px-4 py-3">
                <h2
                    id="work-queue-heading"
                    class="font-display text-base font-semibold tracking-tight"
                >
                    Work queue
                </h2>
                <p class="mt-0.5 text-sm text-muted-foreground">
                    Current jobs across intake, triage, fix, and feature.
                </p>
            </div>
            <ul class="divide-y divide-border lg:hidden">
                <li v-for="item in queue" :key="item.key" class="px-4 py-3">
                    <div class="flex items-center justify-between gap-3">
                        <span
                            class="font-mono text-xs text-muted-foreground"
                            >{{ item.key }}</span
                        >
                        <span class="text-xs text-muted-foreground">{{
                            item.intake
                        }}</span>
                    </div>
                    <p class="mt-1 font-medium">{{ item.work }}</p>
                    <div
                        class="mt-1.5 flex items-center justify-between gap-3"
                    >
                        <span class="inline-flex items-center gap-2 text-sm">
                            <span
                                class="size-1.5 shrink-0 rounded-full"
                                :class="toneClass[statusTone(item.status)]"
                            />
                            <span>{{ item.status }}</span>
                            <span
                                v-if="item.confidence !== null"
                                class="font-mono text-xs text-muted-foreground tabular-nums"
                            >
                                {{ item.confidence.toFixed(2) }}
                            </span>
                        </span>
                        <span
                            class="font-mono text-xs whitespace-nowrap text-muted-foreground"
                            >{{ item.agent }} · {{ item.updated }}</span
                        >
                    </div>
                </li>
            </ul>
            <div class="hidden overflow-x-auto lg:block">
                <table class="w-full text-left text-sm">
                    <thead
                        class="border-b border-border bg-muted/40 text-xs text-muted-foreground"
                    >
                        <tr>
                            <th class="px-4 py-2.5 font-medium whitespace-nowrap">
                                Key
                            </th>
                            <th class="px-4 py-2.5 font-medium">Work</th>
                            <th class="px-4 py-2.5 font-medium whitespace-nowrap">
                                Intake
                            </th>
                            <th class="px-4 py-2.5 font-medium whitespace-nowrap">
                                Status
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        <tr
                            v-for="item in queue"
                            :key="item.key"
                            class="hover:bg-muted/30"
                        >
                            <td
                                class="px-4 py-3 align-top font-mono text-xs whitespace-nowrap text-muted-foreground"
                            >
                                {{ item.key }}
                            </td>
                            <td class="px-4 py-3">
                                <p class="font-medium">{{ item.work }}</p>
                                <p
                                    class="mt-0.5 font-mono text-xs text-muted-foreground"
                                >
                                    {{ item.agent }} · {{ item.updated }}
                                </p>
                            </td>
                            <td
                                class="px-4 py-3 align-top whitespace-nowrap text-muted-foreground"
                            >
                                {{ item.intake }}
                            </td>
                            <td class="px-4 py-3 align-top whitespace-nowrap">
                                <span class="inline-flex items-center gap-2">
                                    <span
                                        class="size-1.5 shrink-0 rounded-full"
                                        :class="toneClass[statusTone(item.status)]"
                                    />
                                    <span>{{ item.status }}</span>
                                    <span
                                        v-if="item.confidence !== null"
                                        class="font-mono text-xs text-muted-foreground tabular-nums"
                                    >
                                        {{ item.confidence.toFixed(2) }}
                                    </span>
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <section
            class="rounded-lg border border-border bg-card px-4 py-3"
            aria-label="Runtime"
        >
            <h2 class="sr-only">Runtime</h2>
            <ul class="flex flex-wrap gap-2">
                <li
                    v-for="item in runtime"
                    :key="item.name"
                    class="inline-flex items-center gap-2 rounded-md bg-muted/50 px-2.5 py-1.5 text-sm"
                >
                    <span
                        class="size-1.5 shrink-0 rounded-full"
                        :class="toneClass[item.tone]"
                    />
                    <span class="font-medium">{{ item.name }}</span>
                    <span class="text-xs text-muted-foreground">{{
                        item.state
                    }}</span>
                </li>
            </ul>
        </section>
    </div>
</template>
