<script setup lang="ts">
import { Head, Link, router } from "@inertiajs/vue3"
import Button from "@/Components/ui/button/Button.vue"
import { CategoryDeal } from "@/types/model/deal"
import Wrapper from "@/Components/layout/Wrapper.vue"
import {
  Carousel,
  CarouselContent,
  CarouselItem,
  CarouselNext,
  CarouselPrevious,
} from "@/Components/ui/carousel"
import CardDeal from "@/Components/Deal/CardDeal.vue"
import { Deal } from "@/types/model/deal"
import HomeDiscussion from "@/Components/Home/HomeDiscussion.vue"
import { Discussion } from "@/types/model/discussion"
import { Blog } from "@/types/model/blog"
import HomeBlog from "@/Components/Home/HomeBlog.vue"
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectLabel,
  SelectTrigger,
  SelectValue,
} from "@/Components/ui/select"
import { Pagination as IPagination } from "@/types/model/miscellaneous"
import { ref, watch } from "vue"
import {
  Pagination,
  PaginationEllipsis,
  PaginationList,
  PaginationListItem,
  PaginationNext,
  PaginationPrev,
} from "@/Components/ui/pagination"
import { Flag, Frown } from "lucide-vue-next"
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from "@/Components/ui/dialog"

interface Filters {
  page: number
  period?: "today" | "week" | "month"
}

const props = defineProps<{
  categories: CategoryDeal[]
  deals: Deal[]
  discussions: Discussion[]
  blogs: Blog[]
  pagination: IPagination
  filters: Filters
}>()

const filters = ref({ ...props.filters })
const deals = ref(props.deals)

const search = () => {
  router.get(
    route().current() === "home.index"
      ? route("home.index")
      : route("home.new"),
    filters.value,
    {
      preserveState: true,
      replace: true,
      onSuccess: () => {
        deals.value = props.deals
      },
    }
  )
}

watch(
  filters,
  () => {
    search()
  },
  { deep: true }
)

// Function to handle page change
const changePage = (page: number) => {
  filters.value.page = page
  search()
}
</script>

<template>
  <div class="w-full pb-6">
    <Head title="Accueil" />
    <div class="bg-primary py-4 dark:bg-primary-foreground">
      <Wrapper class="max-w-2xl lg:max-w-4xl">
        <Carousel
          class="relative flex w-full max-w-full"
          :opts="{
            align: 'start',
            dragFree: true,
          }"
          v-slot="{ canScrollNext, canScrollPrev }"
        >
          <div class="overflow-hidden">
            <CarouselContent class="mx-auto gap-3">
              <CarouselItem
                v-for="category in categories"
                :key="category.slug"
                class="flex basis-auto !pl-0"
              >
                <Link
                  :href="route('search.deals') + '?category=' + category.name"
                >
                  <Button variant="outline" class="font-semibold lg:ml-3">
                    {{ category.name }}
                  </Button>
                </Link>
              </CarouselItem>
            </CarouselContent>
          </div>
          <CarouselPrevious
            v-if="canScrollPrev"
            class="absolute -left-10 hidden md:flex"
          />
          <CarouselNext
            v-if="canScrollNext"
            class="- absolute -right-10 hidden md:flex"
          />
        </Carousel>
      </Wrapper>
    </div>
    <div class="border-b bg-background py-3">
      <Wrapper>
        <div class="flex gap-6">
          <Link
            :href="route('home.index')"
            :class="[
              route().current() === 'home.index'
                ? 'font-semibold text-primary'
                : 'font-medium text-muted-foreground',
            ]"
            >Populaire</Link
          >
          <Link
            :href="route('home.new')"
            :class="[
              route().current() === 'home.new'
                ? 'font-semibold text-primary'
                : 'font-medium text-muted-foreground',
            ]"
            >Nouveauté</Link
          >
          <Link
            v-if="$page.props.auth.user?.id"
            :href="route('home.for-you')"
            :class="[
              route().current() === 'home.for-you'
                ? 'font-semibold text-primary'
                : 'font-medium text-muted-foreground',
            ]"
            >Pour vous</Link
          >
          <Dialog v-else>
            <DialogTrigger as-child>
              <button
                class="cursor-pointer"
                :class="[
                  route().current() === 'home.for-you'
                    ? 'font-semibold text-primary'
                    : 'font-medium text-muted-foreground',
                ]"
              >
                Pour vous
              </button>
            </DialogTrigger>
            <DialogContent class="sm:max-w-[425px]">
              <DialogHeader>
                <DialogTitle>Se connecter</DialogTitle>
                <DialogDescription>
                  Vous devez être connecté pour accéder à cette fonctionnalité.
                </DialogDescription>
              </DialogHeader>
              <DialogFooter>
                <Link :href="route('login')">
                  <Button type="submit">Se connecter</Button>
                </Link>
              </DialogFooter>
            </DialogContent>
          </Dialog>
        </div>
      </Wrapper>
    </div>

    <main class="bg-page">
      <Wrapper class="pt-6">
        <div class="mb-4 flex flex-row items-center gap-2">
          <p class="font-medium text-muted-foreground">Deals créés les</p>
          <Select
            v-model="filters.period"
            @update:model-value="filters.page = 1"
          >
            <SelectTrigger class="!w-fit gap-2">
              <SelectValue placeholder="30 derniers jours" />
            </SelectTrigger>
            <SelectContent>
              <SelectGroup>
                <SelectLabel class="sr-only"
                  >Deals les plus populaires</SelectLabel
                >
                <SelectItem value="day"> dernières 24 heures </SelectItem>
                <SelectItem value="week"> 7 derniers jours </SelectItem>
                <SelectItem value="month"> 30 derniers jours </SelectItem>
              </SelectGroup>
            </SelectContent>
          </Select>
        </div>
        <div class="flex flex-col gap-3 lg:flex-row lg:gap-6">
          <div class="flex grow flex-col gap-3">
            <div
              v-if="deals.length < 1"
              class="flex flex-col items-center justify-center gap-6"
            >
              <Frown class="h-32 w-32 text-muted-foreground" />
              <div>
                <p class="text-center text-lg font-semibold">
                  Aucun bon plan trouvé
                </p>
                <p class="text-muted-foreground">
                  Essayez de changer la période.
                </p>
              </div>
            </div>
            <CardDeal v-for="deal in deals" :key="deal.id" :deal="deal" />
            <Pagination
              class="my-4"
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
                <PaginationPrev
                  @click="changePage(pagination.current_page - 1)"
                />

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

                <PaginationNext
                  @click="changePage(pagination.current_page + 1)"
                />
              </PaginationList>
            </Pagination>
          </div>
          <div
            class="flex w-full shrink-0 flex-col justify-between gap-4 md:flex-row lg:w-fit lg:flex-col lg:justify-normal"
          >
            <HomeDiscussion
              class="md:w-[calc(50%-12px)] lg:w-full"
              :discussions="discussions"
            />
            <HomeBlog class="md:w-[calc(50%-12px)] lg:w-full" :blogs="blogs" />
          </div>
        </div>
      </Wrapper>
    </main>
  </div>
</template>
