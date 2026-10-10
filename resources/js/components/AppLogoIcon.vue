<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

type Props = {
    className?: HTMLAttributes['class'];
};

defineProps<Props>();
</script>

<template>
    <svg
        viewBox="0 0 48 48"
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        aria-hidden="true"
        :class="className"
        v-bind="$attrs"
    >
        <!-- Construction ring + open arc share one radius (MARK-01 vernacular) -->
        <circle
            cx="24"
            cy="24"
            r="18"
            stroke="currentColor"
            stroke-width="1.25"
            opacity="0.35"
        />
        <path
            d="M24 6a18 18 0 1 1-12.73 5.27"
            stroke="currentColor"
            stroke-width="3"
            stroke-linecap="round"
        />
        <circle cx="24" cy="6" r="2.75" fill="#C19A3A" />
        <path
            d="M17.7 29.7c1.8 2.7 4.2 3.9 6.3 3.9s4.5-1.2 6.3-3.9"
            stroke="currentColor"
            stroke-width="2.25"
            stroke-linecap="round"
        />
    </svg>
</template>
