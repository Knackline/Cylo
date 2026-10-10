<script setup lang="ts">
import { Progress } from '@/components/ui/progress';

const agents = [
    { name: 'Router', status: 'done', detail: 'Classified · bug' },
    { name: 'Triage', status: 'active', detail: 'Diagnosis 0.91' },
    { name: 'Fix', status: 'queued', detail: 'Awaiting gate' },
    { name: 'Merge', status: 'hold', detail: 'Human required' },
] as const;

const evidence = [
    'Null payment_intent after webhook retry',
    'Regressed in PR #812',
    'Similar case · PROJ-1184',
] as const;
</script>

<template>
    <div
        class="landing-hero-visual relative w-full"
        aria-hidden="true"
    >
        <div
            class="landing-hero-visual__shell relative overflow-hidden rounded-xl border border-ivory/15 bg-forest-950/80 shadow-[0_0_0_1px_rgba(193,154,58,0.12)]"
        >
            <div
                class="flex items-center justify-between gap-3 border-b border-ivory/10 px-4 py-3"
            >
                <div class="flex items-center gap-3">
                    <span
                        class="landing-hero-visual__live inline-flex h-2 w-2 rounded-full bg-gold"
                    />
                    <span class="font-mono text-xs text-ivory/80">run_18f2</span>
                    <span
                        class="rounded border border-gold/35 px-2 py-0.5 font-mono text-[10px] tracking-wider text-gold uppercase"
                    >
                        Triage
                    </span>
                </div>
                <span class="font-mono text-[10px] tracking-wider text-ivory/40 uppercase">
                    Live cycle
                </span>
            </div>

            <div class="grid gap-0 lg:grid-cols-[11rem_1fr]">
                <div class="border-b border-ivory/10 lg:border-r lg:border-b-0">
                    <p
                        class="border-b border-ivory/10 px-4 py-2.5 font-mono text-[10px] tracking-[0.18em] text-ivory/40 uppercase"
                    >
                        Agents
                    </p>
                    <ul class="divide-y divide-ivory/10">
                        <li
                            v-for="(agent, index) in agents"
                            :key="agent.name"
                            :class="[
                                'landing-hero-visual__agent flex flex-col gap-0.5 px-4 py-3',
                                `landing-hero-visual__agent--${index}`,
                            ]"
                        >
                            <div class="flex items-center justify-between gap-2">
                                <span class="font-display text-sm text-ivory">{{
                                    agent.name
                                }}</span>
                                <span
                                    :class="[
                                        'font-mono text-[10px] uppercase',
                                        agent.status === 'active'
                                            ? 'text-gold'
                                            : agent.status === 'hold'
                                              ? 'text-gold/80'
                                              : agent.status === 'done'
                                                ? 'text-forest-200'
                                                : 'text-ivory/40',
                                    ]"
                                >
                                    {{ agent.status }}
                                </span>
                            </div>
                            <span class="font-mono text-[11px] text-ivory/45">{{
                                agent.detail
                            }}</span>
                        </li>
                    </ul>
                </div>

                <div class="flex flex-col">
                    <div class="border-b border-ivory/10 px-4 py-4 sm:px-5">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p
                                    class="font-mono text-[10px] tracking-[0.18em] text-ivory/40 uppercase"
                                >
                                    Signal
                                </p>
                                <p class="mt-1 font-display text-lg text-ivory">
                                    Payment webhook null intent
                                </p>
                            </div>
                            <span
                                class="shrink-0 rounded border border-ivory/15 px-2 py-1 font-mono text-[10px] text-ivory/55"
                            >
                                slack · #incidents
                            </span>
                        </div>

                        <div class="mt-5">
                            <div class="mb-2 flex items-center justify-between">
                                <p
                                    class="font-mono text-[10px] tracking-[0.18em] text-ivory/40 uppercase"
                                >
                                    Confidence
                                </p>
                                <p class="font-display text-xl text-ivory">0.91</p>
                            </div>
                            <Progress
                                :model-value="91"
                                class="landing-hero-visual__progress h-2 bg-ivory/10"
                            />
                            <p class="mt-2 font-mono text-[11px] text-ivory/45">
                                Gate clear · Fix may start
                            </p>
                        </div>
                    </div>

                    <div class="grow px-4 py-4 sm:px-5">
                        <p
                            class="font-mono text-[10px] tracking-[0.18em] text-ivory/40 uppercase"
                        >
                            Evidence
                        </p>
                        <ul class="mt-3 space-y-2">
                            <li
                                v-for="(item, index) in evidence"
                                :key="item"
                                :class="[
                                    'landing-hero-visual__evidence rounded-md border border-ivory/10 bg-ivory/[0.03] px-3 py-2.5 font-mono text-xs text-ivory/70',
                                    `landing-hero-visual__evidence--${index}`,
                                ]"
                            >
                                {{ item }}
                            </li>
                        </ul>
                    </div>

                    <div
                        class="mt-auto flex items-center justify-between gap-3 border-t border-ivory/10 px-4 py-3 sm:px-5"
                    >
                        <p class="font-mono text-[10px] tracking-wider text-ivory/40 uppercase">
                            Next · Draft PR
                        </p>
                        <p class="font-mono text-[10px] tracking-wider text-gold uppercase">
                            Human merge required
                        </p>
                    </div>
                </div>
            </div>

            <div
                class="landing-hero-visual__scan pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-gold/70 to-transparent"
            />
        </div>
    </div>
</template>
