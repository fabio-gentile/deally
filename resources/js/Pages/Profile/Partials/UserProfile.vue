<script setup lang="ts">
import { Handshake, MessageSquareText, PencilLine } from "lucide-vue-next"
import { Link, usePage } from "@inertiajs/vue3"
import { User } from "@/types/model/user"

const page = usePage()
const props = defineProps<{
  user: User
  dealsCount: number
  discussionsCount: number
  commentsCount: number
  isCurrentUser: boolean
}>()
</script>

<template>
  <div class="grid w-full gap-6 bg-white px-8 py-9 sm:place-items-center">
    <div class="grid place-items-center gap-4">
      <img
        v-if="user.avatar"
        :src="'/storage/uploads/avatar/' + user.avatar"
        :alt="'Avatar de ' + user.name"
        class="h-24 w-24 rounded-full object-cover"
      />
      <img
        v-else
        :src="`https://ui-avatars.com/api/?size=64&name=${user.name}`"
        :alt="'Avatar de ' + user.name"
        class="h-24 w-24 rounded-full object-cover"
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
    <p v-if="user.biography" class="max-w-[800px] text-muted-foreground">
      « {{ user.biography }} »
    </p>

    <nav class="flex flex-wrap justify-center">
      <ul
        class="grid w-full grid-cols-2 justify-between gap-4 sm:flex sm:grid-cols-6 sm:flex-row sm:flex-wrap sm:justify-normal sm:gap-8"
      >
        <li>
          <Link
            :href="route('profile.favorite', { user })"
            :class="[
              page.url.includes('favoris')
                ? 'font-semibold text-primary'
                : 'font-medium text-muted-foreground',
            ]"
          >
            Favoris
          </Link>
        </li>
        <li>
          <Link
            :href="route('profile.deals', { user })"
            :class="[
              page.url.includes('deals')
                ? 'font-semibold text-primary'
                : 'font-medium text-muted-foreground',
            ]"
          >
            Deals
          </Link>
        </li>
        <li>
          <Link
            :href="route('profile.discussions', { user })"
            :class="[
              page.url.includes('discussions')
                ? 'font-semibold text-primary'
                : 'font-medium text-muted-foreground',
            ]"
          >
            Discussions
          </Link>
        </li>
        <li v-if="isCurrentUser">
          <Link
            :href="route('profile.newsletter', { user })"
            :class="[
              page.url.includes('newsletter')
                ? 'font-semibold text-primary'
                : 'font-medium text-muted-foreground',
            ]"
          >
            Newsletter
          </Link>
        </li>
        <li>
          <Link
            :href="route('profile.statistics', { user })"
            :class="[
              page.url.includes('statistiques')
                ? 'font-semibold text-primary'
                : 'font-medium text-muted-foreground',
            ]"
          >
            Statistiques
          </Link>
        </li>
        <li v-if="isCurrentUser">
          <Link
            :href="route('profile.settings', { user })"
            :class="[
              page.url.includes('parametres')
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
