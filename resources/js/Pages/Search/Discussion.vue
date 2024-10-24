<script setup lang="ts">
import Wrapper from "@/Components/layout/Wrapper.vue"
import { FilterX, Frown, Filter, Search } from "lucide-vue-next"
import {
  Pagination,
  PaginationEllipsis,
  PaginationList,
  PaginationListItem,
  PaginationNext,
  PaginationPrev,
} from "@/Components/ui/pagination"
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
import { Label } from "@/Components/ui/label"
import { RadioGroup, RadioGroupItem } from "@/Components/ui/radio-group"
import { Head, router } from "@inertiajs/vue3"
import { Pagination as IPagination } from "@/types/model/miscellaneous"
import { ref, watch } from "vue"
import { useDebounceFn } from "@vueuse/core"
import { CategoryDiscussion, Discussion } from "@/types/model/discussion"
import CardDiscussion from "@/Components/Discussion/CardDiscussion.vue"
import { Input } from "@/Components/ui/input"
import Breadcrumb from "@/Components/Breadcrumb.vue"

const props = defineProps<{
  categories: CategoryDiscussion[]
  discussions: Discussion[] | null
  filters: Filters
  pagination: IPagination
}>()

interface Filters {
  search?: string
  filter_by?: string
  category?: string
  comments?: string
  page?: number
  period?: "today" | "week" | "month" | "all"
}

const filters = ref({ ...props.filters })
const discussions = ref(props.discussions)

const search = useDebounceFn(() => {
  router.get(route("search.discussions"), filters.value, {
    preserveState: true,
    replace: true,
    onSuccess: () => {
      discussions.value = props.discussions
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
  <Head title="Rechercher une discussion" />
  <div class="w-full bg-page py-6">
    <Wrapper>
      <Breadcrumb
        :breadcrumbs="[
          { label: 'Accueil', route: 'home.index', active: false },
          {
            label: 'Rechercher une discussion',
            route: 'search.discussions',
            active: true,
          },
        ]"
      />

      <div class="flex flex-col gap-6 lg:flex-row">
        <!--            filters-->
        <div>
          <Button @click="toggleFiltersMenu" class="mt-4 lg:hidden">
            <Filter class="mr-2" />
            Filtres
          </Button>
          <aside
            class="sticky mt-4 h-fit gap-6 rounded-lg border bg-white p-4 lg:top-20 lg:grid xl:w-[350px]"
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
              <Label for="search" class="sr-only">Rechercher</Label>
              <div class="relative block">
                <Search
                  class="absolute left-2.5 top-[11px] h-4 w-4 text-muted-foreground"
                />
                <Input
                  id="search"
                  v-model="filters.search"
                  type="search"
                  placeholder="Rechercher..."
                  class="pl-8"
                />
              </div>
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
              <h3 class="mb-4 font-semibold">Commentaires</h3>
              <RadioGroup
                v-model="filters.comments"
                default-value="all"
                class="grid gap-4"
              >
                <div class="flex items-center space-x-2">
                  <RadioGroupItem id="all" value="all" />
                  <Label for="all">Tout</Label>
                </div>
                <div class="flex items-center space-x-2">
                  <RadioGroupItem id="5" value="5" />
                  <Label for="5">5+</Label>
                </div>
                <div class="flex items-center space-x-2">
                  <RadioGroupItem id="10" value="10" />
                  <Label for="10">10+</Label>
                </div>
                <div class="flex items-center space-x-2">
                  <RadioGroupItem id="20" value="20" />
                  <Label for="20">20+</Label>
                </div>
                <div class="flex items-center space-x-2">
                  <RadioGroupItem id="50" value="50" />
                  <Label for="50">50+</Label>
                </div>
              </RadioGroup>
            </div>
          </aside>
        </div>

        <div class="flex grow flex-col gap-3 lg:mt-4">
          <div
            v-if="discussions.length < 1"
            class="flex flex-col items-center justify-center gap-6"
          >
            <Frown class="h-32 w-32 text-muted-foreground" />
            <div>
              <p class="text-center text-lg font-semibold">
                Aucune discussion trouvée
              </p>
              <p class="text-muted-foreground">
                Essayez de changer les filtres.
              </p>
            </div>
          </div>
          <CardDiscussion
            v-for="discussion in discussions"
            :key="discussion.id"
            :discussion="discussion"
          />
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
