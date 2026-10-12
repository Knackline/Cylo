<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowRight, TrendingUp } from '@lucide/vue';
import { SidebarTrigger } from '@/components/ui/sidebar';
import {
    type JobStatus,
    sampleRuns,
} from '@/data/sampleRuns';
import { show as showRun } from '@/routes/runs';

defineOptions({
    layout: {
        breadcrumbs: [],
    },
});

type Tone = 'active' | 'attention' | 'quiet';

type MetricTrend = 'up' | 'attention' | 'neutral';

const toneDotClass: Record<Tone, string> = {
    active: 'bg-forest-500',
    attention: 'bg-gold-500',
    quiet: 'bg-stone-400',
};

const metrics = [
    {
        label: 'Runs',
        value: '128',
        hint: '+12% this week',
        trend: 'up' as const,
    },
    {
        label: 'Gates',
        value: '7',
        hint: '3 waiting on a person',
        trend: 'attention' as const,
    },
    {
        label: 'Draft PRs',
        value: '11',
        hint: 'Labeled ai-built',
        trend: 'neutral' as const,
    },
    {
        label: 'KB',
        value: '312',
        hint: '+21% diagnoses',
        trend: 'up' as const,
    },
] as const;

const metricHintClass: Record<MetricTrend, string> = {
    up: 'text-forest-600 dark:text-forest-300',
    attention: 'text-gold-700 dark:text-gold-300',
    neutral: 'text-muted-foreground',
};

const runtime = [
    { name: 'webhook-service', state: 'Up', tone: 'active' as const },
    { name: 'channel-router', state: 'Up', tone: 'active' as const },
    { name: 'bug-triage-agent', state: 'Running', tone: 'active' as const },
    { name: 'bug-fix-agent', state: 'Waiting', tone: 'attention' as const },
    { name: 'feature-agent', state: 'Running', tone: 'active' as const },
    {
        name: 'metrics-enrichment-agent',
        state: 'Scheduled',
        tone: 'quiet' as const,
    },
    { name: 'support-rotation-agent', state: 'Idle', tone: 'quiet' as const },
];

const queue = sampleRuns.map((run) => ({
    key: run.key,
    work: run.title,
    intake: run.intake,
    agent: run.agent,
    status: run.status,
    confidence: run.confidence,
    updated: run.updated,
}));

const reviews = [
    {
        key: 'SL-1831',
        title: 'Review diagnosis',
        detail: 'Confidence 0.54 is below 0.60. Accept or override before a fix starts.',
        action: 'Review',
    },
    {
        key: 'PROJ-441',
        title: 'Approve feature plan',
        detail: 'Blocking questions on seat transfer. Add ai-implement-approved to continue.',
        action: 'Approve',
    },
    {
        key: 'ZD-1794',
        title: 'Review draft pull request',
        detail: 'Labeled ai-built. The agent opened the pull request. A person merges it.',
        action: 'Open PR',
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

    <div class="flex h-full flex-1 flex-col">
        <header
            class="flex flex-col gap-4 border-b border-border px-4 py-4 md:flex-row md:items-center md:justify-between md:gap-6 md:px-6"
        >
            <div class="flex min-w-0 items-start gap-2">
                <SidebarTrigger class="mt-0.5 -ml-1" />
                <div class="min-w-0">
                    <h1
                        class="text-lg font-semibold tracking-tight text-foreground"
                    >
                        Dashboard
                    </h1>
                    <p class="mt-0.5 text-sm text-muted-foreground">
                        Gates and live work across intake → triage → draft PR.
                    </p>
                </div>
            </div>

            <dl
                class="grid w-full grid-cols-2 overflow-hidden rounded-lg border border-border bg-card sm:w-auto sm:grid-cols-4"
                aria-label="Summary"
            >
                <div
                    v-for="(metric, index) in metrics"
                    :key="metric.label"
                    :class="[
                        'flex min-w-0 flex-col gap-1 px-4 py-3 sm:min-w-32',
                        index % 2 === 0 ? 'border-r border-border' : '',
                        index < 2 ? 'border-b border-border sm:border-b-0' : '',
                        index < 3 ? 'sm:border-r sm:border-border' : '',
                        metric.trend === 'attention' ? 'bg-accent/50' : '',
                    ]"
                >
                    <dt
                        class="text-[11px] font-medium tracking-wide text-muted-foreground uppercase"
                    >
                        {{ metric.label }}
                    </dt>
                    <dd class="flex flex-col gap-0.5">
                        <span
                            class="text-2xl font-semibold tracking-tight text-foreground tabular-nums"
                        >
                            {{ metric.value }}
                        </span>
                        <span
                            class="inline-flex items-center gap-1 text-xs"
                            :class="metricHintClass[metric.trend]"
                        >
                            <TrendingUp
                                v-if="metric.trend === 'up'"
                                class="size-3 shrink-0"
                                aria-hidden="true"
                            />
                            <span class="truncate">{{ metric.hint }}</span>
                        </span>
                    </dd>
                </div>
            </dl>
        </header>

        <div class="flex flex-1 flex-col px-4 py-5 md:px-6">
            <div
                class="overflow-hidden rounded-md border border-border bg-card"
            >
                <section aria-labelledby="needs-review-heading">
                    <div
                        class="flex items-baseline justify-between gap-3 border-b border-border bg-muted/30 px-4 py-2.5"
                    >
                        <h2
                            id="needs-review-heading"
                            class="text-sm font-semibold text-foreground"
                        >
                            Needs review
                        </h2>
                        <p class="text-xs text-muted-foreground">
                            {{ reviews.length }} open · waiting on a person
                        </p>
                    </div>

                    <ul>
                        <li
                            v-for="(review, index) in reviews"
                            :key="review.key"
                            :class="index > 0 ? 'border-t border-border' : ''"
                        >
                            <Link
                                :href="showRun(review.key)"
                                class="group flex flex-col gap-3 px-4 py-3 transition-colors duration-150 hover:bg-muted/40 sm:flex-row sm:items-center sm:justify-between"
                            >
                                <div
                                    class="min-w-0 sm:flex sm:min-w-0 sm:items-center sm:gap-4"
                                >
                                    <span
                                        class="inline-block w-20 shrink-0 font-mono text-xs text-muted-foreground"
                                        >{{ review.key }}</span
                                    >
                                    <div class="min-w-0">
                                        <p
                                            class="text-sm font-medium text-foreground"
                                        >
                                            {{ review.title }}
                                        </p>
                                        <p
                                            class="mt-0.5 text-sm text-muted-foreground sm:truncate"
                                        >
                                            {{ review.detail }}
                                        </p>
                                    </div>
                                </div>
                                <span
                                    class="inline-flex h-8 shrink-0 items-center gap-1.5 self-start rounded-md bg-primary px-3 text-xs font-medium text-primary-foreground transition-colors duration-150 group-hover:bg-forest-600 sm:self-center"
                                >
                                    {{ review.action }}
                                    <ArrowRight
                                        class="size-3.5"
                                        aria-hidden="true"
                                    />
                                </span>
                            </Link>
                        </li>
                    </ul>
                </section>

                <section
                    class="border-t border-border"
                    aria-labelledby="work-queue-heading"
                >
                    <div
                        class="flex items-baseline justify-between gap-3 border-b border-border bg-muted/30 px-4 py-2.5"
                    >
                        <h2
                            id="work-queue-heading"
                            class="text-sm font-semibold text-foreground"
                        >
                            Work queue
                        </h2>
                        <p class="text-xs text-muted-foreground">
                            {{ queue.length }} jobs
                        </p>
                    </div>

                    <ul class="divide-y divide-border lg:hidden">
                        <li
                            v-for="item in queue"
                            :key="item.key"
                            :class="
                                statusTone(item.status) === 'attention'
                                    ? 'bg-accent/40'
                                    : ''
                            "
                        >
                            <Link
                                :href="showRun(item.key)"
                                class="block px-4 py-3 transition-colors duration-150 hover:bg-muted/40"
                            >
                                <div
                                    class="flex items-center justify-between gap-3"
                                >
                                    <span
                                        class="font-mono text-xs text-muted-foreground"
                                        >{{ item.key }}</span
                                    >
                                    <span
                                        class="inline-flex items-center gap-1.5 text-xs text-foreground"
                                    >
                                        <span
                                            class="size-1.5 rounded-full"
                                            :class="
                                                toneDotClass[
                                                    statusTone(item.status)
                                                ]
                                            "
                                            aria-hidden="true"
                                        />
                                        {{ item.status }}
                                    </span>
                                </div>
                                <p
                                    class="mt-1 text-sm font-medium text-foreground"
                                >
                                    {{ item.work }}
                                </p>
                                <p
                                    class="mt-0.5 font-mono text-xs text-muted-foreground"
                                >
                                    {{ item.intake }} · {{ item.agent }} ·
                                    {{ item.updated }}
                                </p>
                            </Link>
                        </li>
                    </ul>

                    <div class="hidden overflow-x-auto lg:block">
                        <table class="w-full text-left text-sm">
                            <thead class="text-xs text-muted-foreground">
                                <tr class="border-b border-border">
                                    <th
                                        class="px-4 py-2 font-medium whitespace-nowrap"
                                    >
                                        Key
                                    </th>
                                    <th class="px-4 py-2 font-medium">Work</th>
                                    <th
                                        class="px-4 py-2 font-medium whitespace-nowrap"
                                    >
                                        Intake
                                    </th>
                                    <th
                                        class="px-4 py-2 font-medium whitespace-nowrap"
                                    >
                                        Status
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border">
                                <tr
                                    v-for="item in queue"
                                    :key="item.key"
                                    :class="
                                        statusTone(item.status) === 'attention'
                                            ? 'bg-accent/40'
                                            : ''
                                    "
                                >
                                    <td
                                        class="px-4 py-2.5 align-middle font-mono text-xs whitespace-nowrap text-muted-foreground"
                                    >
                                        <Link
                                            :href="showRun(item.key)"
                                            class="block hover:text-foreground"
                                        >
                                            {{ item.key }}
                                        </Link>
                                    </td>
                                    <td class="max-w-md px-4 py-2.5 align-middle">
                                        <Link
                                            :href="showRun(item.key)"
                                            class="block transition-colors duration-150 hover:bg-muted/40"
                                        >
                                            <p
                                                class="font-medium text-foreground"
                                            >
                                                {{ item.work }}
                                            </p>
                                            <p
                                                class="mt-0.5 font-mono text-xs text-muted-foreground"
                                            >
                                                {{ item.agent }} ·
                                                {{ item.updated }}
                                                <template
                                                    v-if="
                                                        item.confidence !== null
                                                    "
                                                >
                                                    ·
                                                    <span
                                                        :class="
                                                            item.confidence <
                                                            0.6
                                                                ? 'text-gold-700 dark:text-gold-300'
                                                                : ''
                                                        "
                                                    >
                                                        {{
                                                            item.confidence.toFixed(
                                                                2,
                                                            )
                                                        }}
                                                    </span>
                                                </template>
                                            </p>
                                        </Link>
                                    </td>
                                    <td
                                        class="px-4 py-2.5 align-middle whitespace-nowrap"
                                    >
                                        <span
                                            class="rounded border border-border bg-muted/40 px-1.5 py-0.5 font-mono text-[10px] tracking-wide text-muted-foreground uppercase"
                                        >
                                            {{ item.intake }}
                                        </span>
                                    </td>
                                    <td
                                        class="px-4 py-2.5 align-middle whitespace-nowrap"
                                    >
                                        <span
                                            class="inline-flex items-center gap-2 text-sm text-foreground"
                                        >
                                            <span
                                                class="size-1.5 shrink-0 rounded-full"
                                                :class="
                                                    toneDotClass[
                                                        statusTone(item.status)
                                                    ]
                                                "
                                                aria-hidden="true"
                                            />
                                            {{ item.status }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>

        <footer
            class="mt-auto border-t border-border bg-muted/20 px-4 py-2.5 md:px-6"
            aria-label="Runtime"
        >
            <ul
                class="flex flex-wrap items-center gap-x-4 gap-y-1.5 text-xs text-muted-foreground"
            >
                <li
                    class="font-medium tracking-wide text-muted-foreground uppercase"
                >
                    Runtime
                </li>
                <li
                    v-for="item in runtime"
                    :key="item.name"
                    class="inline-flex items-center gap-1.5"
                >
                    <span
                        class="size-1.5 shrink-0 rounded-full"
                        :class="toneDotClass[item.tone]"
                        aria-hidden="true"
                    />
                    <span class="font-mono text-foreground">{{
                        item.name
                    }}</span>
                    <span>{{ item.state }}</span>
                </li>
            </ul>
        </footer>
    </div>
</template>
