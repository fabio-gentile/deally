<script setup lang="ts">
import AdminTitle from "@/Components/Admin/AdminTitle.vue"
import { Link, router } from "@inertiajs/vue3"
import { Pencil, Trash, Eye, ChevronsUpDown } from "lucide-vue-next"
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

interface Filters {
  page?: number
  per_page?: string
  search?: string
  created_at?: string
  reason?: string
  reportable?: string
}

interface Report {
  id: number
  reason: string
  created_at: string
  user: {
    id: number
    name: string
  }
  reportable: {
    id: number
    type: string
  }
}

const props = defineProps<{
  reports: Report[]
  filters: Filters
  pagination: IPagination
}>()

const filters = ref({ ...props.filters })
const reports = ref(props.reports)

const search = useDebounceFn(() => {
  router.get(route("admin.reports.list"), filters.value, {
    preserveState: true,
    replace: true,
    onSuccess: () => {
      reports.value = props.reports
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

const destroyReport = (id: number) => {
  router.delete(route("admin.reports.destroy", id), {
    preserveState: true,
    replace: true,
    onSuccess: () => {
      reports.value = props.reports
    },
  })
}
</script>

<template>
  <AdminTitle title="Signalements">Signalements</AdminTitle>
  <Breadcrumb
    :breadcrumbs="[
      { label: 'Tableau de bord', route: 'admin.dashboard', active: false },
      {
        label: 'Signalements',
        route: 'admin.reports.list',
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
        <TableHead>
          <Button
            @click="
              filters.reason === 'asc'
                ? ((filters.reason = 'desc'),
                  (filters.created_at = null),
                  (filters.reportable = null))
                : ((filters.reason = 'asc'),
                  (filters.reportable = null),
                  (filters.created_at = null))
            "
            variant="ghost"
            >Raison<ChevronsUpDown class="ml-2 w-4"
          /></Button>
        </TableHead>
        <TableHead>
          <Button
            @click="
              filters.reportable === 'asc'
                ? ((filters.reportable = 'desc'),
                  (filters.reason = null),
                  (filters.created_at = null))
                : ((filters.reportable = 'asc'),
                  (filters.reason = null),
                  (filters.created_at = null))
            "
            variant="ghost"
            >Contenu<ChevronsUpDown class="ml-2 w-4"
          /></Button>
        </TableHead>
        <TableHead>
          <Button
            @click="
              filters.created_at === 'asc'
                ? ((filters.created_at = 'desc'),
                  (filters.reportable = null),
                  (filters.reason = null))
                : ((filters.created_at = 'asc'),
                  (filters.reportable = null),
                  (filters.reason = null))
            "
            variant="ghost"
          >
            Créé le <ChevronsUpDown class="ml-2 w-4"
          /></Button>
        </TableHead>
        <TableHead class="text-right"> Action </TableHead>
      </TableRow>
    </TableHeader>
    <TableBody>
      <TableEmpty v-if="reports.length === 0" />
      <TableRow v-for="report in reports" :key="report.id">
        <TableCell class="truncate">
          {{ report.user.name }}
        </TableCell>
        <TableCell class="truncate">
          {{ report.reason }}
        </TableCell>
        <TableCell class="truncate">
          <span v-if="report.reportable.type === 'App\\Models\\CommentDeal'"
            >Commentaire deal</span
          >
          <span v-if="report.reportable.type === 'App\\Models\\Deal'"
            >Deal</span
          >
          <span
            v-if="report.reportable.type === 'App\\Models\\CommentDiscussion'"
            >Commentaire discussion</span
          >
          <span v-if="report.reportable.type === 'App\\Models\\Discussion'"
            >Discussion</span
          >
        </TableCell>
        <TableCell class="truncate">
          {{ report.created_at }}
        </TableCell>
        <TableCell class="flex justify-end gap-4">
          <Link :href="route('admin.reports.show', report.id)">
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
                  définitivement votre le signalement.
                </AlertDialogDescription>
              </AlertDialogHeader>
              <AlertDialogFooter>
                <AlertDialogCancel>Annuler</AlertDialogCancel>
                <AlertDialogAction
                  @click="destroyReport(report.id)"
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
    :label="'signalements'"
  />
</template>
