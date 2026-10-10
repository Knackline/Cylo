<script setup lang="ts">
withDefaults(
    defineProps<{
        eyebrow: string;
        title: string;
        titleId: string;
        description?: string;
        tone?: 'forest' | 'ivory';
    }>(),
    {
        tone: 'ivory',
    },
);
</script>

<template>
    <div class="max-w-2xl">
        <p
            :class="[
                'font-mono text-xs tracking-[0.2em] uppercase',
                tone === 'forest' ? 'text-gold' : 'text-forest-500',
            ]"
        >
            {{ eyebrow }}
        </p>
        <h2
            :id="titleId"
            class="mt-3 font-display text-3xl font-semibold tracking-tight sm:text-4xl"
        >
            {{ title }}
        </h2>
        <p
            v-if="description"
            :class="[
                'mt-4 text-base leading-relaxed sm:text-lg',
                tone === 'forest' ? 'text-ivory/70' : 'text-muted-foreground',
            ]"
        >
            {{ description }}
        </p>
    </div>
</template>
