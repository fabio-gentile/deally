<script setup lang="ts">
import { BookmarkPlus } from "lucide-vue-next"
import { router } from "@inertiajs/vue3"

const props = defineProps<{
  type: "deal" | "discussion"
  id: number
  isBookmarked: boolean
}>()

const saveBookmark = () => {
  router.post(
    route("favorite.store"),
    {
      type: props.type,
      id: props.id,
    },
    {
      preserveState: true,
      preserveScroll: true,
    }
  )
}
</script>
<template>
  <!--    TODO: add route-->
  <div
    @click="saveBookmark"
    class="flex w-fit cursor-pointer items-center gap-1 text-sm"
  >
    <BookmarkPlus
      class="h-5 w-5 object-contain"
      :class="{ '!text-primary': isBookmarked }"
    />
    <slot />
  </div>
</template>
