<script lang="ts" setup>
import { ref, watch } from "vue"
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
import { Pagination as IPagination } from "@/types/model/miscellaneous"

const props = defineProps<{
  pagination: IPagination
  modelValue?: string
  onSearch: () => void
  onPageChange: (page: number) => void
}>()

const emit = defineEmits(["update:modelValue"])

const localPerPage = ref(props.modelValue)

// Watch for changes to localPerPage and update parent
watch(localPerPage, (newValue) => {
  emit("update:modelValue", newValue)
})

const onChangePerPage = () => {
  props.onSearch()
}

const changePage = (page: number) => {
  props.onPageChange(page)
}
</script>
<template>
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
        <Select v-model="localPerPage">
          <SelectTrigger @change="onChangePerPage">
            <SelectValue :placeholder="localPerPage?.toString() ?? 10" />
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
