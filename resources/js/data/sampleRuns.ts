export type JobStatus =
    | 'Investigating'
    | 'Needs review'
    | 'Awaiting approval'
    | 'Developing'
    | 'Reviewing'
    | 'Merged';

export type AgentId = 'bug-triage' | 'bug-fix' | 'feature';

export type SampleRunLink = {
    label: string;
    href: string;
};

export type SampleRun = {
    key: string;
    title: string;
    intake: string;
    agent: AgentId;
    status: JobStatus;
    confidence: number | null;
    updated: string;
    trigger: {
        source: string;
        event: string;
        at: string;
    };
    context: {
        summary: string;
        mode: string;
    };
    links: SampleRunLink[];
};

export const sampleRuns: SampleRun[] = [
    {
        key: 'ZD-1842',
        title: 'Payment webhook null intent',
        intake: 'Zendesk',
        agent: 'bug-triage',
        status: 'Investigating',
        confidence: 0.91,
        updated: '2m ago',
        trigger: {
            source: 'Zendesk',
            event: 'Tag bug posted to webhook-service POST /webhook with ticket_id',
            at: '2m ago',
        },
        context: {
            summary:
                'Triage mode Zendesk. The tool loop is still collecting ticket, code, and log context.',
            mode: 'Zendesk',
        },
        links: [
            {
                label: 'Zendesk ticket #1842',
                href: 'https://example.zendesk.com/tickets/1842',
            },
        ],
    },
    {
        key: 'SL-1831',
        title: 'Invoice PDF missing line items',
        intake: 'Slack',
        agent: 'bug-triage',
        status: 'Needs review',
        confidence: 0.54,
        updated: '18m ago',
        trigger: {
            source: 'Slack',
            event: 'Message in #technical-support. channel-router classified bug_report.',
            at: '18m ago',
        },
        context: {
            summary:
                'Confidence is below the 0.60 review line, so no Jira is filed and the fix agent does not start.',
            mode: 'Slack',
        },
        links: [
            {
                label: 'Slack thread',
                href: 'https://example.slack.com/archives/C000/p1831',
            },
        ],
    },
    {
        key: 'PROJ-441',
        title: 'Self-serve seat transfer',
        intake: 'Jira',
        agent: 'feature',
        status: 'Awaiting approval',
        confidence: null,
        updated: '41m ago',
        trigger: {
            source: 'Jira',
            event: 'Label ai-ready on a Story. webhook-service POST /jira-webhook with FEATURE_PHASE=plan.',
            at: '41m ago',
        },
        context: {
            summary:
                'Plan posted with blocking questions. Waiting on ai-implement-approved.',
            mode: 'Feature plan',
        },
        links: [
            {
                label: 'Jira PROJ-441',
                href: 'https://example.atlassian.net/browse/PROJ-441',
            },
        ],
    },
    {
        key: 'ZD-1794',
        title: 'Retry storm on checkout webhook',
        intake: 'Zendesk',
        agent: 'bug-fix',
        status: 'Reviewing',
        confidence: 0.88,
        updated: '2h ago',
        trigger: {
            source: 'Jira',
            event: 'Triage confidence cleared 0.85. Jira Bug got ai-ready and the fix job started.',
            at: '2h ago',
        },
        context: {
            summary:
                'Claude Code opened a draft PR. Jira moved to Reviewing and gained ai-built.',
            mode: 'Bug fix',
        },
        links: [
            {
                label: 'Zendesk ticket #1794',
                href: 'https://example.zendesk.com/tickets/1794',
            },
            {
                label: 'Jira PROJ-1794',
                href: 'https://example.atlassian.net/browse/PROJ-1794',
            },
            {
                label: 'GitHub draft PR',
                href: 'https://example.github.com/Unimarket/unimarket/pull/1794',
            },
        ],
    },
    {
        key: 'PROJ-438',
        title: 'SSO role mapping for contractors',
        intake: 'Jira',
        agent: 'feature',
        status: 'Developing',
        confidence: null,
        updated: '3h ago',
        trigger: {
            source: 'Jira',
            event: 'Label ai-implement-approved. FEATURE_PHASE=implement.',
            at: '3h ago',
        },
        context: {
            summary:
                'Implementation in progress against Unimarket/unimarket.',
            mode: 'Feature implement',
        },
        links: [
            {
                label: 'Jira PROJ-438',
                href: 'https://example.atlassian.net/browse/PROJ-438',
            },
            {
                label: 'GitHub branch',
                href: 'https://example.github.com/Unimarket/unimarket/tree/ai/proj-438',
            },
        ],
    },
    {
        key: 'ERR-2201',
        title: 'Null payment_intent after retry',
        intake: 'Error Reporting',
        agent: 'bug-fix',
        status: 'Merged',
        confidence: 0.93,
        updated: 'Yesterday',
        trigger: {
            source: 'Error Reporting',
            event: 'Error-reporting scan fanned out one job for the group.',
            at: 'Yesterday',
        },
        context: {
            summary: 'Draft PR was merged by a person.',
            mode: 'Bug fix',
        },
        links: [
            {
                label: 'Jira PROJ-2201',
                href: 'https://example.atlassian.net/browse/PROJ-2201',
            },
            {
                label: 'GitHub PR',
                href: 'https://example.github.com/Unimarket/unimarket/pull/2201',
            },
        ],
    },
];

export function findRun(key: string): SampleRun | null {
    return sampleRuns.find((run) => run.key === key) ?? null;
}
