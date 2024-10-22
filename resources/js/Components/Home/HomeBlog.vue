<script setup lang="ts">
import { Blog } from "@/types/model/blog"
import { ImageOff, MessageSquareText } from "lucide-vue-next"
import { timeAgo } from "@/lib/time-ago"
import { Link } from "@inertiajs/vue3"

defineProps<{
  blogs: Blog[]
}>()
</script>
<template>
  <aside
    class="flex h-fit w-full shrink-0 flex-col gap-4 rounded-lg border bg-white p-3 lg:max-w-[300px]"
  >
    <h3 class="font-semibold">Actualités</h3>
    <p class="text-sm text-muted-foreground">
      Les dernières actualités par l’équipe Deally
    </p>
    <article v-for="blog in blogs" :key="blog.id">
      <Link
        :href="route('blog.show', blog.slug)"
        class="group flex flex-row gap-2"
      >
        <div class="h-24 w-24 shrink-0 overflow-hidden rounded-lg bg-page">
          <img
            v-if="blog.image"
            class="h-full w-full bg-page object-cover transition-all group-hover:scale-110"
            :src="'/storage/uploads/blog/' + blog.image"
            :alt="'Image article ' + blog.title"
          /><ImageOff
            v-else
            class="h-full w-full rounded-md bg-page object-cover transition-all group-hover:scale-110"
          />
        </div>

        <div class="flex flex-col justify-evenly gap-2">
          <h4 class="line-clamp-2 text-sm font-medium group-hover:underline">
            {{ blog.title }}
          </h4>
          <div class="flex items-center justify-between gap-4">
            <div
              class="flex items-center gap-2 font-medium text-muted-foreground"
            >
              <MessageSquareText class="h-4 w-4" />
              {{ blog.comments_count }}
            </div>
          </div>
          <p class="text-sm text-muted-foreground">
            Publié il y a {{ timeAgo(new Date(blog.published_at)) }}.
          </p>
        </div>
      </Link>
    </article>
  </aside>
</template>
