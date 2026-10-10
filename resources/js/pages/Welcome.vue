<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import LandingAgents from '@/components/landing/LandingAgents.vue';
import LandingClosingCta from '@/components/landing/LandingClosingCta.vue';
import LandingFooter from '@/components/landing/LandingFooter.vue';
import LandingHero from '@/components/landing/LandingHero.vue';
import LandingNav from '@/components/landing/LandingNav.vue';
import LandingObservability from '@/components/landing/LandingObservability.vue';
import LandingOpenSource from '@/components/landing/LandingOpenSource.vue';
import LandingOversight from '@/components/landing/LandingOversight.vue';
import LandingPipelines from '@/components/landing/LandingPipelines.vue';
import LandingSignals from '@/components/landing/LandingSignals.vue';

const description =
    'Specialized agents triage, plan, and implement from tickets, Slack, and errors, not a blank chat. You own the gates. You merge the draft PR.';
</script>

<template>
    <Head title="Engineering control plane for AI-assisted development">
        <meta
            head-key="description"
            name="description"
            :content="description"
        />
        <meta
            head-key="og:title"
            property="og:title"
            content="Cylo · Engineering control plane for AI-assisted development"
        />
        <meta
            head-key="og:description"
            property="og:description"
            :content="description"
        />
        <meta head-key="og:type" property="og:type" content="website" />
    </Head>

    <div
        id="top"
        class="min-h-screen scroll-smooth bg-forest text-ivory antialiased"
    >
        <a
            href="#main-content"
            class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-[60] focus:rounded-md focus:bg-gold-700 focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:text-ivory"
        >
            Skip to content
        </a>

        <LandingNav />

        <main id="main-content">
            <LandingHero />
            <LandingSignals />
            <LandingPipelines />
            <LandingAgents />
            <LandingOversight />
            <LandingObservability />
            <LandingOpenSource />
            <LandingClosingCta />
        </main>

        <LandingFooter />
    </div>
</template>
