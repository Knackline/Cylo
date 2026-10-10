<script setup lang="ts">
import LandingSection from '@/components/landing/LandingSection.vue';
import LandingSectionHeader from '@/components/landing/LandingSectionHeader.vue';
import {
    Accordion,
    AccordionContent,
    AccordionItem,
    AccordionTrigger,
} from '@/components/ui/accordion';
import { Badge } from '@/components/ui/badge';

const gates = [
    {
        id: '01',
        label: 'Tag the signal',
        detail: 'Is this a bug, a question, or an incident? You (or clear rules) decide what enters the pipeline.',
    },
    {
        id: '02',
        label: 'Review low confidence',
        detail: 'Below 0.6, or needs_human_review. Diagnosis posts, but Fix does not start without you.',
    },
    {
        id: '03',
        label: 'Approve the plan',
        detail: 'Answer blocking questions. Revise the plan. Or mark implement-approved.',
    },
    {
        id: '04',
        label: 'Merge the draft PR',
        detail: 'All PRs are drafts labeled built. Only humans merge. Agents never promote.',
    },
] as const;

const labels = [
    'ready',
    'revise-plan',
    'implement-approved',
    'fix-in-progress',
    'built',
] as const;

const agentDuties = [
    'Triage bugs into structured diagnoses',
    'Plan and implement features when approved',
    'Open draft PRs labeled built',
    'Emit run metrics for the learn loop',
] as const;

const humanDuties = [
    'Classify what enters the pipeline',
    'Override or accept diagnoses',
    'Answer plan questions and approve implement',
    'Review draft PRs and merge',
] as const;
</script>

<template>
    <LandingSection id="oversight" tone="ivory" labelled-by="oversight-heading">
        <LandingSectionHeader
            eyebrow="Human-in-the-loop"
            title="Nothing merges without a human."
            title-id="oversight-heading"
            description="Agents propose diagnoses, plans, and draft PRs. You own the tags, the low-confidence calls, the plan approvals, and the merge. Accountability stays with the team. You stay in control."
        />

        <div class="mt-12 grid gap-8 lg:grid-cols-2">
            <div class="rounded-lg border border-border bg-white/70 px-5 py-6">
                <p
                    class="font-mono text-xs tracking-widest text-gold uppercase"
                >
                    Agents
                </p>
                <h3 class="mt-2 font-display text-xl font-semibold">
                    Propose and execute
                </h3>
                <ul
                    class="mt-4 space-y-2 text-sm leading-relaxed text-muted-foreground"
                >
                    <li v-for="duty in agentDuties" :key="duty">{{ duty }}</li>
                </ul>
            </div>

            <div class="rounded-lg border border-forest bg-forest px-5 py-6 text-ivory">
                <p
                    class="font-mono text-xs tracking-widest text-gold uppercase"
                >
                    Humans
                </p>
                <h3 class="mt-2 font-display text-xl font-semibold text-ivory">
                    Decide and merge
                </h3>
                <ul
                    class="mt-4 space-y-2 text-sm leading-relaxed text-ivory/75"
                >
                    <li v-for="duty in humanDuties" :key="duty">{{ duty }}</li>
                </ul>
            </div>
        </div>

        <div class="mt-12">
            <p
                class="font-mono text-xs tracking-[0.2em] text-forest-500 uppercase"
            >
                Hold points
            </p>
            <Accordion
                type="single"
                collapsible
                default-value="item-01"
                class="mt-4 rounded-xl border border-border bg-white/80 px-4"
            >
                <AccordionItem
                    v-for="gate in gates"
                    :key="gate.id"
                    :value="`item-${gate.id}`"
                >
                    <AccordionTrigger class="hover:no-underline">
                        <span class="flex items-center gap-3 text-left">
                            <Badge
                                variant="outline"
                                class="border-gold/40 font-mono text-[10px] text-gold"
                            >
                                HP-{{ gate.id }}
                            </Badge>
                            <span class="font-display font-semibold">{{
                                gate.label
                            }}</span>
                        </span>
                    </AccordionTrigger>
                    <AccordionContent>
                        <p class="text-sm leading-relaxed text-muted-foreground">
                            {{ gate.detail }}
                        </p>
                    </AccordionContent>
                </AccordionItem>
            </Accordion>
        </div>

        <div class="mt-10">
            <p
                class="font-mono text-xs tracking-[0.2em] text-forest-500 uppercase"
            >
                Control labels
            </p>
            <ul class="mt-4 flex flex-wrap gap-2">
                <li v-for="label in labels" :key="label">
                    <Badge
                        variant="outline"
                        class="rounded-md px-3 py-1.5 font-mono text-xs"
                    >
                        {{ label }}
                    </Badge>
                </li>
            </ul>
            <p class="mt-3 text-sm text-muted-foreground">
                Trigger labels are claimed on start so duplicate webhooks do not
                re-run the same work.
            </p>
        </div>
    </LandingSection>
</template>
