<script setup lang="ts">
import { Link } from "@inertiajs/vue3"
import {
  CalendarClock,
  Clock,
  Eye,
  ImageOff,
  CircleAlert,
} from "lucide-vue-next"
import ShareSocial from "@/Components/common/ShareSocial.vue"
import MessageSquare from "@/Components/common/MessageSquare.vue"
import SaveBookmark from "@/Components/common/SaveBookmark.vue"
import Report from "@/Components/common/Report.vue"
import Button from "@/Components/ui/button/Button.vue"
import { Discussion } from "@/types/model/discussion"
import { timeAgo } from "@/lib/time-ago"
import striptags from "striptags"

const { discussion } = defineProps<{
  discussion: Discussion
}>()

const since = timeAgo(new Date(discussion.created_at)) // string
</script>

<template>
  <article
    class="w-full gap-4 overflow-hidden rounded-lg border bg-white p-4 text-muted-foreground"
  >
    <div class="flex flex-col gap-4 md:flex-row">
      <div class="flex shrink-0 gap-2">
        <img
          v-if="discussion.thumbnail"
          class="h-32 w-32 shrink-0 overflow-hidden rounded-lg bg-page object-contain md:h-52 md:w-52"
          :src="'/storage/' + discussion.path + '/' + discussion.thumbnail"
          alt=""
        />
      </div>
      <div class="flex grow flex-col justify-evenly gap-3">
        <div
          class="hidden flex-col gap-3 md:flex md:flex-row md:items-center md:justify-between"
        ></div>

        <strong>
          <Link
            :href="route('discussions.show', discussion.slug)"
            class="line-clamp-4 font-semibold md:line-clamp-2"
            >{{ discussion.title }}</Link
          >
        </strong>
        <p
          class="line-clamp-3 break-all text-sm text-muted-foreground md:line-clamp-2"
        >
          {{ striptags(discussion.content) }}
        </p>
        <div
          class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between"
        >
          <div class="flex gap-6">
            <ShareSocial
              :url="route('discussions.show', discussion.slug)"
              title="Jordan 1 Mid"
            />
            <MessageSquare
              :count="discussion?.comments_count"
              :url="route('discussions.show', discussion.slug)"
            />
            <Report :id="discussion.id" type="discussion" />
            <SaveBookmark
              type="discussion"
              :is-bookmarked="discussion.user_favorite"
              :id="discussion.id"
            />
            <div class="flex items-center gap-2 text-sm">
              <Clock class="h-5 w-5" />
              {{ since }}
            </div>
          </div>
          <Link
            :href="route('discussions.show', discussion.slug)"
            class="w-full md:w-fit"
          >
            <Button class="w-full">
              <Eye class="mr-2" />
              Voir la discussion
            </Button>
          </Link>
        </div>
      </div>
    </div>
  </article>
</template>
