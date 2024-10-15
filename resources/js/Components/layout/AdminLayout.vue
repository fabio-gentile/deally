<script setup lang="ts">
import { Toaster } from "@/Components/ui/toast"
import { useToast } from "@/Components/ui/toast/use-toast"
import { Link, usePage } from "@inertiajs/vue3"
import { ref, watch } from "vue"
import { Badge } from "@/Components/ui/badge"

import { Button } from "@/Components/ui/button"
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from "@/Components/ui/dropdown-menu"
import { Sheet, SheetContent, SheetTrigger } from "@/Components/ui/sheet"
import {
  Bell,
  CircleUser,
  Home,
  LineChart,
  Menu,
  Package,
  Package2,
  Search,
  ShoppingCart,
  Users,
  BookOpen,
  Podcast,
  Flag,
  ChevronDown,
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
    // console.log(flash)
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
  blog: false,
  reports: false,
  users: false,
})

const isOpen = ref(false)
</script>

<template>
  <div>
    <Toaster />
    <div
      class="grid min-h-screen w-full md:grid-cols-[220px_1fr] lg:grid-cols-[280px_1fr]"
    >
      <div class="hidden border-r bg-white md:block">
        <div class="flex h-full max-h-screen flex-col gap-2">
          <div class="flex h-14 items-center border-b px-4 lg:h-[60px] lg:px-6">
            <a href="/" class="flex items-center gap-2 font-semibold">
              <Package2 class="h-6 w-6" />
              <span class="">Deally</span>
            </a>
          </div>
          <div class="flex-1">
            <nav
              class="grid items-start gap-3 px-2 text-sm font-medium lg:px-4"
            >
              <a
                href="/"
                class="flex items-center gap-3 rounded-lg px-3 py-2 text-muted-foreground transition-all hover:text-primary"
              >
                <Home class="h-4 w-4" />
                Tableau de bord
              </a>
              <Collapsible v-model:open="openCollapsibles['deals']">
                <CollapsibleTrigger
                  class="flex w-full items-center justify-between gap-3 rounded-lg px-3 py-2 text-muted-foreground transition-all hover:text-primary"
                >
                  <div class="flex items-center gap-4">
                    <Package class="h-4 w-4" /> Deals
                  </div>
                  <ChevronDown
                    class="h-4 w-4 transition-transform duration-200"
                    :class="
                      openCollapsibles['deals'] ? 'rotate-180' : 'rotate-0'
                    "
                  />
                  <span class="sr-only">Toggle</span></CollapsibleTrigger
                >
                <CollapsibleContent class="mt-2 flex flex-col gap-4">
                  <Link @click="openCollapsibles.deals = false" :href="'#'">
                    <div
                      class="ml-12 text-sm font-medium leading-none text-muted-foreground"
                    >
                      Voir les deals
                    </div>
                  </Link>
                  <Link @click="openCollapsibles.deals = false" :href="'#'">
                    <div
                      class="ml-12 text-sm font-medium leading-none text-muted-foreground"
                    >
                      Catégorie
                    </div>
                  </Link>
                </CollapsibleContent>
              </Collapsible>
              <Collapsible v-model:open="openCollapsibles['discussions']">
                <CollapsibleTrigger
                  class="flex w-full items-center justify-between gap-3 rounded-lg px-3 py-2 text-muted-foreground transition-all hover:text-primary"
                >
                  <div class="flex items-center gap-4">
                    <Podcast class="h-4 w-4" /> Discussions
                  </div>
                  <ChevronDown
                    class="h-4 w-4 transition-transform duration-200"
                    :class="
                      openCollapsibles['deals'] ? 'rotate-180' : 'rotate-0'
                    "
                  />
                  <span class="sr-only">Toggle</span></CollapsibleTrigger
                >
                <CollapsibleContent class="mt-2 flex flex-col gap-4">
                  <Link
                    @click="openCollapsibles.discussions = false"
                    :href="'#'"
                  >
                    <div
                      class="ml-12 text-sm font-medium leading-none text-muted-foreground"
                    >
                      Voir les discussions
                    </div>
                  </Link>
                  <Link
                    @click="openCollapsibles.discussions = false"
                    :href="'#'"
                  >
                    <div
                      class="ml-12 text-sm font-medium leading-none text-muted-foreground"
                    >
                      Catégorie
                    </div>
                  </Link>
                </CollapsibleContent>
              </Collapsible>
              <a
                href="#"
                class="flex items-center gap-3 rounded-lg px-3 py-2 text-muted-foreground transition-all hover:text-primary"
              >
                <Users class="h-4 w-4" />
                Utilisateurs
              </a>
              <a
                href="#"
                class="flex items-center gap-3 rounded-lg px-3 py-2 text-muted-foreground transition-all hover:text-primary"
              >
                <BookOpen class="h-4 w-4" />
                Blog
              </a>
              <a
                href="#"
                class="flex items-center gap-3 rounded-lg px-3 py-2 text-muted-foreground transition-all hover:text-primary"
              >
                <Flag class="h-4 w-4" />
                Signalements
              </a>
            </nav>
          </div>
          <div class="mt-auto p-4">
            <Link :href="route('home.index')">
              <Button size="sm" class="w-full"> Revenir au site web </Button>
            </Link>
          </div>
        </div>
      </div>
      <div class="flex flex-col">
        <header
          class="flex h-14 items-center gap-4 border-b bg-white px-4 lg:h-[60px] lg:px-6"
        >
          <Sheet>
            <SheetTrigger as-child>
              <Button variant="outline" size="icon" class="shrink-0 md:hidden">
                <Menu class="h-5 w-5" />
                <span class="sr-only">Toggle navigation menu</span>
              </Button>
            </SheetTrigger>
            <SheetContent side="left" class="flex flex-col">
              <nav class="grid gap-2 text-lg font-medium">
                <a
                  href="#"
                  class="flex items-center gap-2 text-lg font-semibold"
                >
                  <Package2 class="h-6 w-6" />
                  <span class="sr-only">Deally</span>
                </a>
                <a
                  href="#"
                  class="mx-[-0.65rem] flex items-center gap-4 rounded-xl px-3 py-2 text-muted-foreground hover:text-foreground"
                >
                  <Home class="h-5 w-5" />
                  Dashboard
                </a>
                <a
                  href="#"
                  class="mx-[-0.65rem] flex items-center gap-4 rounded-xl bg-muted px-3 py-2 text-foreground hover:text-foreground"
                >
                  <ShoppingCart class="h-5 w-5" />
                  Orders
                  <Badge
                    class="ml-auto flex h-6 w-6 shrink-0 items-center justify-center rounded-full"
                  >
                    6
                  </Badge>
                </a>
                <a
                  href="#"
                  class="mx-[-0.65rem] flex items-center gap-4 rounded-xl px-3 py-2 text-muted-foreground hover:text-foreground"
                >
                  <Package class="h-5 w-5" />
                  Products
                </a>
                <a
                  href="#"
                  class="mx-[-0.65rem] flex items-center gap-4 rounded-xl px-3 py-2 text-muted-foreground hover:text-foreground"
                >
                  <Users class="h-5 w-5" />
                  Customers
                </a>
                <a
                  href="#"
                  class="mx-[-0.65rem] flex items-center gap-4 rounded-xl px-3 py-2 text-muted-foreground hover:text-foreground"
                >
                  <LineChart class="h-5 w-5" />
                  Analytics
                </a>
              </nav>
              <div class="mt-auto">
                <Link :href="route('home.index')">
                  <Button size="sm" class="w-full">
                    Revenir au site web
                  </Button>
                </Link>
              </div>
            </SheetContent>
          </Sheet>
          <div class="ml-auto">
            <ThemeSwitcher />
            <DropdownMenu>
              <DropdownMenuTrigger as-child>
                <Button variant="secondary" size="icon" class="rounded-full">
                  <CircleUser class="h-5 w-5" />
                  <span class="sr-only">Toggle user menu</span>
                </Button>
              </DropdownMenuTrigger>
              <DropdownMenuContent align="end">
                <DropdownMenuLabel>My Account</DropdownMenuLabel>
                <DropdownMenuSeparator />
                <DropdownMenuItem>Settings</DropdownMenuItem>
                <DropdownMenuItem>Support</DropdownMenuItem>
                <DropdownMenuSeparator />
                <DropdownMenuItem>Logout</DropdownMenuItem>
              </DropdownMenuContent>
            </DropdownMenu>
          </div>
        </header>
        <main class="flex flex-1 flex-col gap-4 bg-page p-4 lg:gap-6 lg:p-6">
          <slot />
          <!--          <div class="flex items-center">-->
          <!--            <h1 class="text-lg font-semibold md:text-2xl">Inventory</h1>-->
          <!--          </div>-->
          <!--          <div-->
          <!--            class="flex flex-1 items-center justify-center rounded-lg border border-dashed shadow-sm"-->
          <!--          >-->
          <!--            <div class="flex flex-col items-center gap-1 text-center">-->
          <!--              <h3 class="text-2xl font-bold tracking-tight">-->
          <!--                You have no products-->
          <!--              </h3>-->
          <!--              <p class="text-sm text-muted-foreground">-->
          <!--                You can start selling as soon as you add a product.-->
          <!--              </p>-->
          <!--              <Button class="mt-4"> Add Product </Button>-->
          <!--            </div>-->
          <!--          </div>-->
        </main>
      </div>
    </div>
  </div>
</template>
