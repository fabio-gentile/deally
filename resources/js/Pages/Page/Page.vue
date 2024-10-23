<script setup lang="ts">
import { Page } from "@/types/model/page"
import { Head } from "@inertiajs/vue3"
import Wrapper from "@/Components/layout/Wrapper.vue"

defineProps<{
  page: Page
  title: string
}>()
</script>

<template>
  <Head :title="title" />
  <div class="w-full py-6">
    <Wrapper class="!max-w-[800px]">
      <h1 class="mb-6 text-2xl font-semibold md:mb-8">{{ title }}</h1>
      <div
        class="tip-tap break-all text-sm text-muted-foreground lg:text-base"
        v-html="page.content"
      ></div>
    </Wrapper>
  </div>
</template>
