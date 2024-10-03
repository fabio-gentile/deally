<script setup lang="ts">
import { ArrowDown, ArrowUp } from "lucide-vue-next"
</script>

<template>
  <div class="flex w-fit gap-3 rounded-lg bg-border p-2">
    <ArrowDown class="cursor-pointer" />
    <span>0</span>
    <ArrowUp class="cursor-pointer" />
  </div>
</template>
