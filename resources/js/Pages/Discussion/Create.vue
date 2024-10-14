<script setup lang="ts">
import { Head, Link, useForm } from "@inertiajs/vue3"
import {
  Form,
  FormControl,
  FormField,
  FormItem,
  FormLabel,
} from "@/Components/ui/form"
import { ToggleGroup, ToggleGroupItem } from "@/Components/ui/toggle-group"
import { Input } from "@/Components/ui/input"
import { Button } from "@/Components/ui/button"
import Wrapper from "@/Components/layout/Wrapper.vue"
import { CategoryDeal } from "@/types/model/deal"
import FormError from "@/Components/FormError.vue"
import TipTap from "@/Components/TipTap.vue"
import {
  Breadcrumb,
  BreadcrumbItem,
  BreadcrumbLink,
  BreadcrumbList,
  BreadcrumbSeparator,
} from "@/Components/ui/breadcrumb"
import { ref } from "vue"

const props = defineProps<{
  categories: CategoryDeal[]
}>()

const form = useForm({
  title: "",
  content: "",
  category: "",
  thumbnail: "",
})

const submit = () => {
  form.post(route("discussions.create"), {
    preserveScroll: true,
  })
}

const images = ref([])
const handleImageUpload = (event: Event): void => {
  images.value = []
  const target = event.target as HTMLInputElement
  if (!target.files) return

  const files = Array.from(target.files)
  form.thumbnail = files[0]

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
}
</script>
<template>
  <div class="w-full bg-page py-8">
    <!--      TODO: Refaire le front-->
    <Head title="Créer un bon plan" />
    <Wrapper class="!max-w-[800px]">
      <Breadcrumb class="mb-6">
        <BreadcrumbList>
          <BreadcrumbItem>
            <BreadcrumbLink>
              <Link :href="route('home.index')"> Deally </Link>
            </BreadcrumbLink>
          </BreadcrumbItem>
          <BreadcrumbSeparator />
          <BreadcrumbItem>
            <BreadcrumbLink>
              <!-- TODO: Add redirection to category-->
              <Link :href="route('home.index')"> Discussions </Link>
            </BreadcrumbLink>
          </BreadcrumbItem>
          <BreadcrumbSeparator />
          <BreadcrumbItem>
            <BreadcrumbLink>
              <Link
                class="font-semibold text-foreground"
                :href="route('discussions.create')"
              >
                Créer une discussion
              </Link>
            </BreadcrumbLink>
          </BreadcrumbItem>
        </BreadcrumbList>
      </Breadcrumb>

      <Form v-slot="{ meta, values, validate }" as="" keep-values>
        <form @submit.prevent="submit">
          <div class="flex flex-col gap-4">
            <FormField name="title">
              <FormItem>
                <FormLabel>Titre</FormLabel>
                <FormControl>
                  <Input type="text" v-model="form.title" />
                </FormControl>
                <FormError :message="form.errors.title" />
              </FormItem>
            </FormField>

            <FormField name="thumbnail">
              <FormItem>
                <FormLabel>Image de couverture</FormLabel>
                <FormControl>
                  <Input
                    title="your text"
                    type="file"
                    accept=".jpg, .jpeg, .png, .webp"
                    @change="handleImageUpload"
                  />
                </FormControl>
                <div
                  v-if="images.length"
                  class="rounded-lg border bg-white p-4"
                >
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
                <FormError :message="form.errors.thumbnail" />
              </FormItem>
            </FormField>

            <FormField name="content">
              <FormItem>
                <FormLabel>Description</FormLabel>
                <FormControl>
                  <!--                  <Textarea v-model="form.content" />-->
                  <TipTap v-model="form.content" />
                </FormControl>
                <FormError :message="form.errors.content" />
              </FormItem>
            </FormField>

            <FormField name="category">
              <FormItem>
                <FormLabel>Catégorie</FormLabel>
                <FormControl>
                  <ToggleGroup
                    id="category"
                    type="single"
                    class="flex flex-wrap !justify-normal gap-4 rounded-lg border bg-white p-4"
                    v-model="form.category"
                  >
                    <ToggleGroupItem
                      aria-label="Toggle bold"
                      v-for="(category, index) in props.categories"
                      :value="category.name"
                      :key="index"
                      class="border data-[state=on]:bg-primary data-[state=on]:text-white"
                    >
                      {{ category.name }}
                    </ToggleGroupItem>
                  </ToggleGroup>
                </FormControl>
                <FormError :message="form.errors.content" />
              </FormItem>
            </FormField>
          </div>

          <div class="mt-4 flex items-center justify-between">
            <Button size="sm" type="submit"> Publier </Button>
          </div>
        </form>
      </Form>
    </Wrapper>
  </div>
</template>
