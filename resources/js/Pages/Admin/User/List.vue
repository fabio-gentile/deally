<script setup lang="ts">
import AdminTitle from "@/Components/Admin/AdminTitle.vue"
import { Link } from "@inertiajs/vue3"
import { ChevronsUpDown, Pencil, Eye, Trash, X, Check } from "lucide-vue-next"
import { Button } from "@/Components/ui/button"
import { Pagination as IPagination } from "@/types/model/miscellaneous"
import { User as IUser } from "@/types/model/user"
import { Input } from "@/Components/ui/input"
import { Label } from "@/Components/ui/label"
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
import { router } from "@inertiajs/vue3"
import TablePagination from "@/Components/Admin/TablePagination.vue"
import Breadcrumb from "@/Components/Admin/Breadcrumb.vue"

interface Filters {
  filter_by?: string
  page?: number
  search?: string
  per_page?: string
  name?: "asc" | "desc" | null
  email?: "asc" | "desc" | null
}

const props = defineProps<{
  users: IUser
  filters: Filters
  pagination: IPagination
}>()

const filters = ref({ ...props.filters })
const users = ref(props.users)

const search = useDebounceFn(() => {
  router.get(route("admin.users.list"), filters.value, {
    preserveState: true,
    replace: true,
    onSuccess: () => {
      users.value = props.users
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
</script>

<template>
  <AdminTitle title="Utilisateurs">Utilisateurs</AdminTitle>
  <Breadcrumb
    :breadcrumbs="[
      { label: 'Tableau de board', route: 'admin.dashboard', active: false },
      { label: 'Utilisateurs', route: 'admin.users.list', active: true },
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
              filters.name === 'asc'
                ? ((filters.name = 'desc'), (filters.email = null))
                : ((filters.name = 'asc'), (filters.email = null))
            "
            variant="ghost"
            >Nom<ChevronsUpDown class="ml-2 w-4"
          /></Button>
        </TableHead>
        <TableHead>
          <Button
            @click="
              filters.email === 'asc'
                ? ((filters.email = 'desc'), (filters.name = null))
                : ((filters.email = 'asc'), (filters.name = null))
            "
            variant="ghost"
            >Email<ChevronsUpDown class="ml-2 w-4"
          /></Button>
        </TableHead>
        <TableHead> Vérifié </TableHead>
        <TableHead class="text-right"> Action </TableHead>
      </TableRow>
    </TableHeader>
    <TableBody>
      <TableEmpty v-if="users.length === 0" />
      <TableRow v-for="user in users" :key="user.id">
        <TableCell>
          {{ user.name }}
        </TableCell>
        <TableCell>{{ user.email }}</TableCell>
        <TableCell>
          <Check v-if="user.email_verified_at" />
          <X v-else />
        </TableCell>
        <TableCell class="flex justify-end gap-4">
          <a
            target="_blank"
            class="hover:underline"
            :href="route('profile.index', user.name)"
          >
            <Eye class="w-4 cursor-pointer" />
          </a>

          <Link :href="route('admin.users.edit', user.id)">
            <Pencil class="w-4 cursor-pointer" />
          </Link>
          <Trash class="w-4 cursor-pointer" />
        </TableCell>
      </TableRow>
    </TableBody>
  </Table>
  <TablePagination
    :pagination="pagination"
    v-model="filters.per_page"
    :onSearch="search"
    :onPageChange="changePage"
    :label="'utilisateurs'"
  />
</template>
