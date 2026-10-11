<script setup lang="ts">
import { computed, ref } from 'vue';
import LandingSection from '@/components/landing/LandingSection.vue';
import LandingSectionHeader from '@/components/landing/LandingSectionHeader.vue';
import { Badge } from '@/components/ui/badge';
import {
    Item,
    ItemContent,
    ItemDescription,
    ItemGroup,
    ItemTitle,
} from '@/components/ui/item';
import { Separator } from '@/components/ui/separator';

type PathId = 'bug' | 'feature';

type Stage = {
    id: string;
    title: string;
    owner: 'agent' | 'human';
    detail: string;
};

const paths: Record<
    PathId,
    {
        label: string;
        signal: string;
        summary: string;
        stages: Stage[];
    }
> = {
    bug: {
        label: 'Bug path',
        signal: 'Error · Slack · Ticket',
        summary:
            'Diagnosis first. Fix implements against evidence. Draft PR waits for a human.',
        stages: [
            {
                id: 'bug-triage',
                title: 'Triage',
                owner: 'agent',
                detail: 'Structured diagnosis with evidence and confidence. Low confidence holds for human review.',
            },
            {
                id: 'bug-fix',
                title: 'Fix',
                owner: 'agent',
                detail: 'TDD implement against the diagnosis. Opens a draft PR labeled built. Never merges.',
            },
            {
                id: 'bug-pr',
                title: 'Draft PR',
                owner: 'agent',
                detail: 'Evidence attached. Status stays draft. Agents propose — they do not promote.',
            },
            {
                id: 'bug-merge',
                title: 'Human merge',
                owner: 'human',
                detail: 'A person reviews, approves, and merges. This is the only path to production.',
            },
        ],
    },
    feature: {
        label: 'Feature path',
        signal: 'Labeled story · Request',
        summary:
            'Plan before code. Implement only when approved. Same draft-PR terminus as bugs.',
        stages: [
            {
                id: 'feat-plan',
                title: 'Plan',
                owner: 'agent',
                detail: 'Writes a plan with blocking questions. Waits for revise or implement-approved.',
            },
            {
                id: 'feat-implement',
                title: 'Implement',
                owner: 'agent',
                detail: 'Builds against the approved plan. Opens a draft PR with the change set.',
            },
            {
                id: 'feat-pr',
                title: 'Draft PR',
                owner: 'agent',
                detail: 'Same rule as bugs: draft only, labeled built, ready for human review.',
            },
            {
                id: 'feat-merge',
                title: 'Human merge',
                owner: 'human',
                detail: 'A person reviews, approves, and merges. Learn records the outcome.',
            },
        ],
    },
};

const pathIds: PathId[] = ['bug', 'feature'];

const activePath = ref<PathId>('bug');
const activeStageId = ref(paths.bug.stages[0].id);

const currentPath = computed(() => paths[activePath.value]);

const currentStage = computed(
    () =>
        currentPath.value.stages.find(
            (stage) => stage.id === activeStageId.value,
        ) ?? currentPath.value.stages[0],
);

function selectPath(path: PathId): void {
    activePath.value = path;
    activeStageId.value = paths[path].stages[0].id;
}

function selectStage(stageId: string): void {
    activeStageId.value = stageId;
}
</script>

<template>
    <LandingSection id="cycle" tone="forest" labelled-by="cycle-heading">
        <LandingSectionHeader
            tone="forest"
            eyebrow="Pipelines · Control plane"
            title="Two runs. One merge rule."
            title-id="cycle-heading"
            description="Bugs and features take different paths. Both stop at a draft PR. Agents propose. Only a human merges."
        />

        <div
            class="landing-pipelines mt-12 overflow-hidden rounded-xl border border-ivory/15 bg-forest-950/50"
        >
            <div
                class="flex flex-wrap items-center justify-between gap-3 border-b border-ivory/10 px-4 py-3 sm:px-5"
            >
                <div class="flex flex-wrap items-center gap-2">
                    <button
                        v-for="id in pathIds"
                        :key="id"
                        type="button"
                        class="rounded-md px-3 py-1.5 font-mono text-xs tracking-wide uppercase transition-colors focus-visible:ring-2 focus-visible:ring-gold focus-visible:outline-none"
                        :class="
                            activePath === id
                                ? 'bg-gold-400 text-forest'
                                : 'bg-ivory/5 text-ivory/65 hover:bg-ivory/10 hover:text-ivory'
                        "
                        :aria-pressed="activePath === id"
                        @click="selectPath(id)"
                    >
                        {{ paths[id].label }}
                    </button>
                </div>
                <Badge
                    variant="outline"
                    class="border-gold/40 font-mono text-[10px] tracking-wider text-gold uppercase"
                >
                    Terminus · Human
                </Badge>
            </div>

            <div class="grid lg:grid-cols-[1fr_1.1fr]">
                <div class="border-b border-ivory/10 lg:border-r lg:border-b-0">
                    <div class="border-b border-ivory/10 px-5 py-4">
                        <p
                            class="font-mono text-[10px] tracking-[0.18em] text-ivory/40 uppercase"
                        >
                            Signal · {{ currentPath.signal }}
                        </p>
                        <p class="mt-2 text-sm leading-relaxed text-ivory/70">
                            {{ currentPath.summary }}
                        </p>
                    </div>

                    <ol class="divide-y divide-ivory/10">
                        <li
                            v-for="(stage, index) in currentPath.stages"
                            :key="stage.id"
                        >
                            <button
                                type="button"
                                class="landing-pipelines__stage flex w-full items-start gap-3 px-5 py-4 text-left transition-colors focus-visible:bg-ivory/5 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-gold"
                                :class="
                                    activeStageId === stage.id
                                        ? 'bg-ivory/8'
                                        : 'hover:bg-ivory/4'
                                "
                                :aria-current="
                                    activeStageId === stage.id
                                        ? 'step'
                                        : undefined
                                "
                                @click="selectStage(stage.id)"
                            >
                                <span
                                    :class="[
                                        'mt-0.5 flex size-7 shrink-0 items-center justify-center rounded-full font-mono text-[11px]',
                                        stage.owner === 'human'
                                            ? 'bg-gold-400 text-forest'
                                            : activeStageId === stage.id
                                              ? 'bg-ivory/20 text-ivory'
                                              : 'bg-ivory/10 text-ivory/55',
                                    ]"
                                >
                                    {{ index + 1 }}
                                </span>
                                <span class="min-w-0 flex-1">
                                    <span
                                        class="flex flex-wrap items-center gap-2"
                                    >
                                        <span
                                            class="font-display text-sm font-semibold text-ivory"
                                        >
                                            {{ stage.title }}
                                        </span>
                                        <Badge
                                            variant="outline"
                                            :class="
                                                stage.owner === 'human'
                                                    ? 'border-gold/40 font-mono text-[9px] text-gold uppercase'
                                                    : 'border-ivory/20 font-mono text-[9px] text-ivory/50 uppercase'
                                            "
                                        >
                                            {{ stage.owner }}
                                        </Badge>
                                    </span>
                                    <span
                                        class="mt-1 line-clamp-1 block text-xs text-ivory/45"
                                    >
                                        {{ stage.detail }}
                                    </span>
                                </span>
                            </button>
                        </li>
                    </ol>
                </div>

                <div
                    :key="currentStage.id"
                    class="landing-pipelines__detail flex min-h-[22rem] flex-col"
                >
                    <div class="grow px-5 py-6 sm:px-6">
                        <p
                            class="font-mono text-[10px] tracking-[0.2em] text-ivory/40 uppercase"
                        >
                            Stage detail · {{ currentPath.label }}
                        </p>
                        <h3
                            class="mt-2 font-display text-2xl font-semibold tracking-tight text-ivory"
                        >
                            {{ currentStage.title }}
                        </h3>
                        <p
                            class="mt-3 max-w-xl text-sm leading-relaxed text-ivory/70 sm:text-base"
                        >
                            {{ currentStage.detail }}
                        </p>

                        <ItemGroup class="mt-8 gap-2">
                            <Item
                                v-for="(stage, index) in currentPath.stages"
                                :key="`rail-${stage.id}`"
                                variant="outline"
                                size="sm"
                                class="border-ivory/10 bg-forest/40"
                            >
                                <ItemContent class="flex-row items-center gap-3">
                                    <span
                                        :class="[
                                            'flex size-6 items-center justify-center rounded-full font-mono text-[10px]',
                                            stage.id === currentStage.id
                                                ? stage.owner === 'human'
                                                    ? 'bg-gold-400 text-forest'
                                                    : 'bg-ivory text-forest'
                                                : 'bg-ivory/10 text-ivory/45',
                                        ]"
                                    >
                                        {{ index + 1 }}
                                    </span>
                                    <div>
                                        <ItemTitle
                                            :class="
                                                stage.id === currentStage.id
                                                    ? 'text-ivory'
                                                    : 'text-ivory/55'
                                            "
                                        >
                                            {{ stage.title }}
                                        </ItemTitle>
                                        <ItemDescription
                                            class="text-ivory/40"
                                        >
                                            {{
                                                stage.owner === 'human'
                                                    ? 'Gate · person required'
                                                    : 'Agent executes'
                                            }}
                                        </ItemDescription>
                                    </div>
                                </ItemContent>
                            </Item>
                        </ItemGroup>
                    </div>

                    <div
                        class="flex flex-wrap items-center justify-between gap-3 border-t border-gold/30 bg-gold/10 px-5 py-4 sm:px-6"
                    >
                        <div>
                            <p
                                class="font-mono text-[10px] tracking-[0.18em] text-gold uppercase"
                            >
                                Shared terminus
                            </p>
                            <p class="mt-1 font-display text-lg text-ivory">
                                Human merge
                            </p>
                        </div>
                        <p
                            class="max-w-xs text-right font-mono text-[11px] leading-relaxed text-ivory/55"
                        >
                            Both paths end here. All PRs stay draft until a
                            person merges.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 grid gap-4 sm:grid-cols-2">
            <div
                class="rounded-xl border border-ivory/10 bg-forest-950/40 px-5 py-5 sm:px-6"
            >
                <p
                    class="font-mono text-[10px] tracking-[0.18em] text-ivory/40 uppercase"
                >
                    Agent proposes
                </p>
                <p class="mt-3 text-sm leading-relaxed text-ivory/75 sm:text-base">
                    Signals route into Bug or Feature. Agents triage, plan,
                    implement, and open a draft PR with evidence attached. You
                    get a run you can inspect, not a chat transcript.
                </p>
            </div>
            <div
                class="rounded-xl border border-gold/30 bg-gold/10 px-5 py-5 sm:px-6"
            >
                <p
                    class="font-mono text-[10px] tracking-[0.18em] text-gold uppercase"
                >
                    Human merges
                </p>
                <p class="mt-3 text-sm leading-relaxed text-ivory/85 sm:text-base">
                    Every PR stays draft until a person reviews, approves, and
                    merges. Learn feeds outcomes back into the next cycle.
                </p>
            </div>
        </div>

        <Separator class="my-10 bg-white/10" />

        <p
            class="text-center font-display text-lg text-ivory/85 sm:text-xl"
        >
            Nothing merges without a human.
            <span class="text-gold">All PRs are drafts.</span>
        </p>
    </LandingSection>
</template>
