<script setup lang="ts">
import { Handshake, MessageSquareText, PencilLine } from "lucide-vue-next"
import { Link, usePage } from "@inertiajs/vue3"

const page = usePage()
const props = defineProps<{
  user: {
    name: string
    avatar: string
  }
  dealsCount: number
  discussionsCount: number
  commentsCount: number
}>()
</script>

<template>
  <div class="grid w-full gap-6 bg-white px-8 py-9 sm:place-items-center">
    <div class="grid place-items-center gap-4">
      <img
        src="/images/avatar.jpg"
        :alt="'Avatar de Marc'"
        class="avatar h-24 rounded-full object-contain"
      />
      <h2 class="text-2xl font-semibold text-foreground">{{ user.name }}</h2>
    </div>
    <div
      class="flex flex-col flex-wrap gap-8 font-medium text-muted-foreground sm:flex-row"
    >
      <div class="flex flex-wrap items-center gap-2">
        <Handshake />
        <p>{{ dealsCount }} deal{{ dealsCount > 1 ? "s" : "" }}</p>
      </div>
      <div class="flex flex-wrap items-center gap-2">
        <PencilLine />
        <p>
          {{ discussionsCount }} discussion{{ discussionsCount > 1 ? "s" : "" }}
        </p>
      </div>
      <div class="flex flex-wrap items-center gap-2">
        <MessageSquareText />
        <p>{{ commentsCount }} commentaire{{ commentsCount > 1 ? "s" : "" }}</p>
      </div>
    </div>
    <p class="max-w-[800px] text-muted-foreground">
      Lorem ipsum dolor sit amet consectetur. Pharetra nisl velit nibh enim duis
      venenatis. Fames eget cras blandit maecenas. In integer gravida viverra
      eget odio egestas hac semper vitae commodo vestibulum.
    </p>

    <nav class="flex flex-wrap justify-center">
      <ul
        class="grid w-full grid-cols-2 justify-between gap-4 sm:flex sm:grid-cols-6 sm:flex-row sm:flex-wrap sm:justify-normal sm:gap-8"
      >
        <li>
          <Link
            :href="route('profile.favorite')"
            :class="[
              page.url.includes('/profil') ||
              page.url.includes('/profil/favoris')
                ? 'font-semibold text-primary'
                : 'font-medium text-muted-foreground',
            ]"
          >
            Favoris
          </Link>
        </li>
        <li>
          <Link
            :href="route('profile.deals')"
            :class="[
              page.url.includes('/profil/deals')
                ? 'font-semibold text-primary'
                : 'font-medium text-muted-foreground',
            ]"
          >
            Deals
          </Link>
        </li>
        <li>
          <Link
            :href="route('profile.discussions')"
            :class="[
              page.url.includes('/profil/discussions')
                ? 'font-semibold text-primary'
                : 'font-medium text-muted-foreground',
            ]"
          >
            Discussions
          </Link>
        </li>
        <li>
          <Link
            :href="route('profile.discussions')"
            :class="[
              page.url.includes('/profil/newsletter')
                ? 'font-semibold text-primary'
                : 'font-medium text-muted-foreground',
            ]"
          >
            Newsletter
          </Link>
        </li>
        <li>
          <Link
            :href="route('profile.discussions')"
            :class="[
              page.url.includes('/profil/statistiques')
                ? 'font-semibold text-primary'
                : 'font-medium text-muted-foreground',
            ]"
          >
            Statistiques
          </Link>
        </li>
        <li>
          <Link
            :href="route('profile.discussions')"
            :class="[
              page.url.includes('/profil/parametres')
                ? 'font-semibold text-primary'
                : 'font-medium text-muted-foreground',
            ]"
          >
            Paramètres
          </Link>
        </li>
      </ul>
    </nav>
  </div>
</template>
