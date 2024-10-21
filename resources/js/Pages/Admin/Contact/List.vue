<script setup lang="ts">
import AdminTitle from "@/Components/Admin/AdminTitle.vue"
import { Link, router } from "@inertiajs/vue3"
import { ChevronsUpDown, Eye, Trash, MailPlus } from "lucide-vue-next"
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
import { useDateFormat, useDebounceFn } from "@vueuse/core"
import TablePagination from "@/Components/Admin/TablePagination.vue"
import Breadcrumb from "@/Components/Admin/Breadcrumb.vue"
import Contact from "@/Pages/Contact.vue"

interface Filters {
  page?: number
  per_page?: string
  search?: string
  created_at: "asc" | "desc" | null
  subject: "asc" | "desc" | null
}

const props = defineProps<{
  contacts: Contact[]
  filters: Filters
  pagination: IPagination
}>()

const filters = ref({ ...props.filters })
const contacts = ref(props.contacts)

const search = useDebounceFn(() => {
  router.get(route("admin.contacts.list"), filters.value, {
    preserveState: true,
    replace: true,
    onSuccess: () => {
      contacts.value = props.contacts
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

const destroyContact = (id: number) => {
  router.delete(route("admin.contacts.destroy", id), {
    preserveState: true,
    replace: true,
    onSuccess: () => {
      contacts.value = props.contacts
    },
  })
}
</script>

<template>
  <AdminTitle :title="'Contact'" />
  <Breadcrumb
    :breadcrumbs="[
      { label: 'Tableau de bord', route: 'admin.dashboard', active: false },
      {
        label: 'Contact',
        route: 'admin.contacts.list',
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
        <TableHead> Email </TableHead>
        <TableHead>
          <Button
            @click="
              filters.subject === 'asc'
                ? ((filters.subject = 'desc'), (filters.created_at = null))
                : ((filters.subject = 'asc'), (filters.created_at = null))
            "
            variant="ghost"
            >Sujet<ChevronsUpDown class="ml-2 w-4"
          /></Button>
        </TableHead>
        <TableHead> Commentaire </TableHead>
        <TableHead>
          <Button
            @click="
              filters.created_at === 'asc'
                ? ((filters.created_at = 'desc'), (filters.subject = null))
                : ((filters.created_at = 'asc'), (filters.subject = null))
            "
            variant="ghost"
            >Créé<ChevronsUpDown class="ml-2 w-4"
          /></Button>
        </TableHead>
        <TableHead class="text-right"> Action </TableHead>
      </TableRow>
    </TableHeader>
    <TableBody>
      <TableEmpty v-if="contacts.length === 0" />
      <TableRow v-for="contact in contacts" :key="contact.id">
        <TableCell class="truncate">
          {{ contact.name }}
        </TableCell>
        <TableCell class="truncate"> {{ contact.email }} </TableCell
        ><TableCell class="truncate">
          {{ contact.subject }}
        </TableCell>
        <TableCell class="max-w-[200px] truncate">
          {{ contact.message }}
        </TableCell>
        <TableCell>
          {{
            useDateFormat(contact.created_at, "DD-MM-YY", {
              locales: "fr-FR",
            })
          }}
        </TableCell>
        <TableCell class="flex justify-end gap-4">
          <Link :href="route('admin.contacts.show', contact.id)">
            <Eye class="w-4 cursor-pointer" />
          </Link>

          <a :href="`mailto:${contact.email}?subject=${contact.subject}`"
            ><MailPlus class="w-4 cursor-pointer"
          /></a>
          <AlertDialog>
            <AlertDialogTrigger as-child>
              <Trash class="w-4 cursor-pointer" />
            </AlertDialogTrigger>
            <AlertDialogContent>
              <AlertDialogHeader>
                <AlertDialogTitle>Êtes-vous vraiment sûr ?</AlertDialogTitle>
                <AlertDialogDescription>
                  Cette action ne peut pas être annulée. Cela supprimera
                  définitivement le message de contact.
                </AlertDialogDescription>
              </AlertDialogHeader>
              <AlertDialogFooter>
                <AlertDialogCancel>Annuler</AlertDialogCancel>
                <AlertDialogAction
                  @click="destroyContact(contact.id)"
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
    :label="'messages'"
  />
</template>
