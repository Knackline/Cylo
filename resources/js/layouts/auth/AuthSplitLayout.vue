<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { home } from '@/routes';

const page = usePage();
const name = page.props.name;

defineProps<{
    title?: string;
    description?: string;
}>();

const stages: {
    label: string;
    detail: string;
    active?: boolean;
}[] = [
    { label: 'Signal', detail: 'Slack · ticket · error' },
    { label: 'Route', detail: 'Classified · bug' },
    { label: 'Triage', detail: 'Diagnosis 0.91', active: true },
    { label: 'Fix', detail: 'Awaiting gate' },
    { label: 'Draft PR', detail: 'Patch ready' },
    { label: 'Human merge', detail: 'You own the gate' },
];
</script>

<template>
    <div
        class="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0"
    >
        <div
            class="relative hidden h-full flex-col overflow-hidden bg-forest p-10 text-ivory lg:flex dark:border-r"
        >
            <div
                class="pointer-events-none absolute inset-0 opacity-[0.08]"
                aria-hidden="true"
                style="
                    background-image:
                        linear-gradient(
                            rgba(247, 245, 240, 0.45) 1px,
                            transparent 1px
                        ),
                        linear-gradient(
                            90deg,
                            rgba(247, 245, 240, 0.45) 1px,
                            transparent 1px
                        );
                    background-size: 48px 48px;
                "
            />
            <div
                class="pointer-events-none absolute -top-28 right-0 h-[28rem] w-[28rem] rounded-full bg-gold/14 blur-3xl"
                aria-hidden="true"
            />
            <div
                class="pointer-events-none absolute bottom-0 left-1/4 h-64 w-64 rounded-full bg-forest-300/15 blur-3xl"
                aria-hidden="true"
            />

            <div class="relative z-20 flex min-h-0 flex-1 flex-col">
                <Link
                    :href="home()"
                    class="auth-panel__in flex items-center gap-3 text-ivory transition-colors hover:text-gold-400"
                >
                    <AppLogoIcon class="size-10 text-ivory" />
                    <span
                        class="font-display text-3xl font-semibold tracking-tight"
                    >
                        {{ name }}
                    </span>
                </Link>

                <p
                    class="auth-panel__in auth-panel__in--1 mt-8 font-mono text-[10px] tracking-[0.2em] text-gold uppercase"
                >
                    Open source · ADLC
                </p>

                <p
                    class="auth-panel__in auth-panel__in--2 mt-3 max-w-md font-display text-2xl font-semibold tracking-tight text-ivory xl:text-3xl"
                >
                    The engineering control plane for AI-assisted development
                </p>

                <ol
                    class="auth-panel__in auth-panel__in--3 mt-auto flex list-none flex-col gap-0 border-t border-ivory/10 pt-2"
                    aria-label="Control plane cycle"
                >
                    <li
                        v-for="(stage, index) in stages"
                        :key="stage.label"
                        :class="[
                            'auth-panel__stage grid grid-cols-[7rem_1fr] items-baseline gap-4 border-b border-ivory/10 py-3.5',
                            `auth-panel__stage--${index}`,
                        ]"
                    >
                        <span
                            :class="[
                                'font-mono text-xs tracking-[0.14em] uppercase',
                                stage.active ? 'text-gold' : 'text-ivory/45',
                            ]"
                        >
                            <span
                                v-if="stage.active"
                                class="auth-panel__live mr-2 inline-block size-1.5 rounded-full bg-gold"
                                aria-hidden="true"
                            />
                            {{ stage.label }}
                        </span>
                        <span
                            :class="[
                                'font-mono text-xs',
                                stage.active ? 'text-ivory/80' : 'text-ivory/40',
                            ]"
                        >
                            {{ stage.detail }}
                        </span>
                    </li>
                </ol>
            </div>
        </div>
        <div class="lg:p-8">
            <div
                class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px]"
            >
                <div class="flex flex-col space-y-2 text-center">
                    <h1
                        class="font-display text-2xl font-semibold tracking-tight text-foreground"
                        v-if="title"
                    >
                        {{ title }}
                    </h1>
                    <p
                        class="text-sm leading-relaxed text-muted-foreground"
                        v-if="description"
                    >
                        {{ description }}
                    </p>
                </div>
                <slot />
            </div>
        </div>
    </div>
</template>

<style scoped>
.auth-panel__in {
    animation: auth-panel-in 0.65s ease-out both;
}

.auth-panel__in--1 {
    animation-delay: 0.08s;
}

.auth-panel__in--2 {
    animation-delay: 0.16s;
}

.auth-panel__in--3 {
    animation-delay: 0.24s;
}

.auth-panel__stage {
    animation: auth-panel-in 0.5s ease-out both;
}

.auth-panel__stage--0 {
    animation-delay: 0.28s;
}

.auth-panel__stage--1 {
    animation-delay: 0.34s;
}

.auth-panel__stage--2 {
    animation-delay: 0.4s;
}

.auth-panel__stage--3 {
    animation-delay: 0.46s;
}

.auth-panel__stage--4 {
    animation-delay: 0.52s;
}

.auth-panel__stage--5 {
    animation-delay: 0.58s;
}

.auth-panel__live {
    animation: auth-panel-live 2.2s ease-in-out infinite;
}

@keyframes auth-panel-in {
    from {
        opacity: 0;
        transform: translateY(0.4rem);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes auth-panel-live {
    0%,
    100% {
        opacity: 0.55;
    }
    50% {
        opacity: 1;
    }
}

@media (prefers-reduced-motion: reduce) {
    .auth-panel__in,
    .auth-panel__stage,
    .auth-panel__live {
        animation: none;
    }
}
</style>
