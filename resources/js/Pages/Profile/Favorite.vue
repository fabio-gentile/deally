<script setup lang="ts">
import UserProfile from "@/Pages/Profile/Partials/UserProfile.vue"
import CardDeal from "@/Components/Deal/CardDeal.vue"
import { Deal } from "@/types/model/deal"
import { Discussion } from "@/types/model/discussion"
import CardDiscussion from "@/Components/Discussion/CardDiscussion.vue"
import Wrapper from "@/Components/layout/Wrapper.vue"
import { Head } from "@inertiajs/vue3"
import { User } from "@/types/model/user"

interface Favorite {
  type: "deal" | "discussion"
  item: Deal | Discussion
}

const props = defineProps<{
  latestFavorites: Favorite[]
  user: User
  dealsCount: number
  discussionsCount: number
  commentsCount: number
  isCurrentUser: boolean
}>()
</script>
<template>
  <Head title="Favoris" />
  <UserProfile
    :user="user"
    :deals-count="dealsCount"
    :discussions-count="discussionsCount"
    :comments-count="commentsCount"
    :is-current-user="isCurrentUser"
  />
  <Wrapper class="!max-w-[calc(800px+64px)] py-8">
    <div>
      <div v-if="props.latestFavorites.length === 0">
        <p class="text-center text-muted-foreground">
          Aucun favori pour le moment
        </p>
      </div>
      <div v-else class="flex flex-col gap-3 py-8">
        <div v-for="favorite in props.latestFavorites" :key="favorite.item.id">
          <CardDeal v-if="favorite.type === 'deal'" :deal="favorite.item" />
          <CardDiscussion v-else :discussion="favorite.item" />
        </div>
      </div>
    </div>
  </Wrapper>
</template>
