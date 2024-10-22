<script setup lang="ts">
import { Handshake, MessageSquareText, PencilLine } from "lucide-vue-next"
import { Link, usePage, router } from "@inertiajs/vue3"
import { User } from "@/types/model/user"
import { X } from "lucide-vue-next"
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
  DialogClose,
} from "@/Components/ui/dialog"
import { Button } from "@/Components/ui/button"

const page = usePage()
const props = defineProps<{
  user: User
  dealsCount: number
  discussionsCount: number
  commentsCount: number
  isCurrentUser: boolean
}>()

const deleteAvatar = () => {
  router.delete(route("profile.avatar.delete"), {
    preserveScroll: true,
  })
}
</script>

<template>
  <div class="grid w-full gap-6 bg-white px-8 py-9 sm:place-items-center">
    <div class="grid place-items-center gap-4">
      <div class="relative">
        <img
          v-if="user.avatar"
          :src="'/storage/uploads/avatar/' + user.avatar"
          :alt="'Avatar de ' + user.name"
          class="h-24 w-24 rounded-full object-cover"
        />
        <Dialog v-if="user.avatar">
          <DialogTrigger
            class="absolute -right-4 top-0 z-10 rounded-full bg-destructive p-1"
          >
            <X />
          </DialogTrigger>
          <DialogContent>
            <DialogHeader>
              <DialogTitle>Supprimer votre avatar</DialogTitle>
              <DialogDescription class="my-2">
                Êtes-vous sûr de vouloir supprimer votre avatar ?
              </DialogDescription>
            </DialogHeader>

            <DialogFooter>
              <DialogClose as-child>
                <Button type="button" variant="secondary"> Annuler </Button>
              </DialogClose>
              <DialogClose as-child>
                <Button variant="destructive" @click="deleteAvatar">
                  Supprimer
                </Button>
              </DialogClose>
            </DialogFooter>
          </DialogContent>
        </Dialog>
        <img
          v-else
          :src="`https://ui-avatars.com/api/?size=64&name=${user.name}`"
          :alt="'Avatar de ' + user.name"
          class="h-24 w-24 rounded-full object-cover"
        />
      </div>

      <h2 class="text-2xl font-semibold text-foreground">{{ user.name }}</h2>
    </div>
    <div
      class="flex flex-col flex-wrap gap-2 font-medium text-muted-foreground sm:flex-row md:gap-4 lg:gap-8"
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
            :href="route('profile.favorite', { user: user.name })"
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
            :href="route('profile.deals', { user: user.name })"
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
            :href="route('profile.discussions', { user: user.name })"
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
            :href="route('profile.notifications', { user: user.name })"
            :class="[
              page.url.includes('notifications')
                ? 'font-semibold text-primary'
                : 'font-medium text-muted-foreground',
            ]"
          >
            Notifications
          </Link>
        </li>
        <li>
          <Link
            :href="route('profile.statistics', { user: user.name })"
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
            :href="route('profile.settings', { user: user.name })"
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
