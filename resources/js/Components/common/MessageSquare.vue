<script setup lang="ts">
import { Flag, MessageSquareText } from "lucide-vue-next"
import { Link } from "@inertiajs/vue3"

const props = defineProps<{
  url: string
  count: number
}>()
</script>
<template>
  <Link :href="props.url + '#comments'" class="flex gap-1 font-medium">
    <MessageSquareText class="h-5 w-5 object-contain" />
    <!--              TODO: add number of comments-->
    {{ props.count }}
  </Link>
</template>
