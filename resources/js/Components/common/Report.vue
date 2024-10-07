<script setup lang="ts" xmlns="http://www.w3.org/1999/html">
import { Flag } from "lucide-vue-next"
import { Link } from "@inertiajs/vue3"

const props = defineProps<{
  url: string
  type: "deal" | "discussion | comment"
}>()
</script>
<template>
  <!--    TODO: add route-->
  <Link href="#" class="flex w-fit items-center gap-1 text-sm">
    <Flag class="h-5 w-5 object-contain" />
    <slot />
  </Link>
</template>
