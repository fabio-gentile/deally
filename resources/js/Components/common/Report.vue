<script setup lang="ts">
import { Flag } from "lucide-vue-next"
import { Link } from "@inertiajs/vue3"

const props = defineProps<{
  url: string
  type: "deal" | "discussion | comment"
}>()
</script>
<template>
  <!--    TODO: add route-->
  <Link href="#">
    <Flag class="h-5 w-5 object-contain" />
  </Link>
</template>
