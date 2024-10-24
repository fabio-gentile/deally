<script setup lang="ts">
import { Search } from "lucide-vue-next"
import { Input } from "@/Components/ui/input"
import { ref, watch, onMounted, onBeforeUnmount } from "vue"
import { Link } from "@inertiajs/vue3"
import { Deal } from "@/types/model/deal"
import { User } from "@/types/model/user"
import { useDebounceFn } from "@vueuse/core"
import axios from "axios"
import { Discussion } from "@/types/model/discussion"
import Button from "@/Components/ui/button/Button.vue"

const query = ref<string>("")
const results = ref([])
const users = ref<User[]>([])
const deals = ref<Deal[]>([])
const discussions = ref<Discussion[]>([])
const showResults = ref(false)
const searchBar = ref<HTMLElement | null>(null)
const resultsWidth = ref("")

const resetSearch = (resetQuery: boolean = false) => {
  if (resetQuery) query.value = ""
  results.value = []
  users.value = []
  deals.value = []
  discussions.value = []
  showResults.value = false
}

// Recherche avec debounce
const search = useDebounceFn(async () => {
  if (query.value.length > 2) {
    try {
      const response = await axios.get("/search", {
        params: { q: query.value },
      })
      results.value = response.data.users
        .concat(response.data.deals)
        .concat(response.data.discussions)
      users.value = response.data.users
      deals.value = response.data.deals
      discussions.value = response.data.discussions
      showResults.value = true
    } catch (error) {
      console.error("Erreur lors de la recherche:", error)
    }
  } else {
    resetSearch()
  }
}, 300)

watch(query, search)

const handleClickOutside = (event: MouseEvent) => {
  if (searchBar.value && !searchBar.value.contains(event.target as Node)) {
    showResults.value = false
  }
}

const updateResultsWidth = () => {
  if (window.innerWidth < 1024) {
    resultsWidth.value = `${window.innerWidth * 0.9}px` // 80% of the screen width on mobile
  } else if (searchBar.value) {
    resultsWidth.value = `${searchBar.value.offsetWidth}px`
  }
}

onMounted(() => {
  window.addEventListener("click", handleClickOutside)
  window.addEventListener("resize", updateResultsWidth)
  updateResultsWidth() //
})

onBeforeUnmount(() => {
  window.removeEventListener("click", handleClickOutside)
  window.removeEventListener("resize", updateResultsWidth)
})
</script>

<template>
  <div ref="searchBar" class="relative ml-auto w-full flex-initial lg:w-auto">
    <div class="relative block">
      <Search
        class="absolute left-2.5 top-[11px] h-4 w-4 text-muted-foreground"
      />
      <Input
        v-model="query"
        @focus="showResults = true"
        type="search"
        placeholder="Rechercher..."
        class="pl-8 lg:w-[240px] xl:w-[300px]"
      />
    </div>
    <div
      v-if="showResults && results.length === 0 && query.length > 2"
      :style="{ width: resultsWidth }"
      class="fixed left-2 top-16 grid gap-2 rounded-lg border bg-white px-4 py-2 text-muted-foreground lg:absolute lg:left-0 lg:top-12"
    >
      <p class="font-semibold">Aucun résultat</p>
      <p>Essayez de modifier votre recherche</p>
    </div>
    <div
      v-if="showResults && results.length"
      :style="{ width: resultsWidth }"
      class="fixed left-2 top-16 grid gap-2 rounded-lg border bg-white px-4 py-2 text-muted-foreground lg:absolute lg:left-0 lg:top-12"
    >
      <div
        v-if="deals"
        v-for="(deal, index) in deals"
        :key="deal.id"
        class="truncate"
      >
        <p v-if="index === 0" class="mb-1 font-semibold">Deals</p>
        <Button variant="ghost" as-child>
          <Link
            class="flex !h-auto flex-col !items-start gap-2 !py-1"
            :href="route('deals.show', deal.slug)"
            @click="resetSearch(true)"
            >{{ deal.title }}
            <div class="flex gap-2 text-sm">
              <span class="font-semibold text-primary">
                <span
                  v-if="(!deal.price || deal.price == 0) && deal.original_price"
                  >GRATUIT</span
                >
                <span v-if="deal.price != 0 && deal.price"
                  >{{ deal.price }}€</span
                >
              </span>
              <span
                v-if="deal.original_price"
                class="font-medium text-muted-foreground line-through"
                >{{ deal.original_price }}€</span
              >
            </div>
          </Link>
        </Button>
      </div>
      <div
        v-if="discussions"
        v-for="(discussion, index) in discussions"
        :key="discussion.id"
        class="truncate"
      >
        <p v-if="index === 0" class="mb-1 font-semibold">Discussions</p>
        <Button variant="ghost" as-child>
          <Link
            class="flex !h-auto flex-col !items-start gap-2 !py-1"
            :href="route('discussions.show', discussion.slug)"
            @click="resetSearch(true)"
            >{{ discussion.title }}</Link
          >
        </Button>
      </div>
      <div
        v-if="users"
        v-for="(user, index) in users"
        :key="user.id"
        class="truncate"
      >
        <p v-if="index === 0" class="mb-1 font-semibold">Utilisateurs</p>
        <Button variant="ghost" as-child>
          <!--            TODO: add route redirection-->
          <Link
            class="flex !h-auto flex-col !items-start gap-2 !py-1"
            href="#"
            @click="resetSearch(true)"
            >{{ user.name }}</Link
          >
        </Button>
      </div>
    </div>
  </div>
</template>
