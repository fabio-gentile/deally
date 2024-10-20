<script setup lang="ts">
import AdminTitle from "@/Components/Admin/AdminTitle.vue"
import { Link, router } from "@inertiajs/vue3"
import { Pencil, Trash } from "lucide-vue-next"
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
import { CategoryDiscussion } from "@/types/model/discussion"

interface Filters {
  page?: number
  per_page?: string
  search?: string
}

const props = defineProps<{
  categories: CategoryDiscussion[]
  filters: Filters
  pagination: IPagination
}>()

const filters = ref({ ...props.filters })
const categories = ref(props.categories)

const search = useDebounceFn(() => {
  router.get(route("admin.categories-discussions.list"), filters.value, {
    preserveState: true,
    replace: true,
    onSuccess: () => {
      categories.value = props.categories
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

const destroyCategory = (id: number) => {
  router.delete(route("admin.categories-discussions.destroy", id), {
    preserveState: true,
    replace: true,
    onSuccess: () => {
      categories.value = props.categories
    },
  })
}
</script>

<template>
  <AdminTitle title="Catégories discussion">Catégories discussions</AdminTitle>
  <Breadcrumb
    :breadcrumbs="[
      { label: 'Tableau de bord', route: 'admin.dashboard', active: false },
      {
        label: 'Catégories discussion',
        route: 'admin.categories-discussions.list',
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
    <Link :href="route('admin.categories-discussions.create')">
      <Button>Créer une catégorie</Button>
    </Link>
  </div>
  <Table class="rounded-lg bg-white">
    <TableHeader>
      <TableRow>
        <TableHead> Nom </TableHead>
        <TableHead> Slug </TableHead>
        <TableHead class="text-right"> Action </TableHead>
      </TableRow>
    </TableHeader>
    <TableBody>
      <TableEmpty v-if="categories.length === 0" />
      <TableRow v-for="category in categories" :key="category.id">
        <TableCell class="truncate">
          {{ category.name }}
        </TableCell>
        <TableCell class="truncate">
          {{ category.slug }}
        </TableCell>
        <TableCell class="flex justify-end gap-4">
          <Link :href="route('admin.categories-discussions.edit', category.id)">
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
                  définitivement votre catégorie ainsi que toutes les
                  discussions et les commentaires associés.
                </AlertDialogDescription>
              </AlertDialogHeader>
              <AlertDialogFooter>
                <AlertDialogCancel>Annuler</AlertDialogCancel>
                <AlertDialogAction
                  @click="destroyCategory(category.id)"
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
    :label="'catégories'"
  />
</template>
