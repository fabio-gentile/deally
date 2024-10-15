<script setup lang="ts">
import AdminTitle from "@/Components/Admin/AdminTitle.vue"
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
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/Components/ui/select"
import SelectLabel from "@/Components/ui/select/SelectLabel.vue"
import {
  Pagination,
  PaginationList,
  PaginationNext,
  PaginationPrev,
  PaginationFirst,
  PaginationLast,
} from "@/Components/ui/pagination"
import { ref, watch } from "vue"
import { useDebounceFn } from "@vueuse/core"
import { router } from "@inertiajs/vue3"

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
    onSuccess: (page) => {
      users.value = props.users
    },
  })
}, 100)

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
</script>

<template>
  <AdminTitle title="Utilisateurs">Utilisateurs</AdminTitle>
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
          <Eye class="w-4 cursor-pointer" />
          <Pencil class="w-4 cursor-pointer" />
          <Trash class="w-4 cursor-pointer" />
        </TableCell>
      </TableRow>
    </TableBody>
  </Table>
  <!--    pagination-->
  <div
    class="flex w-full flex-wrap items-baseline justify-between gap-4 text-sm"
  >
    <p>
      {{ pagination.current_page * pagination.per_page }} utilisateurs sur
      {{ pagination.total }}
    </p>
    <div class="flex flex-wrap items-baseline gap-4">
      <div class="flex items-baseline gap-4">
        <div class="shrink-0">Par ligne</div>
        <Select v-model="filters.per_page">
          <SelectTrigger @change="search">
            <SelectValue placeholder="10" />
          </SelectTrigger>
          <SelectContent>
            <SelectGroup>
              <SelectLabel class="sr-only">Nombre par ligne</SelectLabel>
              <SelectItem value="10"> 10 </SelectItem>
              <SelectItem value="20"> 20 </SelectItem>
              <SelectItem value="30"> 30 </SelectItem>
              <SelectItem value="50"> 50 </SelectItem>
            </SelectGroup>
          </SelectContent>
        </Select>
      </div>
      <div>
        Page {{ pagination.current_page }} sur {{ pagination.last_page }}
      </div>
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
          <PaginationFirst @click="changePage(1)" />
          <PaginationPrev @click="changePage(pagination.current_page - 1)" />
          <PaginationNext @click="changePage(pagination.current_page + 1)" />
          <PaginationLast @click="changePage(pagination.last_page)" />
        </PaginationList>
      </Pagination>
    </div>
  </div>
</template>
