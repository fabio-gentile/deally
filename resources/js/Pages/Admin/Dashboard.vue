<script setup lang="ts">
import AdminTitle from "@/Components/Admin/AdminTitle.vue"
import { Users, Package, Podcast } from "lucide-vue-next"
import { BarChart } from "@/Components/ui/chart-bar"

defineProps<{
  activeDealsCount: number
  lastMonthDealsCount: number
  usersCount: number
  lastMonthUsersCount: number
  discussionsCount: number
  lastMonthDiscussionsCount: number
  dealsChart: any
}>()
</script>

<template>
  <AdminTitle title="Tableau de bord">Tableau de bord</AdminTitle>
  <div>
    <div class="flex flex-wrap gap-6">
      <div
        :class="[lastMonthUsersCount > 0 ? 'items-end' : 'items-center']"
        class="gap flex w-full justify-between gap-8 rounded-lg bg-white p-4 sm:w-fit"
      >
        <div class="flex flex-col justify-between gap-4">
          <Users class="h-8 w-8" />
          <h2 class="font-medium">Utilisateurs</h2>
        </div>
        <div class="flex flex-col justify-between gap-2">
          <p class="text-right text-2xl font-semibold">{{ usersCount }}</p>
          <p
            class="text-right text-sm text-muted-foreground"
            v-if="lastMonthUsersCount > 0"
          >
            +{{ lastMonthUsersCount }}
          </p>
        </div>
      </div>
      <div
        :class="[lastMonthDealsCount > 0 ? 'items-end' : 'items-center']"
        class="gap flex w-full justify-between gap-8 rounded-lg bg-white p-4 sm:w-fit"
      >
        <div class="flex flex-col justify-between gap-4">
          <Package class="h-8 w-8" />
          <h2 class="font-medium">Deals actifs</h2>
        </div>
        <div class="flex flex-col justify-between gap-2">
          <p class="text-right text-2xl font-semibold">
            {{ activeDealsCount }}
          </p>
          <p
            class="text-right text-sm text-muted-foreground"
            v-if="lastMonthDealsCount > 0"
          >
            +{{ lastMonthDealsCount }}
          </p>
        </div>
      </div>
      <div
        :class="[lastMonthDiscussionsCount > 0 ? 'items-end' : 'items-center']"
        class="gap flex w-full justify-between gap-8 rounded-lg bg-white p-4 sm:w-fit"
      >
        <div class="flex flex-col justify-between gap-4">
          <Podcast class="h-8 w-8" />
          <h2 class="font-medium">Discussions</h2>
        </div>
        <div class="flex flex-col justify-between gap-2">
          <p class="text-right text-2xl font-semibold">
            {{ discussionsCount }}
          </p>
          <p
            class="text-right text-sm text-muted-foreground"
            v-if="lastMonthDiscussionsCount > 0"
          >
            +{{ lastMonthDiscussionsCount }}
          </p>
        </div>
      </div>
    </div>
    <BarChart
      class="mt-8 rounded-lg border bg-white p-4"
      :data="dealsChart"
      index="name"
      :categories="['Deals']"
    />
  </div>
</template>
