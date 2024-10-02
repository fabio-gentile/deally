<script setup lang="ts">
import NavigationBar from "@/Components/NavigationBar.vue"
import { User } from "@/types"
defineProps<{
  user: User
}>()
</script>

<template>
  <div
    class="mx-auto flex min-h-[100dvh] flex-col justify-center font-sans antialiased"
  >
    <div class="grow">
      <NavigationBar :user="user" />
      <slot />
      <footer></footer>
    </div>
  </div>
</template>
