<script setup lang="ts">
import { cn } from "@/lib/utils"
import type { HTMLAttributes } from "vue"

const props = defineProps<{
  class?: HTMLAttributes["class"]
}>()
</script>

<template>
  <div :class="cn('mx-auto w-full max-w-[1240px] px-4', props.class)">
    <slot />
  </div>
</template>
