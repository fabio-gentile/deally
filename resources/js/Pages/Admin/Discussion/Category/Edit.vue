<script setup lang="ts">
import AdminTitle from "@/Components/Admin/AdminTitle.vue"
import { Button } from "@/Components/ui/button"
import { Input } from "@/Components/ui/input"
import { Label } from "@/Components/ui/label"
import FormError from "@/Components/FormError.vue"
import Breadcrumb from "@/Components/Breadcrumb.vue"
import { useForm } from "@inertiajs/vue3"
import { CategoryDiscussion } from "@/types/model/discussion"

const props = defineProps<{
  category: CategoryDiscussion
}>()

const form = useForm({
  name: props.category.name,
  _method: "patch",
})

const submit = () => {
  form.post(route("admin.categories-discussions.update", props.category.id), {
    preserveState: true,
    onSuccess: () => {
      form.reset()
    },
  })
}
</script>

<template>
  <AdminTitle :title="'Créer une catégorie'" />
  <Breadcrumb
    :breadcrumbs="[
      { label: 'Tableau de bord', route: 'admin.dashboard', active: false },
      {
        label: 'Catégories discussion',
        route: 'admin.categories-discussions.list',
        active: false,
      },
      {
        label: props.category.name,
        route: 'admin.categories-discussions.edit',
        params: { id: category.id },
        active: true,
      },
    ]"
  />
  <div class="grid gap-4">
    <div class="grid gap-2">
      <Label for="name">Nom</Label>
      <Input
        v-model="form.name"
        id="name"
        type="text"
        autocomplete="name"
        required
      />
      <FormError :message="form.errors.name" />
    </div>

    <Button @click="submit" type="submit" class="mt-4 w-fit">Modifier</Button>
  </div>
</template>
