<script setup lang="ts">
import AdminTitle from "@/Components/Admin/AdminTitle.vue"
import { Button } from "@/Components/ui/button"
import { Input } from "@/Components/ui/input"
import { Label } from "@/Components/ui/label"
import FormError from "@/Components/FormError.vue"
import Breadcrumb from "@/Components/Admin/Breadcrumb.vue"
import { useForm } from "@inertiajs/vue3"

const form = useForm({
  name: "",
})

const submit = () => {
  form.post(route("admin.categories-deals.store"), {
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
        label: 'Catégories deals',
        route: 'admin.categories-deals.list',
        active: false,
      },
      {
        label: 'Créer',
        route: 'admin.categories-deals.create',
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

    <Button @click="submit" type="submit" class="mt-4 w-fit">Créer</Button>
  </div>
</template>
