<script setup lang="ts">
import NavigationBar from "@/Components/NavigationBar.vue"
import { User } from "@/types"
import { Toaster } from "@/Components/ui/sonner"

defineProps<{
  user: User
}>()
</script>

<template>
  <Toaster />
  <div
    class="mx-auto flex min-h-[100dvh] flex-col justify-center font-sans antialiased"
  >
    <div class="grow bg-page">
      <NavigationBar :user="user" />
      <slot />
      <footer></footer>
    </div>
  </div>
</template>
