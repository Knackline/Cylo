<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { SidebarTrigger } from '@/components/ui/sidebar';

defineOptions({
    layout: {
        breadcrumbs: [],
    },
});

const metrics = [
    { label: 'Plan', value: 'Team', hint: 'Renews 1 Nov 2026' },
    { label: 'Runs', value: '128 / 500', hint: 'This period' },
    { label: 'Draft PRs', value: '11', hint: 'ai-built' },
] as const;

const invoices = [
    {
        date: '1 Sep 2026',
        plan: 'Team',
        amount: '49.00 USD',
        status: 'Paid',
    },
    {
        date: '1 Oct 2026',
        plan: 'Team',
        amount: '49.00 USD',
        status: 'Paid',
    },
] as const;
</script>

<template>
    <Head title="Billing" />

    <div class="flex h-full flex-1 flex-col">
        <header
            class="flex flex-col gap-4 border-b border-border px-4 py-4 md:flex-row md:items-center md:justify-between md:px-6"
        >
            <div class="flex min-w-0 items-start gap-2">
                <SidebarTrigger class="mt-0.5 -ml-1" />
                <div class="min-w-0">
                    <h1 class="text-lg font-semibold tracking-tight text-foreground">
                        Billing
                    </h1>
                    <p class="mt-0.5 text-sm text-muted-foreground">
                        Sample plan and usage. No payment method is attached.
                    </p>
                </div>
            </div>

            <dl
                class="grid grid-cols-3 gap-px overflow-hidden rounded-md border border-border bg-border sm:flex sm:w-auto sm:shrink-0"
                aria-label="Summary"
            >
                <div
                    v-for="metric in metrics"
                    :key="metric.label"
                    class="flex min-w-0 flex-col bg-card px-3 py-2 sm:min-w-28"
                >
                    <dt
                        class="truncate text-[10px] font-medium tracking-wide text-muted-foreground uppercase"
                    >
                        {{ metric.label }}
                    </dt>
                    <dd class="mt-0.5 flex items-baseline gap-1.5">
                        <span
                            class="text-lg font-semibold tabular-nums text-foreground"
                            >{{ metric.value }}</span
                        >
                        <span
                            class="truncate text-[11px] text-muted-foreground"
                            >{{ metric.hint }}</span
                        >
                    </dd>
                </div>
            </dl>
        </header>

        <div class="flex flex-1 flex-col gap-6 px-4 py-5 md:px-6">
            <section aria-labelledby="invoices-heading">
                <div class="mb-2 flex items-baseline justify-between gap-3">
                    <h2
                        id="invoices-heading"
                        class="text-sm font-semibold text-foreground"
                    >
                        Invoices
                    </h2>
                    <p class="text-xs text-muted-foreground">
                        Renewal 1 Nov 2026
                    </p>
                </div>

                <div class="overflow-hidden rounded-md border border-border">
                    <ul class="divide-y divide-border">
                        <li
                            v-for="invoice in invoices"
                            :key="invoice.date"
                            class="flex flex-wrap items-center justify-between gap-2 px-4 py-3 text-sm"
                        >
                            <div class="min-w-0">
                                <p class="font-medium text-foreground">
                                    {{ invoice.date }}
                                </p>
                                <p class="mt-0.5 text-xs text-muted-foreground">
                                    {{ invoice.plan }} · {{ invoice.status }}
                                </p>
                            </div>
                            <span
                                class="font-mono text-xs tabular-nums text-foreground"
                            >
                                {{ invoice.amount }}
                            </span>
                        </li>
                    </ul>
                </div>
            </section>
        </div>
    </div>
</template>
