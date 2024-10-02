<script setup lang="ts">
import { Search } from "lucide-vue-next"
import { Input } from "@/Components/ui/input"
</script>

<template>
  <form class="ml-auto w-full flex-initial md:w-auto">
    <div class="relative block">
      <Search class="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" />
      <Input
        type="search"
        placeholder="Rechercher..."
        class="pl-8 lg:w-[300px]"
      />
    </div>
  </form>
</template>
