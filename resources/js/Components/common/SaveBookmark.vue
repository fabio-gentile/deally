<script setup lang="ts">
import { BookmarkPlus, Flag } from "lucide-vue-next"
import { Link } from "@inertiajs/vue3"

const props = defineProps<{
  url: string
  type: "deal" | "discussion"
}>()
</script>
<template>
  <!--    TODO: add route-->
  <Link href="#" class="flex w-fit items-center gap-1 text-sm">
    <BookmarkPlus class="h-5 w-5 object-contain" />
    <slot />
  </Link>
</template>
