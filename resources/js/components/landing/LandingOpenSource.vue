<script setup lang="ts">
import { FolderGit2 } from '@lucide/vue';
import LandingSection from '@/components/landing/LandingSection.vue';
import LandingSectionHeader from '@/components/landing/LandingSectionHeader.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';

const githubUrl =
    (import.meta.env.VITE_GITHUB_URL as string | undefined) || null;
</script>

<template>
    <LandingSection
        id="open-source"
        tone="ivory"
        labelled-by="open-source-heading"
    >
        <LandingSectionHeader
            eyebrow="Open source"
            title="Inspect the agents. Host it yourself."
            title-id="open-source-heading"
            description="Cylo is not a black box. Inspect the router, triage, fix, and feature agents. Host it yourself. Licensed under MIT."
        />

        <div class="mt-10 flex flex-wrap items-center gap-4">
            <Button
                v-if="githubUrl"
                as-child
                class="bg-forest text-ivory hover:bg-forest-800"
            >
                <a
                    :href="githubUrl"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    <FolderGit2 class="size-4" />
                    View on GitHub
                </a>
            </Button>
            <Badge
                variant="outline"
                class="rounded-md px-3 py-1.5 font-mono text-xs"
            >
                MIT License · Agent Development Life Cycle
            </Badge>
        </div>
    </LandingSection>
</template>
