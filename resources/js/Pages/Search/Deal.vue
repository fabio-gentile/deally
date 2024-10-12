<script setup lang="ts">
import Wrapper from "@/Components/layout/Wrapper.vue"
import { FilterX, Frown, Filter } from "lucide-vue-next"
import {
  Pagination,
  PaginationEllipsis,
  PaginationFirst,
  PaginationLast,
  PaginationList,
  PaginationListItem,
  PaginationNext,
  PaginationPrev,
} from "@/Components/ui/pagination"
import {
  Breadcrumb,
  BreadcrumbItem,
  BreadcrumbLink,
  BreadcrumbList,
  BreadcrumbSeparator,
} from "@/Components/ui/breadcrumb"
import { Button } from "@/Components/ui/button"
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectLabel,
  SelectTrigger,
  SelectValue,
} from "@/Components/ui/select"
import { Input } from "@/Components/ui/input"
import { Label } from "@/Components/ui/label"
import { RadioGroup, RadioGroupItem } from "@/Components/ui/radio-group"
import { Head, Link, router } from "@inertiajs/vue3"
import { CategoryDeal, Deal } from "@/types/model/deal"
import { Pagination as IPagination } from "@/types/model/miscellaneous"
import CardDeal from "@/Components/Deal/CardDeal.vue"
import { ref, watch } from "vue"
import { useDebounceFn } from "@vueuse/core"
import { cn } from "@/lib/utils"

const props = defineProps<{
  categories: CategoryDeal[]
  deals: Deal[] | null
  filters: Filters
  pagination: IPagination
}>()

interface Filters {
  filter_by?: string
  category?: string
  price_min?: number
  price_max?: number
  votes?: string
  page?: number
  period?: "today" | "week" | "month" | "all"
}

const filters = ref({ ...props.filters })
const deals = ref(props.deals)

const search = useDebounceFn(() => {
  router.get(route("search.deals"), filters.value, {
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

// Function to handle page change
const changePage = (page: number) => {
  filters.value.page = page
  search()
}

const resetFilters = () => {
  filters.value = {}
}

const isFiltersMenuOpen = ref(false)
const toggleFiltersMenu = () => {
  isFiltersMenuOpen.value = !isFiltersMenuOpen.value
}
</script>
<template>
  <Head title="Rechercher un bon plan" />
  <div class="w-full bg-page py-8">
    <Wrapper>
      <Breadcrumb class="mb-6">
        <BreadcrumbList>
          <BreadcrumbItem>
            <BreadcrumbLink>
              <Link :href="route('home.index')"> Accueil </Link>
            </BreadcrumbLink>
          </BreadcrumbItem>
          <BreadcrumbSeparator />
          <BreadcrumbItem>
            <BreadcrumbLink>
              <Link
                class="font-semibold text-foreground"
                :href="route('search.deals')"
              >
                Rechercher un bon plan
              </Link>
            </BreadcrumbLink>
          </BreadcrumbItem>
        </BreadcrumbList>
      </Breadcrumb>

      <div class="flex flex-col gap-6 lg:flex-row">
        <!--            filters-->
        <div>
          <Button @click="toggleFiltersMenu" class="mb-4 lg:hidden">
            <Filter class="mr-2" />
            Filtres
          </Button>
          <aside
            class="sticky h-fit gap-6 rounded-lg border bg-white p-4 lg:top-20 lg:grid xl:w-[350px]"
            :class="{ hidden: !isFiltersMenuOpen, grid: isFiltersMenuOpen }"
          >
            <div class="flex items-center justify-between gap-4">
              <h2 class="text-lg font-bold">Filtres</h2>
              <Button
                variant="ghost"
                @click="resetFilters"
                class="text-muted-foreground"
              >
                <FilterX class="mr-2 h-6 w-6" />
                Réinitialiser
              </Button>
            </div>
            <div>
              <h3 class="mb-4 font-semibold">Trier par</h3>
              <Select v-model="filters.filter_by">
                <SelectTrigger v-model="filters.filter_by" @change="search">
                  <SelectValue placeholder="Nouveauté" />
                </SelectTrigger>
                <SelectContent>
                  <SelectGroup>
                    <SelectLabel class="sr-only">Trier par</SelectLabel>
                    <SelectItem value="newest"> Nouveauté </SelectItem>
                    <SelectItem value="popular"> Popularité </SelectItem>
                  </SelectGroup>
                </SelectContent>
              </Select>
            </div>
            <div>
              <h3 class="mb-4 font-semibold">Période</h3>
              <Select v-model="filters.period">
                <SelectTrigger v-model="filters.period" @change="search">
                  <SelectValue placeholder="Tout" />
                </SelectTrigger>
                <SelectContent>
                  <SelectGroup>
                    <SelectLabel class="sr-only">Période</SelectLabel>
                    <SelectItem value="all"> Tout </SelectItem>
                    <SelectItem value="today">Aujourd'hui</SelectItem>
                    <SelectItem value="week">7 derniers jours</SelectItem>
                    <SelectItem value="month">30 derniers jours</SelectItem>
                  </SelectGroup>
                </SelectContent>
              </Select>
            </div>
            <div>
              <h3 class="mb-4 font-semibold">Prix</h3>
              <div class="flex justify-between">
                <Label for="min_price" class="sr-only">Prix minimum</Label>
                <Input
                  v-model="filters.price_min"
                  id="min_price"
                  type="number"
                  placeholder="Min"
                  class="w-[calc(50%-4px)]"
                  min="0"
                />
                <Label for="max_price" class="sr-only">Prix maximum</Label>
                <Input
                  v-model="filters.price_max"
                  id="max_price"
                  type="number"
                  min="0"
                  placeholder="Max"
                  class="w-[calc(50%-4px)]"
                />
              </div>
            </div>

            <div>
              <h3 class="mb-4 font-semibold">Catégorie</h3>
              <Select v-model="filters.category">
                <SelectTrigger>
                  <SelectValue placeholder="Tout" />
                </SelectTrigger>
                <SelectContent>
                  <SelectGroup>
                    <SelectLabel class="sr-only">Catégorie</SelectLabel>
                    <SelectItem
                      :value="category.name"
                      v-for="category in props.categories"
                      :key="category.id"
                    >
                      {{ category.name }}
                    </SelectItem>
                  </SelectGroup>
                </SelectContent>
              </Select>
            </div>
            <div>
              <h3 class="mb-4 font-semibold">Votes</h3>
              <RadioGroup
                v-model="filters.votes"
                default-value="all"
                class="grid gap-4"
              >
                <div class="flex items-center space-x-2">
                  <RadioGroupItem id="all" value="all" />
                  <Label for="all">Tout</Label>
                </div>
                <div class="flex items-center space-x-2">
                  <RadioGroupItem id="20" value="20" />
                  <Label for="20">20+</Label>
                </div>
                <div class="flex items-center space-x-2">
                  <RadioGroupItem id="50" value="50" />
                  <Label for="50">50+</Label>
                </div>
                <div class="flex items-center space-x-2">
                  <RadioGroupItem id="100" value="100" />
                  <Label for="100">100+</Label>
                </div>
                <div class="flex items-center space-x-2">
                  <RadioGroupItem id="200" value="200" />
                  <Label for="200">200+</Label>
                </div>
                <div class="flex items-center space-x-2">
                  <RadioGroupItem id="500" value="500" />
                  <Label for="500">500+</Label>
                </div>
              </RadioGroup>
            </div>
          </aside>
        </div>

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
                Essayez de changer les filtres.
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
              <!--              <PaginationFirst @click="changePage(1)" />-->
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
              <!--              <PaginationLast @click="changePage(pagination.last_page)" />-->
            </PaginationList>
          </Pagination>
        </div>
      </div>
    </Wrapper>
  </div>
</template>
