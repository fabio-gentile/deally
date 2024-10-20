<script setup lang="ts">
import AdminTitle from "@/Components/Admin/AdminTitle.vue"
import { Link, router } from "@inertiajs/vue3"
import { Eye, Trash } from "lucide-vue-next"
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
import Breadcrumb from "@/Components/Admin/Breadcrumb.vue"
import { Discussion } from "@/types/model/discussion"

interface Filters {
  page?: number
  per_page?: string
  search?: string
  created_at: "asc" | "desc" | null
}

interface Comment {
  id: number
  content: string
  created_at: string
  user: { id: number; name: string }
}

const props = defineProps<{
  discussion: Discussion
  comments: Comment[]
  filters: Filters
  pagination: IPagination
}>()

const filters = ref({ ...props.filters })
const comments = ref(props.comments)

const search = useDebounceFn(() => {
  router.get(
    route("admin.discussions.comments.list", props.discussion.id),
    filters.value,
    {
      preserveState: true,
      replace: true,
      onSuccess: () => {
        comments.value = props.comments
      },
    }
  )
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

const destroyComment = (id: number) => {
  router.delete(route("admin.discussions.comments.destroy", id), {
    preserveState: true,
    replace: true,
    onSuccess: () => {
      comments.value = props.comments
    },
  })
}
</script>

<template>
  <AdminTitle :title="'Commentaires de la discussion ' + discussion.title"
    >Commentaires de la discussion {{ discussion.title }}</AdminTitle
  >
  <Breadcrumb
    :breadcrumbs="[
      { label: 'Tableau de bord', route: 'admin.dashboard', active: false },
      { label: 'Discussion', route: 'admin.discussions.list', active: false },
      {
        label: 'Commentaires de la discussions ' + discussion.title,
        route: 'admin.discussions.comments.list',
        params: { id: discussion.id },
        active: true,
      },
    ]"
  />
  <div class="flex flex-wrap justify-between gap-4">
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
        <TableHead> Nom </TableHead>
        <TableHead> Commentaire </TableHead>
        <TableHead class="text-right"> Action </TableHead>
      </TableRow>
    </TableHeader>
    <TableBody>
      <TableEmpty v-if="comments.length === 0" />
      <TableRow v-for="comment in comments" :key="comment.id">
        <TableCell class="truncate">
          {{ comment.user.name }}
        </TableCell>
        <TableCell class="max-w-[200px] truncate">
          {{ comment.content }}
        </TableCell>
        <TableCell class="flex justify-end gap-4">
          <Link :href="route('admin.discussions.comments.show', comment.id)">
            <Eye class="w-4 cursor-pointer" />
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
                  définitivement le commentaire.
                </AlertDialogDescription>
              </AlertDialogHeader>
              <AlertDialogFooter>
                <AlertDialogCancel>Annuler</AlertDialogCancel>
                <AlertDialogAction
                  @click="destroyComment(comment.id)"
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
    :label="'commentaires'"
  />
</template>
