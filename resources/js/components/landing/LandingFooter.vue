<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { landingNavLinks } from '@/components/landing/landingNavLinks';
import { Separator } from '@/components/ui/separator';
import { login } from '@/routes';

const page = usePage();
const user = computed(() => page.props.auth.user);

const githubUrl =
    (import.meta.env.VITE_GITHUB_URL as string | undefined) || null;
</script>

<template>
    <footer class="border-t border-white/10 bg-forest text-ivory/70">
        <div
            class="mx-auto flex max-w-7xl flex-col gap-10 px-4 py-12 sm:px-6 lg:flex-row lg:justify-between lg:px-8"
        >
            <div class="max-w-sm">
                <a
                    href="#top"
                    class="inline-flex items-center gap-2.5 text-ivory transition-colors hover:text-gold"
                >
                    <AppLogoIcon class="h-7 w-7 text-ivory" />
                    <span class="font-display text-base font-semibold"
                        >Cylo</span
                    >
                </a>
                <p class="mt-3 text-sm leading-relaxed">
                    Open-source engineering control plane for AI-assisted
                    development. Specialized agents propose. You merge.
                </p>
            </div>

            <div class="flex flex-wrap gap-x-12 gap-y-8">
                <div>
                    <p
                        class="font-mono text-xs tracking-widest text-ivory/40 uppercase"
                    >
                        Sections
                    </p>
                    <ul class="mt-3 space-y-2">
                        <li v-for="link in landingNavLinks" :key="link.id">
                            <a
                                :href="`#${link.id}`"
                                class="text-sm text-ivory/70 transition-colors hover:text-gold"
                            >
                                {{ link.label }}
                            </a>
                        </li>
                    </ul>
                </div>

                <div>
                    <p
                        class="font-mono text-xs tracking-widest text-ivory/40 uppercase"
                    >
                        Account
                    </p>
                    <ul class="mt-3 space-y-2">
                        <li v-if="!user">
                            <Link
                                :href="login()"
                                class="text-sm text-ivory/70 transition-colors hover:text-gold"
                            >
                                Sign in
                            </Link>
                        </li>
                        <li v-if="githubUrl">
                            <a
                                :href="githubUrl"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="text-sm text-ivory/70 transition-colors hover:text-gold"
                            >
                                GitHub
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <Separator class="bg-white/10" />
        <div
            class="px-4 py-4 text-center font-mono text-xs text-ivory/40 sm:px-6 lg:px-8"
        >
            MIT License
        </div>
    </footer>
</template>
