<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';

const page = usePage();
const name = page.props.name;
const organization = page.props.organization;
</script>

<template>
    <div
        class="flex aspect-square size-8 items-center justify-center rounded-md bg-primary text-primary-foreground ring-1 ring-gold/35"
    >
        <AppLogoIcon class="size-5" />
    </div>
    <div class="ml-1 grid min-w-0 flex-1 text-left text-sm leading-tight">
        <span class="truncate font-semibold text-foreground">{{
            organization
        }}</span>
        <span class="truncate text-xs text-muted-foreground">{{ name }}</span>
    </div>
</template>
