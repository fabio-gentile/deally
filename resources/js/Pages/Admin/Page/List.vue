<script setup lang="ts">
import AdminTitle from "@/Components/Admin/AdminTitle.vue"
import { Link } from "@inertiajs/vue3"
import { Pencil } from "lucide-vue-next"
import { Button } from "@/Components/ui/button"
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
import Breadcrumb from "@/Components/Admin/Breadcrumb.vue"
import { Page } from "@/types/model/page"
import striptags from "striptags"

const props = defineProps<{
  pages: Page[]
}>()

const pages = ref(props.pages)
</script>

<template>
  <AdminTitle title="Pages">Pages</AdminTitle>
  <Breadcrumb
    :breadcrumbs="[
      { label: 'Tableau de board', route: 'admin.dashboard', active: false },
      {
        label: 'Pages',
        route: 'admin.pages.list',
        active: true,
      },
    ]"
  />
  <div class="flex flex-wrap justify-end gap-4">
    <Link :href="route('admin.pages.create')">
      <Button>Créer une page</Button>
    </Link>
  </div>
  <Table class="rounded-lg bg-white">
    <TableHeader>
      <TableRow>
        <TableHead> Titre </TableHead>
        <TableHead> Contenu </TableHead>
        <TableHead class="text-right"> Action </TableHead>
      </TableRow>
    </TableHeader>
    <TableBody>
      <TableEmpty v-if="pages.length === 0" />
      <TableRow v-for="page in pages" :key="page.id">
        <TableCell class="truncate">
          {{ page.title }}
        </TableCell>
        <TableCell class="max-w-[250px] truncate">
          {{ striptags(page.content) }}
        </TableCell>
        <TableCell class="flex justify-end gap-4">
          <Link :href="route('admin.pages.edit', page.id)">
            <Pencil class="w-4 cursor-pointer" />
          </Link>
        </TableCell>
      </TableRow>
    </TableBody>
  </Table>
</template>
