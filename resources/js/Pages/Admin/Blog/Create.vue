<script setup lang="ts">
import AdminTitle from "@/Components/Admin/AdminTitle.vue"
import { Button } from "@/Components/ui/button"
import { Input } from "@/Components/ui/input"
import { Label } from "@/Components/ui/label"
import FormError from "@/Components/FormError.vue"
import Breadcrumb from "@/Components/Breadcrumb.vue"
import { useForm } from "@inertiajs/vue3"
import TipTap from "@/Components/TipTap.vue"
import { Checkbox } from "@/Components/ui/checkbox"
import { ref } from "vue"

const form = useForm({
  title: "",
  description: "",
  image: "",
  meta_title: "",
  meta_description: "",
  meta_keywords: "",
  is_published: false,
})

const submit = () => {
  form.post(route("admin.blog.store"), {
    preserveState: true,
  })
}

const images = ref([])
const handleImageUpload = (event: Event): void => {
  images.value = []
  const target = event.target as HTMLInputElement
  if (!target.files) return

  const files = Array.from(target.files)
  form.image = files[0]

  files.forEach((file: File) => {
    const reader = new FileReader()

    reader.onload = (e: ProgressEvent<FileReader>) => {
      if (e.target && e.target.result) {
        images.value.push({
          file,
          previewUrl: e.target.result as string, // Génère un aperçu pour l'image
        })
      }
    }

    reader.readAsDataURL(file)
  })
}

// Fonction pour supprimer une image de la liste
const removeImage = (index: number): void => {
  images.value.splice(index, 1)
  form.image = ""
}
</script>

<template>
  <AdminTitle :title="'Créer un article'" />
  <Breadcrumb
    :breadcrumbs="[
      { label: 'Tableau de bord', route: 'admin.dashboard', active: false },
      {
        label: 'Blog',
        route: 'admin.blog.list',
        active: false,
      },
      {
        label: 'Créer un article',
        route: 'admin.blog.create',
        active: true,
      },
    ]"
  />
  <form @submit.prevent="submit" class="grid gap-4">
    <div class="grid gap-2">
      <Label for="title">Titre</Label>
      <Input v-model="form.title" id="title" type="text" required />
      <FormError :message="form.errors.title" />
    </div>
    <div class="grid gap-2">
      <Label for="description">Description</Label>
      <TipTap v-model="form.description" id="description" />
      <FormError :message="form.errors.description" />
    </div>
    <div class="grid gap-2">
      <Label for="meta_title">Meta title</Label>
      <Input type="text" v-model="form.meta_title" id="meta_title" />
      <FormError :message="form.errors.meta_title" />
    </div>
    <div class="grid gap-2">
      <Label for="meta_description">Meta description</Label>
      <Input
        type="text"
        v-model="form.meta_description"
        id="meta_description"
      />
      <FormError :message="form.errors.meta_description" />
    </div>
    <div class="grid gap-2">
      <Label for="meta_keywords">Meta mots-clés</Label>
      <Input type="text" v-model="form.meta_keywords" id="meta_keywords" />
      <FormError :message="form.errors.meta_keywords" />
    </div>
    <div class="flex items-center gap-2">
      <Checkbox
        id="is_published"
        name="is_published"
        v-model:checked="form.is_published"
      />
      <FormError :message="form.errors.is_published" />
      <Label for="is_published">Publier l'article</Label>
    </div>
    <div class="grid gap-2">
      <Label for="image">Image</Label>
      <Input
        accept=".jpg, .jpeg, .png, .webp"
        @change="handleImageUpload"
        type="file"
        id="image"
      />
      <div v-if="images.length" class="rounded-lg border bg-white p-4">
        <div
          v-for="(image, index) in images"
          :key="index"
          class="relative w-fit rounded-lg border"
        >
          <img
            class="h-64 w-64 object-contain"
            :src="image.previewUrl"
            alt="Image Preview"
          />
          <Button
            variant="link"
            @click="removeImage(index)"
            class="w-full text-center font-semibold"
            >Supprimer
          </Button>
        </div>
      </div>
      <FormError :message="form.errors.image" />
    </div>

    <Button type="submit" class="mt-4 w-fit">Créer</Button>
  </form>
</template>
