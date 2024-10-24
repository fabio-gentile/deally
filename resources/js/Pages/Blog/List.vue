<script setup lang="ts">
import { Head, Link, router } from "@inertiajs/vue3"
import Wrapper from "@/Components/layout/Wrapper.vue"
import Breadcrumb from "@/Components/Breadcrumb.vue"
import { Blog } from "@/types/model/blog"
import { timeAgo } from "@/lib/time-ago"
import AspectRatio from "@/Components/ui/aspect-ratio/AspectRatio.vue"
import { ImageOff } from "lucide-vue-next"
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
import { Button } from "@/Components/ui/button"

interface Filters {
  page?: number
}

const props = defineProps<{
  posts: Blog[] | null
  firstPost: Blog | null
  filters: Filters
  pagination: IPagination
}>()

const filters = ref({ ...props.filters })
const posts = ref(props.posts)

const search = () => {
  router.get(route("blog.index"), filters.value, {
    preserveState: true,
    replace: true,
    onSuccess: () => {
      posts.value = props.posts
    },
  })
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
  <Head title="Blog" />
  <div class="w-full py-6">
    <Wrapper class="!max-w-[800px]">
      <Breadcrumb
        :breadcrumbs="[
          { label: 'Accueil', route: 'home.index', active: false },
          {
            label: 'Blog',
            route: 'blog.index',
            active: true,
          },
        ]"
      />
      <h1 class="my-6 text-2xl font-semibold md:my-8">
        Les derniers articles publiés.
      </h1>
      <article
        v-if="firstPost && (+filters.page === 1 || !filters.page)"
        class="mb-4 flex w-full flex-col overflow-hidden rounded-lg border bg-white p-4 md:mb-8"
      >
        <div class="flex grow flex-col flex-wrap gap-4 md:flex-row">
          <div
            class="flex w-full shrink-0 gap-2 overflow-hidden md:max-w-[500px]"
          >
            <AspectRatio :ratio="16 / 9">
              <Link
                v-if="firstPost.image"
                class="group hover:underline"
                :href="route('blog.show', firstPost.slug)"
              >
                <img
                  class="h-full w-full rounded-md bg-page object-cover transition-all group-hover:scale-110"
                  :src="'/storage/uploads/blog/' + firstPost.image"
                  :alt="'Image article ' + firstPost.title"
                />
              </Link>
              <Link v-else class="group hover:underline" href="#">
                <ImageOff
                  class="h-full w-full rounded-md bg-page object-cover transition-all group-hover:scale-110"
                />
              </Link>
            </AspectRatio>
          </div>
          <div class="flex grow flex-col justify-center gap-4">
            <Link
              class="hover:underline"
              :href="route('blog.show', firstPost.slug)"
            >
              <h2 class="font-semibold sm:text-2xl">{{ firstPost.title }}</h2>
            </Link>
            <div class="flex items-center gap-4">
              <img
                src="/logo.svg"
                alt="Logo Deally"
                class="h-8 w-8 rounded-full"
              />
              <p class="text-sm text-muted-foreground">
                Il y a
                {{ timeAgo(new Date(firstPost.published_at as string)) }}.
              </p>
            </div>
          </div>
        </div>
      </article>
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <article
          class="flex w-full flex-col overflow-hidden rounded-lg border bg-white p-4"
          v-for="post in posts"
          :key="post.id"
        >
          <div class="flex grow flex-col gap-4">
            <div class="flex w-full shrink-0 gap-2 overflow-hidden">
              <AspectRatio :ratio="16 / 9">
                <Link
                  v-if="post.image"
                  class="group hover:underline"
                  :href="route('blog.show', post.slug)"
                >
                  <img
                    class="h-full w-full rounded-md bg-page object-cover transition-all group-hover:scale-110"
                    :src="'/storage/uploads/blog/' + post.image"
                    :alt="'Image article ' + post.title"
                  />
                </Link>
                <Link
                  v-else
                  class="group hover:underline"
                  :href="route('blog.show', post.slug)"
                >
                  <ImageOff
                    class="h-full w-full rounded-md bg-page object-cover transition-all group-hover:scale-110"
                  />
                </Link>
              </AspectRatio>
            </div>
            <div class="flex grow flex-col justify-end gap-4">
              <Link
                class="hover:underline"
                :href="route('blog.show', post.slug)"
              >
                <h2 class="font-semibold">{{ post.title }}</h2>
              </Link>
              <div class="flex items-center gap-4">
                <img
                  src="/logo.svg"
                  alt="Logo Deally"
                  class="h-8 w-8 rounded-full"
                />
                <p class="text-sm text-muted-foreground">
                  Il y a
                  {{ timeAgo(new Date(post.published_at as string)) }}.
                </p>
              </div>
            </div>
          </div>
        </article>
      </div>
      <Pagination
        class="mx-auto mt-4"
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
    </Wrapper>
  </div>
</template>
