<script setup lang="ts">
import UserProfile from "@/Pages/Profile/Partials/UserProfile.vue"
import Wrapper from "@/Components/layout/Wrapper.vue"
import ProfileLayout from "@/Components/layout/ProfileLayout.vue"
import { Head, router } from "@inertiajs/vue3"
import { Deal } from "@/types/model/deal"
import { useDebounceFn } from "@vueuse/core"
import { ref, watch } from "vue"
import { Frown } from "lucide-vue-next"
import CardDeal from "@/Components/Deal/CardDeal.vue"
import {
  Pagination,
  PaginationEllipsis,
  PaginationList,
  PaginationListItem,
  PaginationNext,
  PaginationPrev,
} from "@/Components/ui/pagination"
import { Button } from "@/Components/ui/button"
import { Pagination as IPagination } from "@/types/model/miscellaneous"
import { User } from "@/types/model/user"

defineOptions({ layout: ProfileLayout })
const props = defineProps<{
  user: User
  filters: {
    page: number
  }
  pagination: IPagination
  deals: Deal[]
  dealsCount: number
  discussionsCount: number
  commentsCount: number
  isCurrentUser: boolean
}>()

const deals = ref(props.deals)
const filters = ref({ ...props.filters })

const search = useDebounceFn(() => {
  router.get(route("profile.deals"), filters.value, {
    preserveState: true,
    replace: true,
    onSuccess: (page) => {
      deals.value = props.deals
    },
  })
}, 300)

watch(
  filters,
  () => {
    search()
  },
  { deep: true }
)

const changePage = (page: number) => {
  filters.value.page = page
  search()
}
</script>
<template>
  <Head :title="'Deals de ' + user.name" />
  <UserProfile
    :user="user"
    :is-current-user="isCurrentUser"
    :deals-count="dealsCount"
    :discussions-count="discussionsCount"
    :comments-count="commentsCount"
  />
  <Wrapper class="!max-w-[calc(800px+64px)] py-8">
    <div class="flex grow flex-col gap-3">
      <div
        v-if="deals.length < 1"
        class="flex flex-col items-center justify-center gap-6"
      >
        <Frown class="h-32 w-32 text-muted-foreground" />
        <div>
          <p class="text-center text-lg font-semibold">
            {{ user.name }} n'a toujours pas créé de deals.
          </p>
        </div>
      </div>
      <CardDeal v-for="deal in deals" :key="deal.id" :deal="deal" />
      <Pagination
        class="mt-4"
        v-slot="{ page }"
        :total="pagination.total"
        :sibling-count="1"
        :default-page="1"
        show-edges
      >
        <PaginationList
          v-if="pagination.total > 0"
          v-slot="{ items }"
          class="flex items-center justify-center gap-1"
        >
          <PaginationPrev @click="changePage(pagination.current_page - 1)" />

          <template v-for="(item, index) in items">
            <PaginationListItem
              v-if="item.type === 'page'"
              :key="index"
              :value="item.value"
              as-child
            >
              <Button
                class="h-10 w-10 p-0"
                @click="changePage(item.value)"
                :variant="item.value === page ? 'default' : 'outline'"
              >
                {{ item.value }}
              </Button>
            </PaginationListItem>
            <PaginationEllipsis v-else :key="item.type" :index="index" />
          </template>

          <PaginationNext @click="changePage(pagination.current_page + 1)" />
        </PaginationList>
      </Pagination>
    </div>
  </Wrapper>
</template>
