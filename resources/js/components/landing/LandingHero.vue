<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import LandingHeroVisual from '@/components/landing/LandingHeroVisual.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { dashboard, login, register } from '@/routes';

const page = usePage();
const user = computed(() => page.props.auth.user);
</script>

<template>
    <section
        class="relative overflow-hidden border-b border-white/10 bg-forest"
        aria-labelledby="hero-heading"
    >
        <div
            class="pointer-events-none absolute inset-0 opacity-[0.09]"
            aria-hidden="true"
            style="
                background-image:
                    linear-gradient(
                        rgba(247, 245, 240, 0.4) 1px,
                        transparent 1px
                    ),
                    linear-gradient(
                        90deg,
                        rgba(247, 245, 240, 0.4) 1px,
                        transparent 1px
                    );
                background-size: 48px 48px;
            "
        />
        <div
            class="pointer-events-none absolute -top-28 right-0 h-[32rem] w-[32rem] rounded-full bg-gold/15 blur-3xl"
            aria-hidden="true"
        />
        <div
            class="pointer-events-none absolute bottom-0 left-1/3 h-72 w-72 rounded-full bg-forest-300/15 blur-3xl"
            aria-hidden="true"
        />

        <div
            class="relative mx-auto grid max-w-7xl items-center gap-12 px-4 py-16 sm:px-6 lg:grid-cols-[minmax(0,1fr)_minmax(0,1.05fr)] lg:gap-14 lg:px-8 lg:py-24"
        >
            <div class="landing-fade-up max-w-xl">
                <div class="flex items-center gap-3">
                    <AppLogoIcon class="size-12 text-ivory sm:size-14" />
                    <span
                        class="font-display text-4xl font-semibold tracking-tight text-ivory sm:text-5xl"
                        >Cylo</span
                    >
                </div>

                <Badge
                    variant="outline"
                    class="mt-7 border-gold/40 font-mono text-[10px] tracking-[0.2em] text-gold uppercase"
                >
                    Open source · ADLC
                </Badge>

                <h1
                    id="hero-heading"
                    class="mt-4 font-display text-4xl font-semibold tracking-tight text-ivory sm:text-5xl lg:text-[3.35rem] lg:leading-[1.08]"
                >
                    The engineering control plane for AI-assisted development
                </h1>

                <p
                    class="landing-fade-up-delay mt-5 max-w-xl text-base leading-relaxed text-ivory/72 sm:text-lg"
                >
                    Specialized agents triage, plan, and implement from tickets,
                    Slack, and errors, not a blank chat. You own the gates. You
                    merge the draft PR.
                </p>

                <div
                    class="landing-fade-up-delay-2 mt-8 flex flex-wrap items-center gap-3"
                >
                    <template v-if="user">
                        <Button
                            as-child
                            size="lg"
                            class="bg-gold-400 text-forest hover:bg-gold-300"
                        >
                            <Link :href="dashboard()">Dashboard</Link>
                        </Button>
                    </template>
                    <template v-else>
                        <Button
                            as-child
                            size="lg"
                            class="bg-gold-400 text-forest hover:bg-gold-300"
                        >
                            <Link :href="register()">Start a cycle</Link>
                        </Button>
                        <Button
                            as-child
                            size="lg"
                            variant="outline"
                            class="border-ivory/25 bg-transparent text-ivory hover:border-ivory/50 hover:bg-white/5 hover:text-ivory"
                        >
                            <Link :href="login()">Sign in</Link>
                        </Button>
                    </template>
                </div>

                <p
                    class="landing-fade-up-delay-2 mt-10 font-mono text-[11px] leading-relaxed tracking-wide text-ivory/45 sm:text-xs"
                >
                    Signal → Route → Triage → Fix → Draft PR → Human merge
                </p>
            </div>

            <div class="landing-fade-up-delay w-full">
                <LandingHeroVisual />
            </div>
        </div>
    </section>
</template>
