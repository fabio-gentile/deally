<script setup lang="ts">
import { Link, usePage } from "@inertiajs/vue3"
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
import SheetContent from "@/Components/ui/sheet/SheetContent.vue"
import SheetTrigger from "@/Components/ui/sheet/SheetTrigger.vue"
import Sheet from "@/Components/ui/sheet/Sheet.vue"
import { Button } from "@/Components/ui/button"
import { CircleUser, Package2, Menu, Plus, ChevronDown } from "lucide-vue-next"
import ThemeSwitcher from "@/Components/ThemeSwitcher.vue"
import Separator from "@/Components/ui/separator/Separator.vue"

type MenuType = {
  title: string
  href: string
  description: string
}

const deals: MenuType[] = [
  {
    title: "Populaires",
    href: "/docs/primitives/alert-dialog",
    description: "Les bons plans les plus populaires du moment.",
  },
  {
    title: "Nouveautés",
    href: "/docs/primitives/hover-card",
    description: "Les derniers bons plans ajoutés sur la plateforme.",
  },
  {
    title: "Pour vous",
    href: "/docs/primitives/progress",
    description: "Les bons plans qui pourraient vous intéresser.",
  },
  {
    title: "Catégorie",
    href: "/docs/primitives/progress",
    description: "Voir toutes les catégories de bons plans disponibles.",
  },
]
const forums: MenuType[] = [
  {
    title: "Populaires",
    href: "/docs/primitives/alert-dialog",
    description: "Les discussions les plus populaires du moment.",
  },
  {
    title: "Nouveautés",
    href: "/docs/primitives/hover-card",
    description: "Les derniers discussions ajoutés sur la plateforme.",
  },
  {
    title: "Catégorie",
    href: "/docs/primitives/progress",
    description: "Voir toutes les catégories de discussions disponibles.",
  },
]
const resources: MenuType[] = [
  {
    title: "FAQ",
    href: "/docs/primitives/alert-dialog",
    description: "Les questions les plus fréquentes.",
  },
  {
    title: "Blog",
    href: "/docs/primitives/hover-card",
    description: "Les derniers articles publiés par l'équipe Deally.",
  },
  {
    title: "Contact",
    href: "/docs/primitives/progress",
    description: "Contacter l'équipe Deally pour toute question.",
  },
  {
    title: "Conditions générales",
    href: "/docs/primitives/progress",
    description:
      "Les conditions générales d'utilisation de la plateforme Deally.",
  },
  {
    title: "Politique de confidentialité",
    href: "/docs/primitives/progress",
    description: "La politique de confidentialité de la plateforme Deally.",
  },
  {
    title: "Politique d'utilisation des cookies",
    href: "/docs/primitives/progress",
    description:
      "La politique d'utilisation des cookies de la plateforme Deally.",
  },
  {
    title: "Mentions légales",
    href: "/docs/primitives/progress",
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
</script>

<template>
  <header
    class="sticky top-0 flex h-16 items-center gap-4 border-b bg-background px-4 md:px-6"
  >
    <nav
      class="hidden flex-col gap-6 text-lg font-medium md:flex md:flex-row md:items-center md:gap-5 md:text-sm lg:gap-6"
    >
      <Link
        href="#"
        class="flex items-center gap-2 text-lg font-semibold md:text-base"
      >
        <!--          TODO: Add logo here-->
        <Package2 class="h-6 w-6" />
        <span class="sr-only">Deally</span>
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
    <Sheet>
      <SheetTrigger asChild>
        <Button variant="outline" size="icon" class="shrink-0 lg:hidden">
          <Menu class="h-5 w-5" />
          <span class="sr-only">Activer le menu de nagivation</span>
        </Button>
      </SheetTrigger>
      <SheetContent side="left">
        <nav class="mb-4 grid gap-10 text-lg font-medium">
          <Link href="#" class="flex items-center gap-2 text-lg font-semibold">
            <!--              TODO: Add logo here-->
            <Package2 class="h-6 w-6" />
            <span class="sr-only">Acme Inc</span>
          </Link>
          <ul class="grid gap-10">
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
                    v-for="deal in mobileMenu.menu"
                    :href="deal.href"
                    class="block select-none space-y-1 rounded-md p-3 leading-none no-underline outline-none transition-colors hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground"
                  >
                    <div class="text-sm font-medium leading-none">
                      {{ deal.title }}
                    </div>
                  </Link>
                </CollapsibleContent class="mt-2">
              </Collapsible>
            </li>
          </ul>
          <ul class="grid gap-6" v-if="!$page.props.auth.user?.name">
            <li>
              <Link :href="route('login')">Se connecter</Link>
            </li>
            <li>
              <Link :href="route('register')">Créer un compte</Link>
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
                  href="#"
                  class="block select-none space-y-1 rounded-md p-3 leading-none no-underline outline-none transition-colors hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground"
                >
                  <div class="text-sm font-medium leading-none">Favoris</div>
                </Link>
                <Link
                  href="#"
                  class="block select-none space-y-1 rounded-md p-3 leading-none no-underline outline-none transition-colors hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground"
                >
                  <div class="text-sm font-medium leading-none">
                    Deals postés
                  </div>
                </Link>
                <Link
                  href="#"
                  class="block select-none space-y-1 rounded-md p-3 leading-none no-underline outline-none transition-colors hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground"
                >
                  <div class="text-sm font-medium leading-none">
                    Discussions
                  </div>
                </Link>
                <Link
                  href="#"
                  class="block select-none space-y-1 rounded-md p-3 leading-none no-underline outline-none transition-colors hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground"
                >
                  <div class="text-sm font-medium leading-none">Newsletter</div>
                </Link>
                <Link
                  href="#"
                  class="block select-none space-y-1 rounded-md p-3 leading-none no-underline outline-none transition-colors hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground"
                >
                  <div class="text-sm font-medium leading-none">
                    Statistiques
                  </div>
                </Link>
                <Link
                  href="#"
                  class="block select-none space-y-1 rounded-md p-3 leading-none no-underline outline-none transition-colors hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground"
                >
                  <div class="text-sm font-medium leading-none">Paramètres</div>
                </Link>
                <Separator />
                <Link
                  :href="route('logout')"
                  method="post"
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
          <ul class="w-full absolute bottom-2 left-1/2 transform -translate-x-1/2">

              <li class="block md:hidden">
                  <ThemeSwitcher />
              </li>
          <li class="mt-4 text-center text-sm">Copyright 2023 - 2024. Deally. Tous droits réservés</li>
          </ul>

      </SheetContent>
    </Sheet>
    <div
      class="flex w-full items-center justify-end gap-2 md:ml-auto md:gap-2 lg:gap-4"
    >
      <SearchBar />
      <DropdownMenu>
        <DropdownMenuTrigger asChild>
          <Button variant="link">
            <CircleUser />
            <span class="sr-only">Ouvrir menu compte</span>
          </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end" v-if="!$page.props.auth.user?.name">
          <DropdownMenuLabel>Compte</DropdownMenuLabel>
          <DropdownMenuSeparator />
          <DropdownMenuItem>
            <Link :href="route('login')"> Se connecter </Link>
          </DropdownMenuItem>
          <DropdownMenuItem
            ><Link :href="route('register')"
              >Créer un compte</Link
            ></DropdownMenuItem
          >
        </DropdownMenuContent>
        <DropdownMenuContent align="end" v-else>
          <DropdownMenuItem>
            <Link href="#">Favoris</Link>
          </DropdownMenuItem>
          <DropdownMenuItem>
            <Link href="#">Deals postés</Link>
          </DropdownMenuItem>
          <DropdownMenuItem>
            <Link href="#">Discussions</Link>
          </DropdownMenuItem>
          <DropdownMenuItem>
            <Link href="#">Newsletter</Link>
          </DropdownMenuItem>
          <DropdownMenuItem>
            <Link href="#">Statistiques</Link>
          </DropdownMenuItem>
          <DropdownMenuItem>
            <Link href="#">Paramètres</Link>
          </DropdownMenuItem>
          <DropdownMenuSeparator />
          <DropdownMenuItem>
            <Link :href="route('logout')" method="post">Déconnexion</Link>
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
            <Link href="#">Bon plan</Link></DropdownMenuItem
          >
          <DropdownMenuItem class="cursor-pointer">
            <Link href="#">Discussion</Link></DropdownMenuItem
          >
        </DropdownMenuContent>
      </DropdownMenu>
      <div class="hidden md:block">
        <ThemeSwitcher />
      </div>
    </div>
  </header>
</template>
