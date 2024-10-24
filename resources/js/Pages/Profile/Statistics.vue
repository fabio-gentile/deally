<script setup lang="ts">
import UserProfile from "@/Pages/Profile/Partials/UserProfile.vue"
import Wrapper from "@/Components/layout/Wrapper.vue"
import { Head } from "@inertiajs/vue3"
import {
  Handshake,
  MessageSquareText,
  PencilLine,
  Flame,
  ChartNoAxesColumn,
} from "lucide-vue-next"
import { User } from "@/types/model/user"

const props = defineProps<{
  user: User
  mostUpvotedDealCount: number
  averageVotesPerDeal: number
  dealsCount: number
  discussionsCount: number
  commentsCount: number
  commentsDealsCount: number
  averageCommentsPerDiscussion: number
  commentsDiscussionsCount: number
  isCurrentUser: boolean
}>()
</script>
<template>
  <Head title="Statistiques" />
  <UserProfile
    :user="user"
    :is-current-user="isCurrentUser"
    :deals-count="dealsCount"
    :discussions-count="discussionsCount"
    :comments-count="commentsCount"
  />
  <Wrapper class="!max-w-[calc(800px+64px)] py-8">
    <div
      class="grid w-full grid-cols-1 gap-8 rounded-lg border bg-white p-4 md:grid-cols-2"
    >
      <div class="flex flex-col gap-4">
        <h2 class="text-xl font-semibold">Deals</h2>
        <div class="grid gap-3">
          <div class="flex items-center gap-2">
            <Handshake />
            <div>
              <span class="mr-1 font-semibold"> {{ dealsCount }} </span>
              <span class="text-muted-foreground"
                >deal{{ dealsCount > 1 ? "s" : "" }} créé{{
                  dealsCount > 1 ? "s" : ""
                }}</span
              >
            </div>
          </div>
          <div class="flex items-center gap-2">
            <Flame />
            <div>
              <span class="mr-1 font-semibold">
                {{ mostUpvotedDealCount }}
              </span>
              <span class="text-muted-foreground"
                >est le record de votes sur un deal</span
              >
            </div>
          </div>
          <div class="flex items-center gap-2">
            <ChartNoAxesColumn />
            <div>
              <span class="mr-1 font-semibold">
                {{ averageVotesPerDeal }}
              </span>
              <span class="text-muted-foreground"
                >vote{{ averageVotesPerDeal > 1 ? "s" : "" }} en moyenne par
                deal</span
              >
            </div>
          </div>
          <div class="flex items-center gap-2">
            <MessageSquareText />
            <div>
              <span class="mr-1 font-semibold">
                {{ commentsDealsCount }}
              </span>
              <span class="text-muted-foreground"
                >réponse{{ commentsDealsCount > 1 ? "s" : "" }} total aux
                deals</span
              >
            </div>
          </div>
        </div>
      </div>
      <div class="flex flex-col gap-4">
        <h2 class="text-xl font-semibold">Discussions</h2>
        <div class="grid gap-3">
          <div class="flex items-center gap-2">
            <PencilLine />
            <div>
              <span class="mr-1 font-semibold"> {{ discussionsCount }} </span>
              <span class="text-muted-foreground"
                >discussion{{ discussionsCount > 1 ? "s" : "" }} créée{{
                  discussionsCount > 1 ? "s" : ""
                }}</span
              >
            </div>
          </div>
          <div class="flex items-center gap-2">
            <ChartNoAxesColumn />
            <div>
              <span class="mr-1 font-semibold">
                {{ averageCommentsPerDiscussion }}
              </span>
              <span class="text-muted-foreground"
                >commentaire{{ averageCommentsPerDiscussion > 1 ? "s" : "" }} en
                moyenne par discussion</span
              >
            </div>
          </div>
          <div class="flex items-center gap-2">
            <MessageSquareText />
            <div>
              <span class="mr-1 font-semibold">
                {{ commentsDiscussionsCount }}
              </span>
              <span class="text-muted-foreground"
                >réponse{{ commentsDiscussionsCount > 1 ? "s" : "" }} total aux
                discussions</span
              >
            </div>
          </div>
        </div>
      </div>
    </div>
  </Wrapper>
</template>
