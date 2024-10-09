<script setup lang="ts">
import NavigationBar from "@/Components/layout/NavigationBar.vue"
import { Toaster } from "@/Components/ui/sonner"
import Footer from "@/Components/layout/Footer.vue"
</script>

<template>
  <Toaster />
  <div
    class="mx-auto flex min-h-[100dvh] flex-col justify-center font-sans antialiased"
  >
    <NavigationBar :user="$page.props.auth.user?.name" />
    <div class="flex grow flex-col items-center justify-center bg-page">
      <slot />
    </div>
    <Footer />
  </div>
</template>
