<script setup lang="ts">
import AdminTitle from "@/Components/Admin/AdminTitle.vue"
import { Button } from "@/Components/ui/button"
import { Input } from "@/Components/ui/input"
import { Label } from "@/Components/ui/label"
import FormError from "@/Components/FormError.vue"
import Breadcrumb from "@/Components/Admin/Breadcrumb.vue"
import { useForm } from "@inertiajs/vue3"
import TipTap from "@/Components/TipTap.vue"
import { Page } from "@/types/model/page"

const props = defineProps<{
  page: Page
}>()

const form = useForm({
  content: props.page.content,
  _method: "patch",
})

const submit = () => {
  form.post(route("admin.pages.update", props.page.id), {
    preserveState: true,
    onSuccess: () => {
      form.reset()
    },
  })
}
</script>

<template>
  <AdminTitle :title="'Créer une pages'" />
  <Breadcrumb
    :breadcrumbs="[
      { label: 'Tableau de bord', route: 'admin.dashboard', active: false },
      {
        label: 'Pages',
        route: 'admin.pages.list',
        active: false,
      },
      {
        label: 'Modifier la page ' + props.page.title,
        route: 'admin.pages.edit',
        params: { id: props.page.id },
        active: true,
      },
    ]"
  />
  <div class="grid gap-4">
    <div class="grid gap-2">
      <Label for="title">Titre</Label>
      <Input
        disabled
        v-model="props.page.title"
        id="title"
        type="text"
        required
      />
    </div>
    <div class="grid gap-2">
      <Label for="content">Contenu</Label>
      <TipTap v-model="form.content" id="content" />
      <FormError :message="form.errors.content" />
    </div>

    <Button @click="submit" type="submit" class="mt-4 w-fit">Modifier</Button>
  </div>
</template>
