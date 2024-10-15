<script setup>
import { Head } from "@inertiajs/vue3"

defineProps({ title: String })
</script>

<template>
  <Head :title="title ? `${title}` : 'Deally'" />
  <div class="flex items-center">
    <h1 class="text-lg font-semibold md:text-2xl">
      {{ title }}
    </h1>
  </div>
</template>
