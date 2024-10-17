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
import Breadcrumb from "@/Components/Admin/Breadcrumb.vue"
import { useDateFormat } from "@vueuse/core"

interface Filters {
  page?: number
  search?: string
  per_page?: string
  title?: "asc" | "desc" | null
  votes?: "asc" | "desc" | null
  created_at: "asc" | "desc" | null
  expiration_date: "asc" | "desc" | null
}

interface Deal {
  id: number
  title: string
  comments_count: number
  expiration_date: string
  price: number
  created_at: string
  slug: string
  category: string
  votes: string
}

const props = defineProps<{
  deals: Deal[]
  filters: Filters
  pagination: IPagination
}>()

const filters = ref({ ...props.filters })
const deals = ref(props.deals)

const search = useDebounceFn(() => {
  router.get(route("admin.deals.list"), filters.value, {
    preserveState: true,
    replace: true,
    onSuccess: () => {
      deals.value = props.deals
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

const destroyDeal = (id: number) => {
  router.delete(route("admin.deals.destroy", id), {
    preserveState: true,
    replace: true,
    onSuccess: () => {
      deals.value = props.deals
    },
  })
}
</script>

<template>
  <AdminTitle title="Deals">Deals</AdminTitle>
  <Breadcrumb
    :breadcrumbs="[
      { label: 'Tableau de board', route: 'admin.dashboard', active: false },
      { label: 'Deals', route: 'admin.deals.list', active: true },
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
                  (filters.votes = null),
                  (filters.expiration_date = null),
                  (filters.created_at = null))
                : ((filters.title = 'asc'),
                  (filters.votes = null),
                  (filters.expiration_date = null),
                  (filters.created_at = null))
            "
            variant="ghost"
            >Titre<ChevronsUpDown class="ml-2 w-4"
          /></Button>
        </TableHead>
        <TableHead> Prix </TableHead>
        <TableHead>
          <Button
            @click="
              filters.votes === 'asc'
                ? ((filters.votes = 'desc'),
                  (filters.title = null),
                  (filters.expiration_date = null),
                  (filters.created_at = null))
                : ((filters.votes = 'asc'),
                  (filters.title = null),
                  (filters.expiration_date = null),
                  (filters.created_at = null))
            "
            variant="ghost"
            >Votes<ChevronsUpDown class="ml-2 w-4"
          /></Button>
        </TableHead>
        <TableHead>
          <Button
            @click="
              filters.created_at === 'asc'
                ? ((filters.created_at = 'desc'),
                  (filters.title = null),
                  (filters.expiration_date = null),
                  (filters.votes = null))
                : ((filters.created_at = 'asc'),
                  (filters.title = null),
                  (filters.expiration_date = null),
                  (filters.votes = null))
            "
            variant="ghost"
            >Créé<ChevronsUpDown class="ml-2 w-4"
          /></Button>
        </TableHead>
        <TableHead>
          <Button
            @click="
              filters.expiration_date === 'asc'
                ? ((filters.expiration_date = 'desc'),
                  (filters.title = null),
                  (filters.created_at = null),
                  (filters.votes = null))
                : ((filters.expiration_date = 'asc'),
                  (filters.title = null),
                  (filters.created_at = null),
                  (filters.votes = null))
            "
            variant="ghost"
            >Date de fin<ChevronsUpDown class="ml-2 w-4"
          /></Button>
        </TableHead>
        <TableHead> Commentaires </TableHead>
        <TableHead class="text-right"> Action </TableHead>
      </TableRow>
    </TableHeader>
    <TableBody>
      <TableEmpty v-if="deals.length === 0" />
      <TableRow v-for="deal in deals" :key="deal.id">
        <TableCell class="max-w-[200px] truncate">
          {{ deal.title }}
        </TableCell>
        <TableCell class="truncate">
          {{ deal.price > 0 ? deal.price + "€" : "Gratuit" }}
        </TableCell>
        <TableCell class="text-center"
          ><Link
            class="hover:underline"
            :href="route('admin.deal.comments.list', deal.id)"
          >
            {{ deal.votes }}
          </Link></TableCell
        >
        <TableCell>
          {{
            useDateFormat(deal.created_at, "DD-MM-YY", {
              locales: "fr-FR",
            })
          }}
        </TableCell>
        <TableCell>
          {{
            useDateFormat(deal.expiration_date, "DD-MM-YY", {
              locales: "fr-FR",
            })
          }}
        </TableCell>
        <TableCell class="text-center"
          ><Link
            class="hover:underline"
            :href="route('admin.deal.comments.list', deal.id)"
          >
            {{ deal.comments_count }}
          </Link></TableCell
        >
        <TableCell class="flex justify-end gap-4">
          <a target="_blank" :href="route('deals.show', deal.slug)">
            <Eye class="w-4 cursor-pointer" />
          </a>

          <Link :href="route('admin.deals.edit', deal.id)">
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
                  définitivement votre deal et les commentaires associés.
                </AlertDialogDescription>
              </AlertDialogHeader>
              <AlertDialogFooter>
                <AlertDialogCancel>Annuler</AlertDialogCancel>
                <AlertDialogAction
                  @click="destroyDeal(deal.id)"
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
    :label="'deals'"
  />
</template>
