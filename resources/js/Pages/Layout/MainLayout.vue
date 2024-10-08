<script setup lang="ts">
import NavigationBar from "@/Components/NavigationBar.vue"
import { Toaster } from "@/Components/ui/sonner"
</script>

<template>
  <Toaster />
  <div
    class="mx-auto flex min-h-[100dvh] flex-col justify-center font-sans antialiased"
  >
    <div class="grow bg-page">
      <NavigationBar :user="$page.props.auth.user?.name" />
      <slot />
      <footer></footer>
    </div>
  </div>
</template>
