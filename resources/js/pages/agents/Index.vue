<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { SidebarTrigger } from '@/components/ui/sidebar';
import { edit as repositories } from '@/routes/repositories';

defineOptions({
    layout: {
        breadcrumbs: [],
    },
});

const model = ref('claude-sonnet-4-6');
const confidenceThreshold = ref(0.6);
const autoFixThreshold = ref(0.85);
const maxToolCalls = ref(60);

const tools = ref({
    zendesk: true,
    githubSearch: true,
    githubFile: true,
    jira: true,
    confluence: true,
    gcpLogs: true,
});

const intakes = ['Zendesk', 'Jira', 'Slack', 'Error Reporting'] as const;
</script>

<template>
    <Head title="Agents" />

    <div class="flex h-full flex-1 flex-col">
        <header
            class="flex flex-col gap-4 border-b border-border px-4 py-4 md:px-6"
        >
            <div class="flex min-w-0 items-start gap-2">
                <SidebarTrigger class="mt-0.5 -ml-1" />
                <div class="min-w-0">
                    <h1 class="text-lg font-semibold tracking-tight text-foreground">
                        Agents
                    </h1>
                    <p class="mt-0.5 text-sm text-muted-foreground">
                        Sample configuration for triage and bug fix. Nothing
                        here is saved.
                    </p>
                </div>
            </div>
        </header>

        <div class="flex flex-1 flex-col gap-6 px-4 py-5 md:px-6">
            <section
                class="overflow-hidden rounded-md border border-border"
                aria-labelledby="triage-heading"
            >
                <div class="border-b border-border px-4 py-3">
                    <p
                        class="font-mono text-[10px] tracking-wide text-muted-foreground uppercase"
                    >
                        bug-triage-agent
                    </p>
                    <h2
                        id="triage-heading"
                        class="mt-1 text-sm font-semibold text-foreground"
                    >
                        Bug triage
                    </h2>
                    <p class="mt-0.5 text-sm text-muted-foreground">
                        Investigates a Zendesk ticket, Jira issue, Slack report,
                        or error group and posts a diagnosis.
                    </p>
                </div>

                <div class="space-y-5 px-4 py-4 text-sm">
                    <label class="block space-y-1.5">
                        <span class="text-muted-foreground">Model</span>
                        <select
                            v-model="model"
                            class="flex h-9 w-full max-w-sm rounded-md border border-input bg-background px-3 text-sm"
                        >
                            <option value="claude-sonnet-4-6">
                                claude-sonnet-4-6
                            </option>
                            <option value="claude-haiku">claude-haiku</option>
                        </select>
                    </label>

                    <div class="grid gap-4 sm:grid-cols-3">
                        <label class="block space-y-1.5">
                            <span class="text-muted-foreground"
                                >Confidence threshold</span
                            >
                            <input
                                v-model.number="confidenceThreshold"
                                type="number"
                                step="0.01"
                                min="0"
                                max="1"
                                class="flex h-9 w-full rounded-md border border-input bg-background px-3 font-mono text-sm"
                            />
                        </label>
                        <label class="block space-y-1.5">
                            <span class="text-muted-foreground"
                                >Auto-fix threshold</span
                            >
                            <input
                                v-model.number="autoFixThreshold"
                                type="number"
                                step="0.01"
                                min="0"
                                max="1"
                                class="flex h-9 w-full rounded-md border border-input bg-background px-3 font-mono text-sm"
                            />
                        </label>
                        <label class="block space-y-1.5">
                            <span class="text-muted-foreground"
                                >Max tool calls</span
                            >
                            <input
                                v-model.number="maxToolCalls"
                                type="number"
                                min="1"
                                class="flex h-9 w-full rounded-md border border-input bg-background px-3 font-mono text-sm"
                            />
                        </label>
                    </div>

                    <p class="text-xs text-muted-foreground">
                        Below 0.60 the diagnosis is marked for a person. Below
                        0.85 Jira is still filed but without ai-ready, so the
                        fix agent does not start.
                    </p>

                    <fieldset class="space-y-2">
                        <legend class="text-muted-foreground">Tools</legend>
                        <label class="flex items-center gap-2">
                            <input v-model="tools.zendesk" type="checkbox" />
                            <span>Zendesk ticket</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input
                                v-model="tools.githubSearch"
                                type="checkbox"
                            />
                            <span>GitHub code search</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input v-model="tools.githubFile" type="checkbox" />
                            <span>GitHub file</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input v-model="tools.jira" type="checkbox" />
                            <span>Jira search</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input
                                v-model="tools.confluence"
                                type="checkbox"
                            />
                            <span>Confluence search</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input v-model="tools.gcpLogs" type="checkbox" />
                            <span>GCP logs</span>
                        </label>
                    </fieldset>

                    <div>
                        <p class="text-muted-foreground">Intake</p>
                        <ul
                            class="mt-2 flex flex-wrap gap-2 font-mono text-xs text-foreground"
                        >
                            <li
                                v-for="intake in intakes"
                                :key="intake"
                                class="rounded-md border border-border px-2 py-1"
                            >
                                {{ intake }}
                            </li>
                        </ul>
                    </div>
                </div>
            </section>

            <section
                class="overflow-hidden rounded-md border border-border"
                aria-labelledby="fix-heading"
            >
                <div class="border-b border-border px-4 py-3">
                    <p
                        class="font-mono text-[10px] tracking-wide text-muted-foreground uppercase"
                    >
                        bug-fix-agent
                    </p>
                    <h2
                        id="fix-heading"
                        class="mt-1 text-sm font-semibold text-foreground"
                    >
                        Bug fix
                    </h2>
                    <p class="mt-0.5 text-sm text-muted-foreground">
                        Implements a diagnosed bug as a draft pull request. It
                        does not push to the default branch.
                    </p>
                </div>

                <dl class="divide-y divide-border text-sm">
                    <div
                        class="flex flex-col gap-0.5 px-4 py-3 sm:flex-row sm:gap-6"
                    >
                        <dt class="w-28 shrink-0 text-muted-foreground">
                            Runtime
                        </dt>
                        <dd class="text-foreground">Claude Code CLI</dd>
                    </div>
                    <div
                        class="flex flex-col gap-0.5 px-4 py-3 sm:flex-row sm:gap-6"
                    >
                        <dt class="w-28 shrink-0 text-muted-foreground">
                            Starts when
                        </dt>
                        <dd class="text-foreground">
                            A Jira Bug has ai-ready
                        </dd>
                    </div>
                    <div
                        class="flex flex-col gap-0.5 px-4 py-3 sm:flex-row sm:gap-6"
                    >
                        <dt class="w-28 shrink-0 text-muted-foreground">
                            Jira path
                        </dt>
                        <dd class="text-foreground">
                            Developing, then Reviewing, and gains ai-built
                        </dd>
                    </div>
                    <div class="px-4 py-3">
                        <label class="flex items-center gap-2">
                            <input
                                type="checkbox"
                                checked
                                disabled
                            />
                            <span>Draft pull requests only</span>
                        </label>
                    </div>
                    <div class="px-4 py-3 text-muted-foreground">
                        The target repo comes from the connected
                        <Link
                            :href="repositories()"
                            class="text-foreground underline-offset-4 hover:underline"
                        >
                            GitHub repositories
                        </Link>
                        .
                    </div>
                </dl>
            </section>
        </div>
    </div>
</template>
