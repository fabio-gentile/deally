<script setup lang="ts">
import { Link } from "@inertiajs/vue3"
import { computed, onMounted, ref } from "vue"
import {
  Collapsible,
  CollapsibleContent,
  CollapsibleTrigger,
} from "@/Components/ui/collapsible"
import SearchBar from "@/Components/SearchBar.vue"

import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from "@/Components/ui/dropdown-menu"
import {
  NavigationMenu,
  NavigationMenuContent,
  NavigationMenuItem,
  NavigationMenuLink,
  NavigationMenuList,
  NavigationMenuTrigger,
  navigationMenuTriggerStyle,
} from "@/Components/ui/navigation-menu"
import { Button } from "@/Components/ui/button"
import { CircleUser, Menu, Plus, ChevronDown } from "lucide-vue-next"
import ThemeSwitcher from "@/Components/ThemeSwitcher.vue"
import Separator from "@/Components/ui/separator/Separator.vue"
import {
  Sheet,
  SheetContent,
  SheetDescription,
  SheetHeader,
  SheetTitle,
  SheetTrigger,
} from "@/Components/ui/sheet"

type MenuType = {
  title: string
  href: string
  description: string
}

const deals: MenuType[] = [
  {
    title: "Populaires",
    href: route("search.deals") + "?filter_by=popular",
    description: "Les deals les plus populaires du moment.",
  },
  {
    title: "Nouveautés",
    href: route("search.deals") + "?filter_by=newest",
    description: "Les derniers deals ajoutés sur la plateforme.",
  },
  {
    title: "Pour vous",
    href: route("home.for-you"),
    description: "Les deals qui pourraient vous intéresser.",
  },
]
const forums: MenuType[] = [
  {
    title: "Populaires",
    href: route("search.discussions") + "?filter_by=popular",
    description: "Les discussions les plus populaires du moment.",
  },
  {
    title: "Nouveautés",
    href: route("search.discussions") + "?filter_by=newest",
    description: "Les derniers discussions ajoutés sur la plateforme.",
  },
]
const resources: MenuType[] = [
  {
    title: "Blog",
    href: route("blog.index"),
    description: "Les derniers articles publiés par l'équipe Deally.",
  },
  {
    title: "FAQ",
    href: route("pages.faq"),
    description: "Les questions les plus fréquentes.",
  },
  {
    title: "À propos",
    href: route("pages.about"),
    description: "En savoir plus sur la plateforme Deally.",
  },
  {
    title: "Contact",
    href: route("contact.create"),
    description: "Contacter l'équipe Deally pour toute question.",
  },
  {
    title: "Conditions générales",
    href: route("pages.cgu"),
    description:
      "Les conditions générales d'utilisation de la plateforme Deally.",
  },
  {
    title: "Politique de confidentialité",
    href: route("pages.privacy-policy"),
    description: "La politique de confidentialité de la plateforme Deally.",
  },
  {
    title: "Politique d'utilisation des cookies",
    href: route("pages.cookie-policy"),
    description:
      "La politique d'utilisation des cookies de la plateforme Deally.",
  },
  {
    title: "Mentions légales",
    href: route("pages.legal-mentions"),
    description: "Les mentions légales de la plateforme Deally.",
  },
]

type MobileMenuType = {
  title: string
  menu: MenuType[]
}

const mobileMenus: MobileMenuType[] = [
  {
    title: "Bon plans",
    menu: deals,
  },
  {
    title: "Forum",
    menu: forums,
  },
  {
    title: "Resources",
    menu: resources,
  },
]

const openCollapsibles = ref<{ [key: string]: boolean }>({
  bonPlans: false,
  forum: false,
  resources: false,
})

const isOpen = ref(false)
const isSheetOpen = ref(false)
const closeSheet = () => {
  isSheetOpen.value = false
  isOpen.value = false
  openCollapsibles.value = {
    bonPlans: false,
    forum: false,
    resources: false,
  }
}
</script>

<template>
  <header
    class="sticky top-0 z-50 flex h-16 items-center gap-4 border-b bg-background px-4 md:px-6"
  >
    <nav
      class="hidden flex-col gap-6 text-lg font-medium md:flex md:flex-row md:items-center md:gap-5 md:text-sm lg:gap-6"
    >
      <Link
        class="hidden h-10 w-20 rounded-lg lg:inline-flex lg:items-center"
        :href="route('home.index')"
      >
        <img
          class="h-full rounded-lg"
          src="/logo-deally.svg"
          type="logo"
          alt="Logo Deally"
        />
      </Link>
      <NavigationMenu class="hidden lg:block">
        <NavigationMenuList>
          <NavigationMenuItem>
            <NavigationMenuTrigger class="text-base"
              >Bon plans</NavigationMenuTrigger
            >
            <NavigationMenuContent>
              <ul
                class="grid w-[400px] gap-3 p-4 md:w-[500px] md:grid-cols-2 lg:w-[600px]"
              >
                <li v-for="deal in deals" :key="deal.title">
                  <NavigationMenuLink as-child>
                    <Link
                      :href="deal.href"
                      class="block select-none space-y-1 rounded-md p-3 leading-none no-underline outline-none transition-colors hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground"
                    >
                      <div class="font-medium leading-none">
                        {{ deal.title }}
                      </div>
                      <p
                        class="line-clamp-2 leading-snug text-muted-foreground"
                      >
                        {{ deal.description }}
                      </p>
                    </Link>
                  </NavigationMenuLink>
                </li>
              </ul>
            </NavigationMenuContent>
          </NavigationMenuItem>
          <NavigationMenuItem>
            <NavigationMenuTrigger class="text-base"
              >Forum</NavigationMenuTrigger
            >
            <NavigationMenuContent>
              <ul
                class="grid w-[400px] gap-3 p-4 md:w-[500px] md:grid-cols-2 lg:w-[600px]"
              >
                <li v-for="forum in forums" :key="forum.title">
                  <NavigationMenuLink as-child>
                    <Link
                      :href="forum.href"
                      class="block select-none space-y-1 rounded-md p-3 leading-none no-underline outline-none transition-colors hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground"
                    >
                      <div class="font-medium leading-none">
                        {{ forum.title }}
                      </div>
                      <p
                        class="line-clamp-2 leading-snug text-muted-foreground"
                      >
                        {{ forum.description }}
                      </p>
                    </Link>
                  </NavigationMenuLink>
                </li>
              </ul>
            </NavigationMenuContent>
          </NavigationMenuItem>
          <NavigationMenuItem>
            <NavigationMenuTrigger class="text-base"
              >Resources</NavigationMenuTrigger
            >
            <NavigationMenuContent>
              <ul
                class="grid w-[400px] gap-3 p-4 md:w-[500px] md:grid-cols-2 lg:w-[600px]"
              >
                <li v-for="resource in resources" :key="resource.title">
                  <NavigationMenuLink as-child>
                    <Link
                      :href="resource.href"
                      class="block select-none space-y-1 rounded-md p-3 leading-none no-underline outline-none transition-colors hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground"
                    >
                      <div class="font-medium leading-none">
                        {{ resource.title }}
                      </div>
                      <p
                        class="line-clamp-2 leading-snug text-muted-foreground"
                      >
                        {{ resource.description }}
                      </p>
                    </Link>
                  </NavigationMenuLink>
                </li>
              </ul>
            </NavigationMenuContent>
          </NavigationMenuItem>
        </NavigationMenuList>
      </NavigationMenu>
    </nav>

    <Sheet :open="isSheetOpen" @update:open="isSheetOpen = !isSheetOpen">
      <SheetTrigger asChild>
        <Button variant="outline" size="icon" class="shrink-0 lg:hidden">
          <Menu class="h-5 w-5" />
          <span class="sr-only">Activer le menu de nagivation</span>
        </Button>
      </SheetTrigger>
      <SheetContent side="left">
        <SheetHeader class="sr-only">
          <SheetTitle>Menu mobile</SheetTitle>
          <SheetDescription>
            Naviguez à travers les différentes sections de la plateforme.
          </SheetDescription>
        </SheetHeader>
        <nav class="mb-4 grid gap-8 text-lg font-medium">
          <Link @click="closeSheet" :href="route('home.index')">
            <img
              class="h-8 rounded-lg object-contain"
              src="/logo-deally.svg"
              type="logo"
              alt="Logo Deally"
            />
          </Link>
          <ul class="grid gap-8">
            <li v-for="mobileMenu in mobileMenus" :key="mobileMenu.title">
              <Collapsible v-model:open="openCollapsibles[mobileMenu.title]">
                <CollapsibleTrigger
                  class="flex w-full items-center justify-between gap-6"
                  >{{ mobileMenu.title }}
                  <ChevronDown
                    class="h-4 w-4 transition-transform duration-200"
                    :class="
                      openCollapsibles[mobileMenu.title]
                        ? 'rotate-180'
                        : 'rotate-0'
                    "
                  />
                  <span class="sr-only">Toggle</span></CollapsibleTrigger
                >
                <CollapsibleContent class="mt-2">
                  <Link
                    @click="closeSheet"
                    v-for="deal in mobileMenu.menu"
                    :href="deal.href"
                    class="block select-none space-y-1 rounded-md p-3 leading-none no-underline outline-none transition-colors hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground"
                  >
                    <div class="text-sm font-medium leading-none">
                      {{ deal.title }}
                    </div>
                  </Link>
                </CollapsibleContent>
              </Collapsible>
            </li>
          </ul>
          <ul class="grid gap-6" v-if="!$page.props.auth.user?.name">
            <li>
              <Link @click="closeSheet" :href="route('login')"
                >Se connecter</Link
              >
            </li>
            <li>
              <Link @click="closeSheet" :href="route('register')"
                >Créer un compte</Link
              >
            </li>
          </ul>
          <ul class="grid gap-6" v-else>
            <Collapsible v-model:open="isOpen">
              <CollapsibleTrigger
                class="flex w-full items-center justify-between gap-6"
                >{{ $page.props.auth.user?.name }}
                <ChevronDown
                  class="h-4 w-4 transition-transform duration-200"
                  :class="isOpen ? 'rotate-180' : 'rotate-0'"
                />
                <span class="sr-only">Toggle</span></CollapsibleTrigger
              >
              <CollapsibleContent class="mt-2">
                <Link
                  @click="closeSheet"
                  :href="route('profile.favorite', $page.props.auth.user.name)"
                  class="block select-none space-y-1 rounded-md p-3 leading-none no-underline outline-none transition-colors hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground"
                >
                  <div class="text-sm font-medium leading-none">Favoris</div>
                </Link>
                <Link
                  @click="closeSheet"
                  :href="route('profile.deals', $page.props.auth.user.name)"
                  class="block select-none space-y-1 rounded-md p-3 leading-none no-underline outline-none transition-colors hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground"
                >
                  <div class="text-sm font-medium leading-none">
                    Deals postés
                  </div>
                </Link>
                <Link
                  @click="closeSheet"
                  :href="
                    route('profile.discussions', $page.props.auth.user.name)
                  "
                  class="block select-none space-y-1 rounded-md p-3 leading-none no-underline outline-none transition-colors hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground"
                >
                  <div class="text-sm font-medium leading-none">
                    Discussions
                  </div>
                </Link>
                <Link
                  @click="closeSheet"
                  :href="
                    route('profile.statistics', $page.props.auth.user.name)
                  "
                  class="block select-none space-y-1 rounded-md p-3 leading-none no-underline outline-none transition-colors hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground"
                >
                  <div class="text-sm font-medium leading-none">Newsletter</div>
                </Link>
                <Link
                  @click="closeSheet"
                  :href="route('profile.favorite', $page.props.auth.user.name)"
                  class="block select-none space-y-1 rounded-md p-3 leading-none no-underline outline-none transition-colors hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground"
                >
                  <div class="text-sm font-medium leading-none">
                    Statistiques
                  </div>
                </Link>
                <Link
                  @click="closeSheet"
                  :href="route('profile.settings', $page.props.auth.user.name)"
                  class="block select-none space-y-1 rounded-md p-3 leading-none no-underline outline-none transition-colors hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground"
                >
                  <div class="text-sm font-medium leading-none">Paramètres</div>
                </Link>
                <Separator />
                <Link
                  @click="closeSheet"
                  :href="route('logout')"
                  method="post"
                  as="button"
                  class="block select-none space-y-1 rounded-md p-3 leading-none no-underline outline-none transition-colors hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground"
                >
                  <div class="text-sm font-medium leading-none">
                    Déconnexion
                  </div>
                </Link>
              </CollapsibleContent>
            </Collapsible>
          </ul>
        </nav>
        <ul
          class="absolute bottom-2 left-1/2 w-full -translate-x-1/2 transform"
        >
          <li class="block md:hidden">
            <ThemeSwitcher />
          </li>
          <li class="mt-4 text-center text-sm">
            Copyright {{ new Date().getFullYear() }}. Tous droits réservés
          </li>
        </ul>
      </SheetContent>
    </Sheet>
    <div
      class="flex w-full items-center justify-end gap-2 md:ml-auto md:gap-2 lg:gap-4"
    >
      <SearchBar />
      <DropdownMenu>
        <DropdownMenuTrigger asChild>
          <Button class="!hidden sm:!inline-flex" variant="ghost">
            <CircleUser />
            <span class="sr-only">Ouvrir menu compte</span>
          </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end" v-if="!$page.props.auth.user?.name">
          <DropdownMenuLabel>Compte</DropdownMenuLabel>
          <DropdownMenuSeparator />
          <DropdownMenuItem>
            <Link class="w-full" :href="route('login')"> Se connecter </Link>
          </DropdownMenuItem>
          <DropdownMenuItem
            ><Link class="w-full" :href="route('register')"
              >Créer un compte</Link
            ></DropdownMenuItem
          >
        </DropdownMenuContent>
        <DropdownMenuContent align="end" v-else>
          <DropdownMenuItem>
            <Link
              class="w-full"
              :href="route('profile.favorite', $page.props.auth.user.name)"
              >Favoris</Link
            >
          </DropdownMenuItem>
          <DropdownMenuItem>
            <Link
              class="w-full"
              :href="route('profile.deals', $page.props.auth.user.name)"
              >Deals postés</Link
            >
          </DropdownMenuItem>
          <DropdownMenuItem>
            <Link
              class="w-full"
              :href="route('profile.discussions', $page.props.auth.user.name)"
              >Discussions</Link
            >
          </DropdownMenuItem>
          <DropdownMenuItem>
            <Link
              class="w-full"
              :href="route('profile.notifications', $page.props.auth.user.name)"
              >Notifications</Link
            >
          </DropdownMenuItem>
          <DropdownMenuItem>
            <Link
              class="w-full"
              :href="route('profile.statistics', $page.props.auth.user.name)"
              >Statistiques</Link
            >
          </DropdownMenuItem>
          <DropdownMenuItem>
            <Link
              class="w-full"
              :href="route('profile.settings', $page.props.auth.user.name)"
              >Paramètres</Link
            >
          </DropdownMenuItem>
          <DropdownMenuSeparator />
          <DropdownMenuItem>
            <Link
              class="w-full text-left"
              :href="route('logout')"
              method="post"
              as="button"
              >Déconnexion</Link
            >
          </DropdownMenuItem>
        </DropdownMenuContent>
      </DropdownMenu>
      <DropdownMenu>
        <DropdownMenuTrigger asChild>
          <Button>
            <Plus class="md:mr-2" />
            <span class="hidden md:block">Publier</span>
          </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end">
          <DropdownMenuItem class="cursor-pointer">
            <Link class="w-full" :href="route('deals.create')"
              >Bon plan</Link
            ></DropdownMenuItem
          >
          <DropdownMenuItem class="cursor-pointer">
            <Link class="w-full" :href="route('discussions.create')"
              >Discussion</Link
            ></DropdownMenuItem
          >
        </DropdownMenuContent>
      </DropdownMenu>
      <div class="hidden md:block">
        <ThemeSwitcher />
      </div>
    </div>
  </header>
</template>
