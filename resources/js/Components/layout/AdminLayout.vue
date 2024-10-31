<script setup lang="ts">
import { Toaster } from "@/Components/ui/toast"
import { useToast } from "@/Components/ui/toast/use-toast"
import { Link, usePage, Head } from "@inertiajs/vue3"
import { ref, watch } from "vue"
import { Button } from "@/Components/ui/button"
import { Sheet, SheetContent, SheetTrigger } from "@/Components/ui/sheet"
import {
  Home,
  Menu,
  Package,
  Package2,
  Users,
  BookOpen,
  Podcast,
  Flag,
  ChevronDown,
  StickyNote,
  Mail,
} from "lucide-vue-next"
import ThemeSwitcher from "@/Components/ThemeSwitcher.vue"
import {
  Collapsible,
  CollapsibleContent,
  CollapsibleTrigger,
} from "@/Components/ui/collapsible"
const page = usePage()

const { toast } = useToast()
watch(
  () => usePage().props.flash,
  (flash: { success: string | null; error: string | null }) => {
    if (flash.success) {
      toast({
        description: flash.success,
        duration: 3000,
      })
    }

    if (flash.error) {
      toast({
        description: flash.error,
        variant: "destructive",
        duration: 3000,
      })
    }
  },
  { deep: true, immediate: true }
)

const openCollapsibles = ref<{ [key: string]: boolean }>({
  deals: false,
  discussions: false,
})

const links = [
  {
    title: "Tableau de bord",
    icon: Home,
    href: route("admin.dashboard"),
  },
  {
    title: "Deals",
    icon: Package,
    children: [
      {
        title: "Voir les deals",
        href: route("admin.deals.list"),
      },
      {
        title: "Catégorie",
        href: route("admin.categories-deals.list"),
      },
    ],
  },
  {
    title: "Discussions",
    icon: Podcast,
    children: [
      {
        title: "Voir les discussions",
        href: route("admin.discussions.list"),
      },
      {
        title: "Catégorie",
        href: route("admin.categories-discussions.list"),
      },
    ],
  },
  {
    title: "Utilisateurs",
    icon: Users,
    href: route("admin.users.list"),
  },
  {
    title: "Blog",
    icon: BookOpen,
    href: route("admin.blog.list"),
  },
  {
    title: "Signalements",
    icon: Flag,
    href: route("admin.reports.list"),
  },
  {
    title: "Pages",
    icon: StickyNote,
    href: route("admin.pages.list"),
  },
  {
    title: "Contact",
    icon: Mail,
    href: route("admin.contacts.list"),
  },
]

const isOpen = ref(false)

const handleLinkClick = () => {
  isOpen.value = false // Close the mobile menu
  // Reset all collapsibles to false
  Object.keys(openCollapsibles.value).forEach((key) => {
    openCollapsibles.value[key] = false
  })
}
</script>

<template>
  <Head>
    <link rel="icon" type="image/svg+xml" href="/logo.svg" />
    <meta name="robots" content="noindex, follow" />
  </Head>
  <div>
    <Toaster />
    <div class="min-h-screen w-full flex-row overflow-hidden md:flex">
      <div
        class="fixed hidden border-r bg-white md:block md:w-[220px] lg:w-[280px]"
      >
        <div class="min-h-screen w-full flex-row md:flex">
          <div
            class="hidden border-r bg-white md:block md:w-[220px] lg:w-[280px]"
          >
            <div class="flex h-full max-h-screen flex-col gap-2">
              <div
                class="flex h-14 items-center border-b px-4 lg:h-[60px] lg:px-6"
              >
                <a href="/" class="flex items-center gap-2 font-semibold">
                  <Package2 class="h-6 w-6" />
                  <span class="">Deally</span>
                </a>
              </div>
              <div class="flex-1">
                <nav
                  class="grid items-start gap-3 px-2 text-sm font-medium lg:px-4"
                >
                  <template v-for="(link, index) in links" :key="index">
                    <Collapsible
                      class=""
                      v-if="link.children"
                      v-model:open="openCollapsibles[link.title]"
                    >
                      <CollapsibleTrigger
                        class="flex w-full items-center justify-between gap-3 rounded-lg px-3 py-2 text-muted-foreground transition-all hover:text-primary"
                      >
                        <div class="flex items-center gap-4">
                          <component :is="link.icon" class="h-4 w-4" />
                          {{ link.title }}
                        </div>
                        <ChevronDown
                          class="h-4 w-4 transition-transform duration-200"
                          :class="
                            openCollapsibles[link.title]
                              ? 'rotate-180'
                              : 'rotate-0'
                          "
                        />
                        <span class="sr-only">Toggle</span>
                      </CollapsibleTrigger>
                      <CollapsibleContent class="flex flex-col gap-4">
                        <!-- Collapsible Links -->
                        <Link
                          v-for="(child, childIndex) in link.children"
                          :key="childIndex"
                          :href="child.href"
                          @click="openCollapsibles[link.title] = false"
                          :class="[childIndex === 0 ? 'mt-2' : '']"
                        >
                          <div
                            class="ml-12 text-sm font-medium leading-none text-muted-foreground"
                          >
                            {{ child.title }}
                          </div>
                        </Link>
                      </CollapsibleContent>
                    </Collapsible>

                    <!-- Regular links -->
                    <Link
                      v-else
                      :href="link.href"
                      class="flex items-center gap-3 rounded-lg px-3 py-2 text-muted-foreground transition-all hover:text-primary"
                    >
                      <component :is="link.icon" class="h-4 w-4" />
                      {{ link.title }}
                    </Link>
                  </template>
                </nav>
              </div>
              <div class="mt-auto p-4">
                <a :href="route('home.index')">
                  <Button size="sm" class="w-full">
                    Revenir au site web
                  </Button>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="flex grow flex-col">
        <header
          class="flex h-14 items-center gap-4 border-b bg-white px-4 lg:h-[60px] lg:px-6"
        >
          <Sheet v-model:open="isOpen">
            <SheetTrigger as-child>
              <Button variant="outline" size="icon" class="shrink-0 md:hidden">
                <Menu class="h-5 w-5" />
                <span class="sr-only">Toggle navigation menu</span>
              </Button>
            </SheetTrigger>
            <SheetContent side="left" class="flex flex-col">
              <nav
                class="mt-6 grid items-start gap-3 px-2 text-sm font-medium lg:px-4"
              >
                <template v-for="(link, index) in links" :key="index">
                  <!-- Collapsible Links -->
                  <Collapsible
                    v-if="link.children"
                    v-model:open="openCollapsibles[link.title]"
                  >
                    <CollapsibleTrigger
                      class="flex w-full items-center justify-between gap-3 rounded-lg px-3 py-2 text-muted-foreground transition-all hover:text-primary"
                    >
                      <div class="flex items-center gap-4">
                        <component :is="link.icon" class="h-4 w-4" />
                        {{ link.title }}
                      </div>
                      <ChevronDown
                        class="h-4 w-4 transition-transform duration-200"
                        :class="
                          openCollapsibles[link.title]
                            ? 'rotate-180'
                            : 'rotate-0'
                        "
                      />
                      <span class="sr-only">Toggle</span>
                    </CollapsibleTrigger>
                    <CollapsibleContent class="flex flex-col gap-4">
                      <Link
                        v-for="(child, childIndex) in link.children"
                        :key="childIndex"
                        :href="child.href"
                        @click="handleLinkClick"
                        :class="[childIndex === 0 ? 'mt-2' : '']"
                      >
                        <div
                          class="ml-12 text-sm font-medium leading-none text-muted-foreground"
                        >
                          {{ child.title }}
                        </div>
                      </Link>
                    </CollapsibleContent>
                  </Collapsible>

                  <!-- Regular Links -->
                  <Link
                    v-else
                    :href="link.href"
                    class="flex items-center gap-3 rounded-lg px-3 py-2 text-muted-foreground transition-all hover:text-primary"
                    @click="handleLinkClick"
                  >
                    <component :is="link.icon" class="h-4 w-4" />
                    {{ link.title }}
                  </Link>
                </template>
              </nav>
              <div class="mt-auto">
                <a :href="route('home.index')">
                  <Button size="sm" class="w-full">
                    Revenir au site web
                  </Button>
                </a>
              </div>
            </SheetContent>
          </Sheet>
          <div class="ml-auto">
            <ThemeSwitcher />
          </div>
        </header>
        <main
          class="flex flex-1 flex-col gap-4 overflow-hidden bg-page p-4 md:ml-[220px] md:max-w-[calc(100dvw-220px)] lg:ml-[280px] lg:max-w-[calc(100dvw-280px)] lg:gap-6 lg:p-6"
        >
          <slot />
        </main>
      </div>
    </div>
  </div>
</template>
