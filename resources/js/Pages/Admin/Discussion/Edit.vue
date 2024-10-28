<script setup lang="ts">
import { useForm } from "@inertiajs/vue3"
import { ref } from "vue"
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
import FormError from "@/Components/FormError.vue"
import TipTap from "@/Components/TipTap.vue"
import { CategoryDiscussion, Discussion } from "@/types/model/discussion"
import AdminTitle from "@/Components/Admin/AdminTitle.vue"
import Breadcrumb from "@/Components/Breadcrumb.vue"

const props = defineProps<{
  categories: CategoryDiscussion[]
  discussion: Discussion
  currentCategory: string
  currentThumbnail: {
    path: string
    filename: string
    original_filename: string
  } | null
}>()

const currentThumbnail = ref(props.currentThumbnail)

const form = useForm({
  title: props.discussion?.title,
  content: props.discussion?.content,
  category: props.currentCategory,
  thumbnail: "",
  isThumbnailRemoved: false,
  _method: "patch",
})

const submit = () => {
  form.post(route("admin.discussions.update", props.discussion.id), {
    preserveScroll: true,
  })
}

interface Image {
  file: File
  previewUrl: string
}

const images = ref<Image[]>([])

const handleImageUpload = (event: Event): void => {
  const target = event.target as HTMLInputElement
  if (!target.files) return

  const files = Array.from(target.files)
  form.thumbnail = files[0]

  files.forEach((file: File) => {
    const reader = new FileReader()

    reader.onload = (e: ProgressEvent<FileReader>) => {
      if (e.target && e.target.result) {
        images.value = [] // Reset the images array
        images.value.push({
          file,
          previewUrl: e.target.result as string, // Generates a preview for the image
        })
      }
    }

    reader.readAsDataURL(file)
  })
}

// Fonction pour supprimer une image de la liste
const removeImage = (index: number): void => {
  images.value.splice(index, 1)
  form.thumbnail = ""
}

const handleRemoveThumbnail = () => {
  form.isThumbnailRemoved = true
  currentThumbnail.value = null
}
</script>
<template>
  <AdminTitle :title="'Modification ' + discussion.title" />
  <Breadcrumb
    :breadcrumbs="[
      { label: 'Tableau de bord', route: 'admin.dashboard', active: false },
      { label: 'Discussion', route: 'admin.discussions.list', active: false },
      {
        label: discussion.title,
        route: 'admin.discussions.edit',
        params: { id: discussion.id },
        active: true,
      },
    ]"
  />
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
            <FormControl v-if="!currentThumbnail">
              <Input
                title="your text"
                type="file"
                accept=".jpg, .jpeg, .png, .webp"
                @change="handleImageUpload"
              />
            </FormControl>
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

            <!-- Current thumbnail -->
            <div v-if="currentThumbnail" class="rounded-lg border bg-white p-4">
              <div class="relative w-fit rounded-lg border">
                <img
                  class="h-64 w-64 object-contain"
                  :src="
                    '/storage/' +
                    currentThumbnail.path +
                    '/' +
                    currentThumbnail.filename
                  "
                  alt="Image Preview"
                />
                <Button
                  variant="link"
                  @click="handleRemoveThumbnail"
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
        <Button size="sm" type="submit"> Mettre à jour </Button>
      </div>
    </form>
  </Form>
</template>
