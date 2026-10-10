<script setup lang="ts">
import { ArrowRight } from '@lucide/vue';
import LandingSection from '@/components/landing/LandingSection.vue';
import LandingSectionHeader from '@/components/landing/LandingSectionHeader.vue';
import { Badge } from '@/components/ui/badge';
import {
    Item,
    ItemContent,
    ItemGroup,
    ItemTitle,
} from '@/components/ui/item';
import { Separator } from '@/components/ui/separator';

const inlets = [
    { id: 'tickets', label: 'Tickets' },
    { id: 'slack', label: 'Slack' },
    { id: 'issues', label: 'Issues' },
    { id: 'errors', label: 'Error groups' },
] as const;

const outlets = [
    { id: 'triage', label: 'Triage' },
    { id: 'fix', label: 'Fix' },
    { id: 'feature', label: 'Feature' },
    { id: 'metrics', label: 'Metrics' },
] as const;
</script>

<template>
    <LandingSection id="signals" tone="ivory" labelled-by="signals-heading">
        <LandingSectionHeader
            eyebrow="Intake · Router"
            title="Signals in. Jobs out."
            title-id="signals-heading"
            description="Work starts as a tagged ticket, a Slack report, a labeled story, or a production error group, not a blank chat prompt. The router authenticates, classifies, and dispatches jobs. Specialized agents hold the business logic."
        />

        <div
            class="mt-14 grid items-stretch gap-6 lg:grid-cols-[1fr_auto_minmax(12rem,16rem)_auto_1fr] lg:gap-4"
        >
            <ItemGroup class="gap-2">
                <Item
                    v-for="inlet in inlets"
                    :key="inlet.id"
                    variant="outline"
                    size="sm"
                    class="bg-white/60"
                >
                    <ItemContent>
                        <ItemTitle class="font-mono text-sm">{{
                            inlet.label
                        }}</ItemTitle>
                    </ItemContent>
                </Item>
            </ItemGroup>

            <div
                class="hidden items-center justify-center lg:flex"
                aria-hidden="true"
            >
                <ArrowRight class="size-5 text-muted-foreground" />
            </div>

            <div
                class="flex flex-col justify-center gap-3 rounded-lg border border-forest bg-forest px-5 py-6 text-ivory"
            >
                <Badge
                    variant="outline"
                    class="w-fit border-gold/40 font-mono text-[10px] tracking-wider text-gold uppercase"
                >
                    Gateway
                </Badge>
                <p class="font-display text-2xl font-semibold">Router</p>
                <p class="text-sm text-ivory/60">Auth, classify, dispatch</p>
            </div>

            <div
                class="hidden items-center justify-center lg:flex"
                aria-hidden="true"
            >
                <ArrowRight class="size-5 text-gold" />
            </div>

            <ItemGroup class="gap-2">
                <Item
                    v-for="outlet in outlets"
                    :key="outlet.id"
                    variant="outline"
                    size="sm"
                    class="border-gold/30 bg-white/60"
                >
                    <ItemContent>
                        <ItemTitle class="font-mono text-sm">{{
                            outlet.label
                        }}</ItemTitle>
                    </ItemContent>
                </Item>
            </ItemGroup>
        </div>

        <Separator class="my-10" />
        <p class="font-mono text-xs tracking-wide text-muted-foreground">
            CYLO / SYS-01 · REV A
        </p>
    </LandingSection>
</template>
