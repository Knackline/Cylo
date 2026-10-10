<script setup lang="ts">
import type { HTMLAttributes } from 'vue';
import { cn } from '@/lib/utils';

const props = withDefaults(
    defineProps<{
        id?: string;
        tone?: 'forest' | 'ivory';
        bordered?: boolean;
        class?: HTMLAttributes['class'];
        labelledBy?: string;
        flush?: boolean;
    }>(),
    {
        tone: 'ivory',
        bordered: true,
        flush: false,
    },
);
</script>

<template>
    <section
        :id="id"
        :aria-labelledby="labelledBy"
        :class="
            cn(
                'scroll-mt-16',
                tone === 'forest'
                    ? 'bg-forest text-ivory'
                    : 'bg-ivory text-stone',
                bordered &&
                    (tone === 'forest'
                        ? 'border-b border-white/10'
                        : 'border-b border-border'),
                props.class,
            )
        "
    >
        <div
            :class="
                cn(
                    'mx-auto max-w-7xl',
                    flush
                        ? 'px-4 sm:px-6 lg:px-8'
                        : 'px-4 py-20 sm:px-6 lg:px-8 lg:py-28',
                )
            "
        >
            <slot />
        </div>
    </section>
</template>
