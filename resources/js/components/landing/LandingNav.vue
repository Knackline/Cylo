<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { Menu } from '@lucide/vue';
import { computed, onMounted, onUnmounted, ref } from 'vue';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { landingNavLinks } from '@/components/landing/landingNavLinks';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    NavigationMenu,
    NavigationMenuItem,
    NavigationMenuList,
} from '@/components/ui/navigation-menu';
import { Separator } from '@/components/ui/separator';
import {
    Sheet,
    SheetClose,
    SheetContent,
    SheetHeader,
    SheetTitle,
    SheetTrigger,
} from '@/components/ui/sheet';
import { cn } from '@/lib/utils';
import { dashboard, login, register } from '@/routes';

const page = usePage();
const user = computed(() => page.props.auth.user);
const scrolled = ref(false);

function onScroll(): void {
    scrolled.value = window.scrollY > 8;
}

onMounted(() => {
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
});

onUnmounted(() => {
    window.removeEventListener('scroll', onScroll);
});
</script>

<template>
    <header
        :class="
            cn(
                'sticky top-0 z-50 border-b border-white/10 transition-[background-color,backdrop-filter] duration-300',
                scrolled
                    ? 'bg-forest/90 backdrop-blur-md'
                    : 'bg-forest/95 backdrop-blur-sm',
            )
        "
    >
        <div
            class="mx-auto flex h-16 max-w-7xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8"
        >
            <a
                href="#top"
                class="flex items-center gap-2.5 text-ivory transition-colors hover:text-gold"
            >
                <AppLogoIcon class="h-8 w-8 text-ivory" />
                <span class="font-display text-lg font-semibold tracking-tight"
                    >Cylo</span
                >
            </a>

            <NavigationMenu class="hidden lg:flex" aria-label="Primary">
                <NavigationMenuList class="gap-0.5">
                    <NavigationMenuItem
                        v-for="link in landingNavLinks"
                        :key="link.id"
                    >
                        <a
                            :href="`#${link.id}`"
                            class="inline-flex h-9 items-center rounded-md px-2.5 text-sm text-ivory/70 transition-colors hover:bg-white/5 hover:text-ivory focus-visible:ring-2 focus-visible:ring-gold focus-visible:outline-none"
                        >
                            {{ link.label }}
                        </a>
                    </NavigationMenuItem>
                </NavigationMenuList>
            </NavigationMenu>

            <div class="flex items-center gap-2 sm:gap-3">
                <template v-if="user">
                    <Button
                        as-child
                        class="bg-gold-700 text-ivory hover:bg-gold-600"
                    >
                        <Link :href="dashboard()">Dashboard</Link>
                    </Button>
                </template>
                <template v-else>
                    <Button
                        as-child
                        variant="ghost"
                        class="hidden text-ivory/80 hover:bg-white/5 hover:text-ivory sm:inline-flex"
                    >
                        <Link :href="login()">Sign in</Link>
                    </Button>
                    <Button
                        as-child
                        class="bg-gold-700 text-ivory hover:bg-gold-600"
                    >
                        <Link :href="register()">Start a cycle</Link>
                    </Button>
                </template>

                <Sheet>
                    <SheetTrigger as-child>
                        <Button
                            variant="ghost"
                            size="icon"
                            class="text-ivory hover:bg-white/10 lg:hidden"
                            aria-label="Open navigation"
                        >
                            <Menu class="h-5 w-5" />
                        </Button>
                    </SheetTrigger>
                    <SheetContent
                        side="right"
                        class="border-white/10 bg-forest text-ivory"
                    >
                        <SheetHeader>
                            <SheetTitle class="text-ivory">Cylo</SheetTitle>
                        </SheetHeader>
                        <nav
                            class="mt-6 flex flex-col gap-1"
                            aria-label="Mobile"
                        >
                            <SheetClose
                                v-for="link in landingNavLinks"
                                :key="link.id"
                                as-child
                            >
                                <a
                                    :href="`#${link.id}`"
                                    class="flex min-h-11 items-center rounded-md px-3 text-base text-ivory/80 transition-colors hover:bg-white/5 hover:text-ivory"
                                >
                                    {{ link.label }}
                                </a>
                            </SheetClose>
                            <Separator class="my-3 bg-white/10" />
                            <template v-if="user">
                                <SheetClose as-child>
                                    <Link
                                        :href="dashboard()"
                                        class="flex min-h-11 items-center rounded-md px-3 text-base font-medium text-gold"
                                    >
                                        Dashboard
                                    </Link>
                                </SheetClose>
                            </template>
                            <template v-else>
                                <SheetClose as-child>
                                    <Link
                                        :href="login()"
                                        class="flex min-h-11 items-center rounded-md px-3 text-base text-ivory/80"
                                    >
                                        Sign in
                                    </Link>
                                </SheetClose>
                                <SheetClose as-child>
                                    <Link
                                        :href="register()"
                                        class="flex min-h-11 items-center rounded-md px-3 text-base font-medium text-gold"
                                    >
                                        Start a cycle
                                    </Link>
                                </SheetClose>
                            </template>
                        </nav>
                        <Badge
                            variant="outline"
                            class="mt-8 border-gold/40 font-mono text-[10px] tracking-wider text-gold uppercase"
                        >
                            Open source · ADLC
                        </Badge>
                    </SheetContent>
                </Sheet>
            </div>
        </div>
    </header>
</template>
