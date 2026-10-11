<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import {
    Bot,
    CircleAlert,
    GitPullRequest,
    Hourglass,
    Radio,
    TrendingUp,
} from '@lucide/vue';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import { Badge } from '@/components/ui/badge';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Progress } from '@/components/ui/progress';
import { Separator } from '@/components/ui/separator';
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

type RunStatus =
    | 'triaging'
    | 'awaiting_gate'
    | 'implementing'
    | 'draft_pr'
    | 'merged';

type GateKind = 'confidence' | 'plan' | 'merge';

const metrics = [
    {
        label: 'Agent runs',
        value: '128',
        delta: '+12%',
        detail: 'Structured events this week',
        icon: Radio,
    },
    {
        label: 'Awaiting gates',
        value: '7',
        delta: '3 urgent',
        detail: 'Human review before the next step',
        icon: Hourglass,
    },
    {
        label: 'Draft PRs',
        value: '11',
        delta: 'built',
        detail: 'Agents propose — you merge',
        icon: GitPullRequest,
    },
    {
        label: 'Diagnosis KB',
        value: '312',
        delta: '+21%',
        detail: 'Searchable cases for next triage',
        icon: TrendingUp,
    },
] as const;

const runs: {
    id: string;
    signal: string;
    source: string;
    path: 'Bug' | 'Feature';
    agent: string;
    status: RunStatus;
    confidence: number | null;
    updated: string;
}[] = [
    {
        id: 'run_18f2',
        signal: 'Payment webhook null intent',
        source: 'Sentry',
        path: 'Bug',
        agent: 'Triage',
        status: 'triaging',
        confidence: 0.91,
        updated: '2m ago',
    },
    {
        id: 'run_17c9',
        signal: 'Invoice PDF missing line items',
        source: 'Slack',
        path: 'Bug',
        agent: 'Fix',
        status: 'awaiting_gate',
        confidence: 0.54,
        updated: '18m ago',
    },
    {
        id: 'run_16a1',
        signal: 'Self-serve seat transfer',
        source: 'Linear',
        path: 'Feature',
        agent: 'Feature',
        status: 'awaiting_gate',
        confidence: null,
        updated: '41m ago',
    },
    {
        id: 'run_15e4',
        signal: 'Retry storm on checkout webhook',
        source: 'Ticket',
        path: 'Bug',
        agent: 'Fix',
        status: 'draft_pr',
        confidence: 0.88,
        updated: '2h ago',
    },
    {
        id: 'run_14b7',
        signal: 'SSO role mapping for contractors',
        source: 'Linear',
        path: 'Feature',
        agent: 'Feature',
        status: 'implementing',
        confidence: null,
        updated: '3h ago',
    },
    {
        id: 'run_13d2',
        signal: 'Null payment_intent after retry',
        source: 'Sentry',
        path: 'Bug',
        agent: 'Metrics',
        status: 'merged',
        confidence: 0.93,
        updated: 'Yesterday',
    },
];

const gates: {
    id: string;
    kind: GateKind;
    runId: string;
    title: string;
    detail: string;
    severity: 'high' | 'medium';
}[] = [
    {
        id: 'gate_01',
        kind: 'confidence',
        runId: 'run_17c9',
        title: 'Review low-confidence diagnosis',
        detail: 'Confidence 0.54 · needs_human_review before Fix starts',
        severity: 'high',
    },
    {
        id: 'gate_02',
        kind: 'plan',
        runId: 'run_16a1',
        title: 'Approve feature plan',
        detail: 'Two blocking questions on seat transfer edge cases',
        severity: 'high',
    },
    {
        id: 'gate_03',
        kind: 'merge',
        runId: 'run_15e4',
        title: 'Merge draft PR',
        detail: 'PR #842 · labeled built · agents never promote',
        severity: 'medium',
    },
];

const agents = [
    { name: 'Router', status: 'idle', detail: 'Auth · classify · dispatch' },
    { name: 'Triage', status: 'active', detail: '1 live diagnosis' },
    { name: 'Fix', status: 'hold', detail: 'Awaiting gate' },
    { name: 'Feature', status: 'active', detail: 'Implement in progress' },
    { name: 'Metrics', status: 'idle', detail: 'Learn loop ready' },
] as const;

const statusLabel: Record<RunStatus, string> = {
    triaging: 'Triage',
    awaiting_gate: 'Gate',
    implementing: 'Implement',
    draft_pr: 'Draft PR',
    merged: 'Merged',
};

const gateLabel: Record<GateKind, string> = {
    confidence: 'Confidence',
    plan: 'Plan',
    merge: 'Merge',
};
</script>

<template>
    <Head title="Dashboard" />

    <div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto p-4 md:p-6">
        <Alert
            class="border-gold/35 bg-gold-50 text-foreground dark:border-gold/30 dark:bg-card"
        >
            <CircleAlert class="size-4 text-gold-700 dark:text-gold-400" />
            <AlertTitle class="font-display">Mock control plane</AlertTitle>
            <AlertDescription class="text-muted-foreground">
                Sample runs, gates, and agent activity for demos and local
                development. Live integrations will replace this data.
            </AlertDescription>
        </Alert>

        <div class="flex flex-col gap-1">
            <h1
                class="font-display text-3xl font-semibold tracking-tight text-foreground"
            >
                Dashboard
            </h1>
            <p class="text-sm leading-relaxed text-muted-foreground">
                Signals in. Specialized agents out. You own the gates.
            </p>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <Card
                v-for="metric in metrics"
                :key="metric.label"
                class="border-border/80 bg-card shadow-none"
            >
                <CardHeader class="pb-2">
                    <div class="flex items-start justify-between gap-3">
                        <CardDescription class="font-mono text-[11px] tracking-wide uppercase">{{
                            metric.label
                        }}</CardDescription>
                        <component
                            :is="metric.icon"
                            class="size-4 text-muted-foreground"
                        />
                    </div>
                    <CardTitle
                        class="font-display text-3xl font-semibold tracking-tight text-foreground tabular-nums"
                    >
                        {{ metric.value }}
                    </CardTitle>
                </CardHeader>
                <CardContent class="space-y-1">
                    <Badge
                        variant="outline"
                        class="border-gold/50 font-mono text-[10px] tracking-wide text-gold-700 uppercase dark:border-gold-400/40 dark:text-gold-400"
                    >
                        {{ metric.delta }}
                    </Badge>
                    <p class="text-sm text-muted-foreground">
                        {{ metric.detail }}
                    </p>
                </CardContent>
            </Card>
        </div>

        <div class="grid gap-4 xl:grid-cols-[minmax(0,1.4fr)_minmax(0,1fr)]">
            <Card class="border-border/80 bg-card shadow-none">
                <CardHeader>
                    <CardTitle class="font-display text-lg tracking-tight"
                        >Recent runs</CardTitle
                    >
                    <CardDescription>
                        Inspectable agent cycles from tickets, Slack, issues, and
                        errors.
                    </CardDescription>
                </CardHeader>
                <CardContent class="space-y-0 p-0">
                    <ul class="divide-y divide-border">
                        <li
                            v-for="run in runs"
                            :key="run.id"
                            class="flex flex-col gap-3 px-6 py-4 sm:flex-row sm:items-center sm:justify-between"
                        >
                            <div class="min-w-0 space-y-1">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span
                                        class="font-mono text-xs text-muted-foreground"
                                        >{{ run.id }}</span
                                    >
                                    <Badge variant="outline" class="font-mono text-[10px]">
                                        {{ run.source }}
                                    </Badge>
                                    <Badge
                                        variant="secondary"
                                        class="font-mono text-[10px]"
                                    >
                                        {{ run.path }}
                                    </Badge>
                                </div>
                                <p class="truncate font-medium">
                                    {{ run.signal }}
                                </p>
                                <p class="font-mono text-xs text-muted-foreground">
                                    {{ run.agent }} · {{ run.updated }}
                                </p>
                            </div>

                            <div
                                class="flex shrink-0 flex-col items-start gap-2 sm:items-end"
                            >
                                <Badge
                                    :variant="
                                        run.status === 'awaiting_gate'
                                            ? 'default'
                                            : 'outline'
                                    "
                                    class="font-mono text-[10px] tracking-wide uppercase"
                                >
                                    {{ statusLabel[run.status] }}
                                </Badge>
                                <div
                                    v-if="run.confidence !== null"
                                    class="flex w-36 items-center gap-2"
                                >
                                    <Progress
                                        :model-value="Math.round(run.confidence * 100)"
                                        class="h-1.5"
                                    />
                                    <span
                                        class="font-mono text-[10px] tabular-nums text-muted-foreground"
                                    >
                                        {{ run.confidence.toFixed(2) }}
                                    </span>
                                </div>
                            </div>
                        </li>
                    </ul>
                </CardContent>
            </Card>

            <div class="flex flex-col gap-4">
                <Card class="border-border/80 bg-card shadow-none">
                    <CardHeader>
                        <CardTitle class="font-display text-lg tracking-tight"
                            >Human gates</CardTitle
                        >
                        <CardDescription>
                            Agents stop here. You resume, revise, or merge.
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-3">
                        <div
                            v-for="gate in gates"
                            :key="gate.id"
                            class="rounded-lg border border-border p-4"
                        >
                            <div class="flex flex-wrap items-center gap-2">
                                <Badge
                                    variant="outline"
                                    class="font-mono text-[10px] tracking-wide uppercase"
                                >
                                    {{ gateLabel[gate.kind] }}
                                </Badge>
                                <Badge
                                    v-if="gate.severity === 'high'"
                                    class="bg-gold-400 text-forest hover:bg-gold-300"
                                >
                                    Needs you
                                </Badge>
                                <span
                                    class="font-mono text-xs text-muted-foreground"
                                    >{{ gate.runId }}</span
                                >
                            </div>
                            <p class="mt-2 font-medium">{{ gate.title }}</p>
                            <p class="mt-1 text-sm text-muted-foreground">
                                {{ gate.detail }}
                            </p>
                        </div>
                    </CardContent>
                </Card>

                <Card class="border-border/80 bg-card shadow-none">
                    <CardHeader>
                        <CardTitle class="font-display text-lg tracking-tight"
                            >Agent desk</CardTitle
                        >
                        <CardDescription>
                            Thin router. Specialists own the work.
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-3">
                        <div
                            v-for="(agent, index) in agents"
                            :key="agent.name"
                        >
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex items-center gap-2.5">
                                    <span
                                        class="flex size-8 items-center justify-center rounded-md border border-border bg-muted/40"
                                    >
                                        <Bot class="size-4 text-muted-foreground" />
                                    </span>
                                    <div>
                                        <p class="text-sm font-medium">
                                            {{ agent.name }}
                                        </p>
                                        <p
                                            class="font-mono text-[11px] text-muted-foreground"
                                        >
                                            {{ agent.detail }}
                                        </p>
                                    </div>
                                </div>
                                <span
                                    :class="[
                                        'font-mono text-[10px] tracking-wide uppercase',
                                        agent.status === 'active'
                                            ? 'text-gold-700 dark:text-gold-400'
                                            : agent.status === 'hold'
                                              ? 'text-gold-600 dark:text-gold-400/80'
                                              : 'text-muted-foreground',
                                    ]"
                                >
                                    {{ agent.status }}
                                </span>
                            </div>
                            <Separator
                                v-if="index < agents.length - 1"
                                class="mt-3"
                            />
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </div>
</template>
