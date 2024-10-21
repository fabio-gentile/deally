<script setup lang="ts">
import { Discussion } from "@/types/model/discussion"
import { MessageSquareText } from "lucide-vue-next"
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar"
import { Link } from "@inertiajs/vue3"

defineProps<{
  discussions: Discussion[]
}>()
</script>
<template>
  <aside
    class="flex h-fit w-full shrink-0 flex-col gap-4 rounded-lg border bg-white p-3 lg:max-w-[300px]"
  >
    <h3 class="font-semibold">Forum</h3>
    <p class="text-sm text-muted-foreground">Dernières discussions</p>
    <article v-for="discussion in discussions" :key="discussion.id">
      <Link
        :href="route('discussions.show', discussion.slug)"
        class="group flex flex-col gap-2"
      >
        <h4 class="line-clamp-2 text-sm font-medium group-hover:underline">
          {{ discussion.title }}
        </h4>
        <div class="flex items-center justify-between gap-4">
          <div class="flex items-center gap-2">
            <Avatar>
              <AvatarImage
                v-if="discussion.user?.avatar"
                :src="'/storage/uploads/avatar/' + discussion.user?.avatar"
                :alt="'Avatar de ' + discussion.user?.name"
              />
              <AvatarImage
                v-else
                :src="`https://ui-avatars.com/api/?size=64&name=${discussion.user?.name}`"
                :alt="'Avatar de ' + discussion.user?.name"
              />
              <AvatarFallback>{{ discussion.user?.name }}</AvatarFallback>
            </Avatar>
            <p class="text-sm text-muted-foreground">
              {{ discussion.user.name }}
            </p>
          </div>
          <div
            class="flex items-center gap-2 font-medium text-muted-foreground"
          >
            <MessageSquareText class="h-4 w-4" />
            {{ discussion.comments_count }}
          </div>
        </div>
      </Link>
    </article>
  </aside>
</template>
