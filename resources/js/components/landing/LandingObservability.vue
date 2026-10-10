<script setup lang="ts">
import { TrendingUp } from '@lucide/vue';
import LandingSection from '@/components/landing/LandingSection.vue';
import LandingSectionHeader from '@/components/landing/LandingSectionHeader.vue';
import { Badge } from '@/components/ui/badge';
import {
    Card,
    CardAction,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';

const metrics = [
    {
        label: 'Agent runs',
        value: '128',
        delta: '+12%',
        detail: 'Structured events this week',
    },
    {
        label: 'Outcomes',
        value: '94',
        delta: '+8%',
        detail: 'Merged draft PRs with learn tags',
    },
    {
        label: 'Diagnosis KB',
        value: '312',
        delta: '+21%',
        detail: 'Searchable cases for next triage',
    },
] as const;
</script>

<template>
    <LandingSection id="observe" tone="forest" labelled-by="observe-heading">
        <LandingSectionHeader
            tone="forest"
            eyebrow="Observability · Learn"
            title="Every run feeds the next triage"
            title-id="observe-heading"
            description="Agents emit structured run events. Enrichment turns those into outcomes and a diagnosis knowledge base that triage can search next time. The learn loop is the reliability record, not a wallpaper dashboard."
        />

        <div class="mt-12 grid gap-4 md:grid-cols-3">
            <Card
                v-for="metric in metrics"
                :key="metric.label"
                class="border-white/10 bg-forest-900/40 text-ivory shadow-none"
            >
                <CardHeader>
                    <CardDescription class="text-ivory/55">
                        {{ metric.label }}
                    </CardDescription>
                    <CardTitle
                        class="font-display text-2xl font-semibold tabular-nums text-ivory sm:text-3xl"
                    >
                        {{ metric.value }}
                    </CardTitle>
                    <CardAction>
                        <Badge
                            variant="outline"
                            class="border-gold/40 text-gold"
                        >
                            <TrendingUp class="size-3" />
                            {{ metric.delta }}
                        </Badge>
                    </CardAction>
                </CardHeader>
                <CardFooter
                    class="flex-col items-start gap-1.5 text-sm text-ivory/65"
                >
                    <div class="flex gap-2 font-medium text-ivory/80">
                        {{ metric.detail }}
                        <TrendingUp class="size-4 text-gold" />
                    </div>
                    <div class="font-mono text-xs text-ivory/45">
                        Learn loop · not a wallpaper
                    </div>
                </CardFooter>
            </Card>
        </div>

        <div
            class="mt-10 flex flex-wrap items-center gap-2 font-mono text-[10px] tracking-wider text-ivory/45 uppercase"
        >
            <span>Agent run</span>
            <span aria-hidden="true">→</span>
            <span>Metrics</span>
            <span aria-hidden="true">→</span>
            <span>Outcomes</span>
            <span aria-hidden="true">→</span>
            <span>Diagnosis KB</span>
            <span aria-hidden="true">→</span>
            <span class="text-gold">Learn</span>
        </div>
    </LandingSection>
</template>
