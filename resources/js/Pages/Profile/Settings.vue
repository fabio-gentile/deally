<script setup lang="ts">
import UserProfile from "@/Pages/Profile/Partials/UserProfile.vue"
import Wrapper from "@/Components/layout/Wrapper.vue"
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/Components/ui/tabs"
import { Head } from "@inertiajs/vue3"
import DeleteAccount from "@/Pages/Profile/Partials/DeleteAccount.vue"
import UpdatePassword from "@/Pages/Profile/Partials/UpdatePassword.vue"
import UpdateProfile from "@/Pages/Profile/Partials/UpdateProfile.vue"
import { User } from "@/types/model/user"

const props = defineProps<{
  user: User
  dealsCount: number
  discussionsCount: number
  commentsCount: number
  isCurrentUser: boolean
}>()
</script>
<template>
  <Head title="Paramètres" />
  <UserProfile
    :user="user"
    :is-current-user="isCurrentUser"
    :deals-count="dealsCount"
    :discussions-count="discussionsCount"
    :comments-count="commentsCount"
  />
  <Wrapper class="!max-w-[calc(800px+64px)] py-8">
    <Tabs
      default-value="profile"
      class="flex flex-col justify-between gap-6 md:flex-row"
      orientation="horizontal"
    >
      <TabsList
        class="mt-2 grid h-fit grid-cols-1 border bg-white font-semibold md:w-[150px]"
      >
        <TabsTrigger
          value="profile"
          class="p-3 data-[state=active]:!bg-primary/10"
        >
          Profil
        </TabsTrigger>
        <TabsTrigger
          value="account"
          class="p-3 data-[state=active]:!bg-primary/10"
        >
          Compte
        </TabsTrigger>
      </TabsList>
      <TabsContent value="profile" class="w-full md:max-w-[600px]">
        <div class="grid gap-6 rounded-lg bg-white p-4">
          <UpdateProfile />
        </div>
      </TabsContent>
      <TabsContent value="account" class="w-full md:max-w-[600px]">
        <div class="grid gap-6 rounded-lg bg-white p-4">
          <UpdatePassword />
          <div class="flex flex-col gap-3">
            <DeleteAccount />
          </div>
        </div>
      </TabsContent>
    </Tabs>
  </Wrapper>
</template>
