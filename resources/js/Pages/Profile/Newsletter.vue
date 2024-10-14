<script setup lang="ts">
import UserProfile from "@/Pages/Profile/Partials/UserProfile.vue"
import Wrapper from "@/Components/layout/Wrapper.vue"
import ProfileLayout from "@/Components/layout/ProfileLayout.vue"
import { Head } from "@inertiajs/vue3"

defineOptions({ layout: ProfileLayout })
const props = defineProps<{
  user: {
    name: string
    avatar: string
  }
  dealsCount: number
  discussionsCount: number
  commentsCount: number
}>()
</script>
<template>
  <Head title="Newsletter" />
  <UserProfile
    :user="user"
    :deals-count="dealsCount"
    :discussions-count="discussionsCount"
    :comments-count="commentsCount"
  />
  <Wrapper class="!max-w-[calc(800px+64px)] py-8"> </Wrapper>
</template>
