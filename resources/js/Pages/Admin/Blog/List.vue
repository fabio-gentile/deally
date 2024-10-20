<script setup lang="ts">
import AdminTitle from "@/Components/Admin/AdminTitle.vue"
import { Link, router } from "@inertiajs/vue3"
import { ChevronsUpDown, Pencil, Eye, Trash, X, Check } from "lucide-vue-next"
import { Button } from "@/Components/ui/button"
import { Pagination as IPagination } from "@/types/model/miscellaneous"
import { Input } from "@/Components/ui/input"
import { Label } from "@/Components/ui/label"
import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
  AlertDialogTrigger,
} from "@/Components/ui/alert-dialog"
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
  TableEmpty,
} from "@/Components/ui/table"
import { ref, watch } from "vue"
import TablePagination from "@/Components/Admin/TablePagination.vue"
import Breadcrumb from "@/Components/Admin/Breadcrumb.vue"
import { useDateFormat, useDebounceFn } from "@vueuse/core"
import { Blog } from "@/types/model/blog"

interface Filters {
  filter_by?: string
  page?: number
  search?: string
  per_page?: string
  comments?: "asc" | "desc" | null
  created_at: "asc" | "desc" | null
}

interface Discussion {
  id: number
  title: string
  comments_count: number
  user: { id: number; name: string }
  created_at: string
  slug: string
}

const props = defineProps<{
  blogs: Blog[]
  filters: Filters
  pagination: IPagination
}>()

const filters = ref({ ...props.filters })
const blogs = ref(props.blogs)

const search = useDebounceFn(() => {
  router.get(route("admin.blog.list"), filters.value, {
    preserveState: true,
    replace: true,
    onSuccess: () => {
      blogs.value = props.blogs
    },
  })
}, 100)

// Watch for changes in filters
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

const resetFilters = () => {
  filters.value = {}
}

const destroyBlog = (id: number) => {
  router.delete(route("admin.blog.destroy", id), {
    preserveState: true,
    replace: true,
    onSuccess: () => {
      blogs.value = props.blogs
    },
  })
}
</script>

<template>
  <AdminTitle title="Articles">Articles</AdminTitle>
  <Breadcrumb
    :breadcrumbs="[
      { label: 'Tableau de bord', route: 'admin.dashboard', active: false },
      { label: 'Blog', route: 'admin.blog.list', active: true },
    ]"
  />
  <div>
    <div class="flex flex-wrap gap-4">
      <Label for="search" class="sr-only">Rechercher</Label>
      <Input
        v-model="filters.search"
        id="search"
        type="search"
        placeholder="Rechercher..."
        class="w-[300px]"
      />
      <Button @click="resetFilters" variant="outline"
        >Réinitialiser les filtres</Button
      >
    </div>
  </div>
  <Table class="rounded-lg bg-white">
    <TableHeader>
      <TableRow>
        <TableHead> Titre </TableHead>
        <TableHead> Publié </TableHead>
        <TableHead>
          <Button
            @click="
              filters.comments === 'asc'
                ? ((filters.comments = 'desc'), (filters.created_at = null))
                : ((filters.comments = 'asc'), (filters.created_at = null))
            "
            variant="ghost"
            >Commentaires<ChevronsUpDown class="ml-2 w-4"
          /></Button>
        </TableHead>
        <TableHead>
          <Button
            @click="
              filters.created_at === 'asc'
                ? ((filters.created_at = 'desc'), (filters.comments = null))
                : ((filters.created_at = 'asc'), (filters.comments = null))
            "
            variant="ghost"
            >Créé<ChevronsUpDown class="ml-2 w-4"
          /></Button>
        </TableHead>
        <TableHead class="text-right"> Action </TableHead>
      </TableRow>
    </TableHeader>
    <TableBody>
      <TableEmpty v-if="blogs.length === 0" />
      <TableRow v-for="blog in blogs" :key="blog.id">
        <TableCell class="truncate">
          {{ blog.title }}
        </TableCell>
        <TableCell class="truncate">
          <Check v-if="blog.is_published" />
          <X v-else />
        </TableCell>
        <TableCell
          ><Link
            class="ml-16 hover:underline"
            :href="route('admin.blog.comments.list', blog.id)"
          >
            {{ blog.comments_count }}
          </Link></TableCell
        >
        <TableCell>
          {{
            useDateFormat(blog.created_at, "DD-MM-YY", {
              locales: "fr-FR",
            })
          }}
        </TableCell>
        <TableCell class="flex justify-end gap-4">
          <!--            TODO: add redirection-->
          <a target="_blank" href="#">
            <Eye class="w-4 cursor-pointer" />
          </a>

          <Link :href="route('admin.blog.edit', blog.id)">
            <Pencil class="w-4 cursor-pointer" />
          </Link>
          <AlertDialog>
            <AlertDialogTrigger as-child>
              <Trash class="w-4 cursor-pointer" />
            </AlertDialogTrigger>
            <AlertDialogContent>
              <AlertDialogHeader>
                <AlertDialogTitle>Êtes-vous vraiment sûr ?</AlertDialogTitle>
                <AlertDialogDescription>
                  Cette action ne peut pas être annulée. Cela supprimera
                  définitivement votre blog et les commentaires associés.
                </AlertDialogDescription>
              </AlertDialogHeader>
              <AlertDialogFooter>
                <AlertDialogCancel>Annuler</AlertDialogCancel>
                <AlertDialogAction
                  @click="destroyBlog(blog.id)"
                  class="!bg-destructive"
                >
                  Supprimer
                </AlertDialogAction>
              </AlertDialogFooter>
            </AlertDialogContent>
          </AlertDialog>
        </TableCell>
      </TableRow>
    </TableBody>
  </Table>
  <TablePagination
    :pagination="pagination"
    v-model="filters.per_page"
    :onSearch="search"
    :onPageChange="changePage"
    :label="'blogs'"
  />
</template>
