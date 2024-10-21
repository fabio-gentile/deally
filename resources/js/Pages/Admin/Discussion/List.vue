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
import { useDebounceFn } from "@vueuse/core"
import TablePagination from "@/Components/Admin/TablePagination.vue"
import Breadcrumb from "@/Components/Breadcrumb.vue"
import { useDateFormat } from "@vueuse/core"

interface Filters {
  filter_by?: string
  page?: number
  search?: string
  per_page?: string
  title?: "asc" | "desc" | null
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
  discussions: Discussion[]
  filters: Filters
  pagination: IPagination
}>()

const filters = ref({ ...props.filters })
const discussions = ref(props.discussions)

const search = useDebounceFn(() => {
  router.get(route("admin.discussions.list"), filters.value, {
    preserveState: true,
    replace: true,
    onSuccess: () => {
      discussions.value = props.discussions
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

const destroyDiscussion = (id: number) => {
  router.delete(route("admin.discussions.destroy", id), {
    preserveState: true,
    replace: true,
    onSuccess: () => {
      discussions.value = props.discussions
    },
  })
}
</script>

<template>
  <AdminTitle title="Discussions">Discussions</AdminTitle>
  <Breadcrumb
    :breadcrumbs="[
      { label: 'Tableau de bord', route: 'admin.dashboard', active: false },
      { label: 'Discussion', route: 'admin.discussions.list', active: true },
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
        <TableHead>
          <Button
            @click="
              filters.title === 'asc'
                ? ((filters.title = 'desc'),
                  (filters.comments = null),
                  (filters.created_at = null))
                : ((filters.title = 'asc'),
                  (filters.comments = null),
                  (filters.created_at = null))
            "
            variant="ghost"
            >Titre<ChevronsUpDown class="ml-2 w-4"
          /></Button>
        </TableHead>
        <TableHead> Par </TableHead>
        <TableHead>
          <Button
            @click="
              filters.comments === 'asc'
                ? ((filters.comments = 'desc'),
                  (filters.title = null),
                  (filters.created_at = null))
                : ((filters.comments = 'asc'),
                  (filters.title = null),
                  (filters.created_at = null))
            "
            variant="ghost"
            >Commentaires<ChevronsUpDown class="ml-2 w-4"
          /></Button>
        </TableHead>
        <TableHead>
          <Button
            @click="
              filters.created_at === 'asc'
                ? ((filters.created_at = 'desc'),
                  (filters.title = null),
                  (filters.comments = null))
                : ((filters.created_at = 'asc'),
                  (filters.title = null),
                  (filters.comments = null))
            "
            variant="ghost"
            >Créé<ChevronsUpDown class="ml-2 w-4"
          /></Button>
        </TableHead>
        <TableHead class="text-right"> Action </TableHead>
      </TableRow>
    </TableHeader>
    <TableBody>
      <TableEmpty v-if="discussions.length === 0" />
      <TableRow v-for="discussion in discussions" :key="discussion.id">
        <TableCell class="truncate">
          {{ discussion.title }}
        </TableCell>
        <TableCell class="truncate">
          <a
            class="hover:underline"
            target="_blank"
            :href="route('admin.users.edit', discussion.user.id)"
          >
            {{ discussion.user.name }}
          </a>
        </TableCell>
        <TableCell class="text-center"
          ><Link
            class="hover:underline"
            :href="route('admin.discussions.comments.list', discussion.id)"
          >
            {{ discussion.comments_count }}
          </Link></TableCell
        >
        <TableCell>
          {{
            useDateFormat(discussion.created_at, "DD-MM-YY", {
              locales: "fr-FR",
            })
          }}
        </TableCell>
        <TableCell class="flex justify-end gap-4">
          <a target="_blank" :href="route('discussions.show', discussion.slug)">
            <Eye class="w-4 cursor-pointer" />
          </a>

          <Link :href="route('admin.discussions.edit', discussion.id)">
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
                  définitivement votre discussion et les commentaires associés.
                </AlertDialogDescription>
              </AlertDialogHeader>
              <AlertDialogFooter>
                <AlertDialogCancel>Annuler</AlertDialogCancel>
                <AlertDialogAction
                  @click="destroyDiscussion(discussion.id)"
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
    :label="'discussions'"
  />
</template>
