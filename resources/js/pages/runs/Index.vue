<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
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

const toneDotClass: Record<Tone, string> = {
    active: 'bg-forest-500',
    attention: 'bg-gold-500',
    quiet: 'bg-stone-400',
};

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
    <Head title="Runs" />

    <div class="flex h-full flex-1 flex-col">
        <header
            class="flex flex-col gap-4 border-b border-border px-4 py-4 md:flex-row md:items-center md:justify-between md:px-6"
        >
            <div class="flex min-w-0 items-start gap-2">
                <SidebarTrigger class="mt-0.5 -ml-1" />
                <div class="min-w-0">
                    <h1 class="text-lg font-semibold tracking-tight text-foreground">
                        Runs
                    </h1>
                    <p class="mt-0.5 text-sm text-muted-foreground">
                        How each run was triggered, which agent handled it, and
                        where it landed.
                    </p>
                </div>
            </div>
        </header>

        <div class="flex flex-1 flex-col gap-6 px-4 py-5 md:px-6">
            <section aria-labelledby="runs-heading">
                <div class="mb-2 flex items-baseline justify-between gap-3">
                    <h2
                        id="runs-heading"
                        class="text-sm font-semibold text-foreground"
                    >
                        All runs
                    </h2>
                    <p class="text-xs text-muted-foreground">
                        {{ sampleRuns.length }} runs
                    </p>
                </div>

                <div class="overflow-hidden rounded-md border border-border">
                    <ul class="divide-y divide-border lg:hidden">
                        <li
                            v-for="item in sampleRuns"
                            :key="item.key"
                            :class="[
                                statusTone(item.status) === 'attention'
                                    ? 'bg-muted/35'
                                    : '',
                            ]"
                        >
                            <Link
                                :href="showRun(item.key)"
                                class="block px-4 py-3 transition-colors duration-150 hover:bg-muted/50"
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
                                    {{ item.title }}
                                </p>
                                <p
                                    class="mt-0.5 font-mono text-xs text-muted-foreground"
                                >
                                    {{ item.intake }} · {{ item.agent }} ·
                                    {{ item.updated }}
                                    <template v-if="item.confidence !== null">
                                        · {{ item.confidence.toFixed(2) }}
                                    </template>
                                </p>
                            </Link>
                        </li>
                    </ul>

                    <div class="hidden overflow-x-auto lg:block">
                        <table class="w-full text-left text-sm">
                            <thead
                                class="border-b border-border text-xs text-muted-foreground"
                            >
                                <tr>
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
                                    v-for="item in sampleRuns"
                                    :key="item.key"
                                    :class="[
                                        'transition-colors duration-150 hover:bg-muted/50',
                                        statusTone(item.status) === 'attention'
                                            ? 'bg-muted/35'
                                            : '',
                                    ]"
                                >
                                    <td
                                        class="px-4 py-2.5 align-middle font-mono text-xs whitespace-nowrap text-muted-foreground"
                                    >
                                        <Link
                                            :href="showRun(item.key)"
                                            class="hover:text-foreground"
                                        >
                                            {{ item.key }}
                                        </Link>
                                    </td>
                                    <td class="px-4 py-2.5 align-middle">
                                        <Link
                                            :href="showRun(item.key)"
                                            class="block"
                                        >
                                            <p
                                                class="font-medium text-foreground"
                                            >
                                                {{ item.title }}
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
                                                    {{
                                                        item.confidence.toFixed(
                                                            2,
                                                        )
                                                    }}
                                                </template>
                                            </p>
                                        </Link>
                                    </td>
                                    <td
                                        class="px-4 py-2.5 align-middle text-xs whitespace-nowrap text-muted-foreground"
                                    >
                                        {{ item.intake }}
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
                </div>
            </section>
        </div>
    </div>
</template>
