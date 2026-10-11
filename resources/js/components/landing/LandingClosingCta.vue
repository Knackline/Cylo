<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import LandingSection from '@/components/landing/LandingSection.vue';
import { Button } from '@/components/ui/button';
import { dashboard, login, register } from '@/routes';

const page = usePage();
const user = computed(() => page.props.auth.user);
</script>

<template>
    <LandingSection
        tone="forest"
        :bordered="false"
        labelled-by="closing-heading"
        class="border-b-0"
    >
        <div
            class="flex flex-col items-start gap-8 lg:flex-row lg:items-center lg:justify-between"
        >
            <div class="max-w-xl">
                <p
                    class="font-mono text-xs tracking-[0.22em] text-gold-400 uppercase"
                >
                    Keep control
                </p>
                <h2
                    id="closing-heading"
                    class="mt-3 font-display text-3xl font-semibold tracking-tight text-ivory sm:text-4xl"
                >
                    You keep the merge.
                </h2>
                <p
                    class="mt-4 text-base leading-relaxed text-ivory/72 sm:text-lg"
                >
                    Triage, Fix, and Feature open draft PRs. You review and
                    merge. Nothing ships without a human. You keep the merge.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
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
        </div>
    </LandingSection>
</template>
