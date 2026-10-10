<script setup lang="ts">
import { ArrowDown, ArrowRight } from '@lucide/vue';
import LandingSection from '@/components/landing/LandingSection.vue';
import LandingSectionHeader from '@/components/landing/LandingSectionHeader.vue';
import { Badge } from '@/components/ui/badge';
import { Separator } from '@/components/ui/separator';

const routerJobs = [
    { id: 'auth', label: 'Auth', detail: 'Verify the webhook or actor' },
    { id: 'classify', label: 'Classify', detail: 'Bug, feature, or metrics' },
    { id: 'dispatch', label: 'Dispatch', detail: 'Hand off to one specialist' },
] as const;

const specialists = [
    {
        id: 'triage',
        name: 'Triage',
        path: 'Bug',
        verb: 'Diagnose',
        summary:
            'Evidence, confidence, and human-review flags — not a chat reply.',
        accepts: 'Bug job',
        emits: 'Diagnosis JSON',
        rule: 'Gate Fix below 0.85 or needs_human_review',
    },
    {
        id: 'fix',
        name: 'Fix',
        path: 'Bug',
        verb: 'Implement',
        summary: 'TDD against the diagnosis. Opens a draft PR. Stops there.',
        accepts: 'Diagnosis ≥ gate',
        emits: 'Draft PR · built',
        rule: 'Agents never merge',
    },
    {
        id: 'feature',
        name: 'Feature',
        path: 'Feature',
        verb: 'Plan → build',
        summary:
            'Plans first. Implements only after revise or implement-approved.',
        accepts: 'Feature job',
        emits: 'Plan · Draft PR',
        rule: 'Human approves the plan',
    },
    {
        id: 'metrics',
        name: 'Metrics',
        path: 'Learn',
        verb: 'Close the loop',
        summary:
            'Outcomes and diagnosis KB entries so the next triage is sharper.',
        accepts: 'Merged draft PR',
        emits: 'Learn signal',
        rule: 'Feeds the next run',
    },
] as const;
</script>

<template>
    <div>
        <LandingSection
            id="agents"
            tone="ivory"
            :bordered="false"
            labelled-by="agents-heading"
        >
            <LandingSectionHeader
                eyebrow="Agents"
                title="Specialized workers. Thin router."
                title-id="agents-heading"
                description="The gateway stays small on purpose. Specialists own the business logic. You get inspectable runs — not a blank chat."
            />

            <!-- Topology: signals → thin router → specialists -->
            <div class="landing-agents mt-12 space-y-4">
                <div
                    class="flex flex-wrap items-center justify-center gap-2 font-mono text-[11px] tracking-wide text-muted-foreground uppercase sm:gap-3"
                >
                    <span
                        class="rounded-md border border-border bg-white px-3 py-1.5"
                        >Tickets</span
                    >
                    <span
                        class="rounded-md border border-border bg-white px-3 py-1.5"
                        >Slack</span
                    >
                    <span
                        class="rounded-md border border-border bg-white px-3 py-1.5"
                        >Issues</span
                    >
                    <span
                        class="rounded-md border border-border bg-white px-3 py-1.5"
                        >Errors</span
                    >
                </div>

                <div
                    class="flex justify-center text-muted-foreground"
                    aria-hidden="true"
                >
                    <ArrowDown class="size-4" />
                </div>

                <!-- Thin router strip -->
                <div
                    class="landing-agents__router overflow-hidden rounded-xl border border-forest bg-forest text-ivory"
                >
                    <div
                        class="flex flex-wrap items-center justify-between gap-3 border-b border-ivory/10 px-5 py-3"
                    >
                        <div class="flex flex-wrap items-center gap-3">
                            <p class="font-display text-lg font-semibold">
                                Router
                            </p>
                            <Badge
                                variant="outline"
                                class="border-gold/40 font-mono text-[10px] tracking-wider text-gold uppercase"
                            >
                                Thin gateway
                            </Badge>
                        </div>
                        <p
                            class="font-mono text-[10px] tracking-[0.18em] text-ivory/45 uppercase"
                        >
                            No domain logic
                        </p>
                    </div>

                    <div
                        class="grid gap-0 sm:grid-cols-3 sm:divide-x sm:divide-ivory/10"
                    >
                        <div
                            v-for="(job, index) in routerJobs"
                            :key="job.id"
                            class="flex items-start gap-3 px-5 py-4"
                        >
                            <span
                                class="mt-0.5 flex size-6 shrink-0 items-center justify-center rounded-full bg-ivory/10 font-mono text-[10px] text-ivory/70"
                            >
                                {{ index + 1 }}
                            </span>
                            <div>
                                <p class="font-display text-sm font-semibold">
                                    {{ job.label }}
                                </p>
                                <p class="mt-1 text-xs leading-relaxed text-ivory/60">
                                    {{ job.detail }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="flex justify-center text-gold"
                    aria-hidden="true"
                >
                    <ArrowDown class="size-4" />
                </div>

                <p
                    class="text-center font-mono text-[10px] tracking-[0.2em] text-forest-500 uppercase"
                >
                    Specialized workers
                </p>

                <!-- Fat specialists grid -->
                <ul
                    class="grid gap-4 sm:grid-cols-2"
                    aria-label="Specialist agents"
                >
                    <li
                        v-for="(agent, index) in specialists"
                        :key="agent.id"
                        :class="[
                            'landing-agents__tile group flex flex-col rounded-xl border border-border bg-white p-5 transition-colors hover:border-forest/25',
                            `landing-agents__tile--${index}`,
                        ]"
                    >
                        <div class="flex flex-wrap items-start justify-between gap-2">
                            <div>
                                <p
                                    class="font-mono text-[10px] tracking-[0.18em] text-muted-foreground uppercase"
                                >
                                    {{ agent.path }} · {{ agent.verb }}
                                </p>
                                <h3
                                    class="mt-1 font-display text-xl font-semibold tracking-tight"
                                >
                                    {{ agent.name }}
                                </h3>
                            </div>
                            <Badge
                                variant="outline"
                                class="border-gold/35 font-mono text-[10px] text-gold-700 uppercase"
                            >
                                Specialist
                            </Badge>
                        </div>

                        <p
                            class="mt-3 grow text-sm leading-relaxed text-muted-foreground"
                        >
                            {{ agent.summary }}
                        </p>

                        <div
                            class="mt-5 flex flex-wrap items-center gap-2 font-mono text-[11px]"
                        >
                            <span
                                class="rounded-md border border-border bg-ivory/70 px-2.5 py-1 text-stone"
                            >
                                {{ agent.accepts }}
                            </span>
                            <ArrowRight
                                class="size-3.5 shrink-0 text-muted-foreground"
                                aria-hidden="true"
                            />
                            <span
                                class="rounded-md border border-gold/30 bg-gold-50/60 px-2.5 py-1 text-stone"
                            >
                                {{ agent.emits }}
                            </span>
                        </div>

                        <Separator class="my-4" />

                        <p
                            class="font-mono text-[11px] leading-relaxed text-forest-600"
                        >
                            {{ agent.rule }}
                        </p>
                    </li>
                </ul>

                <div
                    class="rounded-xl border border-forest/15 bg-forest px-5 py-5 text-center text-ivory sm:px-8"
                >
                    <p
                        class="font-mono text-[10px] tracking-[0.2em] text-gold uppercase"
                    >
                        Architecture rule
                    </p>
                    <p
                        class="mt-2 font-display text-lg font-semibold sm:text-xl"
                    >
                        Thin router. Fat specialists. Draft PRs only.
                    </p>
                    <p
                        class="mx-auto mt-2 max-w-2xl text-sm leading-relaxed text-ivory/70"
                    >
                        Business logic never piles into the gateway. Each worker
                        owns a clear contract. Humans keep the merge.
                    </p>
                </div>
            </div>
        </LandingSection>

        <div class="bg-ivory px-4 sm:px-6 lg:px-8">
            <Separator class="mx-auto max-w-7xl" />
        </div>
    </div>
</template>
