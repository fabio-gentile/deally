<script setup lang="ts">
import { Head, Link, useForm } from "@inertiajs/vue3"
import { ref } from "vue"
import { X } from "lucide-vue-next"
import {
  Form,
  FormControl,
  FormField,
  FormItem,
  FormLabel,
} from "@/Components/ui/form"
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/Components/ui/select"
import { Input } from "@/Components/ui/input"
import { Button } from "@/Components/ui/button"
import Wrapper from "@/Components/layout/Wrapper.vue"
import { CategoryDeal } from "@/types/model/deal"
import FormError from "@/Components/FormError.vue"
import { Deal } from "@/types/model/deal"
import { ImageDeal } from "@/types/model/deal"
import axios from "axios"
import TipTap from "@/Components/TipTap.vue"
import {
  Breadcrumb,
  BreadcrumbItem,
  BreadcrumbLink,
  BreadcrumbList,
  BreadcrumbSeparator,
} from "@/Components/ui/breadcrumb"

interface Image {
  file: File
  previewUrl: string
}

const props = defineProps<{
  categories: CategoryDeal[]
  deal: Deal
  current_category: string
  start_date: string
  expiration_date: string
  uploaded_images: string[]
}>()

const form = useForm({
  images: [],
  title: props.deal?.title,
  description: props.deal?.description,
  price: props.deal?.price || 0,
  original_price: props.deal?.original_price,
  deal_url: props.deal?.deal_url,
  promo_code: props.deal?.promo_code,
  category: props.current_category,
  start_date: props.start_date,
  expiration_date: props.expiration_date,
  uploaded_images: props.uploaded_images || [],
  _method: "patch",
})

const images = ref<Image[]>([])
const uploaded_images = ref<string[]>(props.uploaded_images)

const submit = (): void => {
  const formData = new FormData()

  images.value.forEach((image) => {
    formData.append("images[]", image.file)
  })

  form.images = formData.getAll("images[]")

  if (form.price === "") {
    form.price = 0
  }

  form.post(route("deals.update", props.deal.id))
}

const handleImageUpload = (event: Event): void => {
  const target = event.target as HTMLInputElement
  if (!target.files) return

  const files = Array.from(target.files)

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

// Function to remove an image from the list
const removeImage = (index: number): void => {
  images.value.splice(index, 1)
}

// Function to delete an uploaded image
const deleteImage = async (filename: string, index: number): Promise<void> => {
  try {
    await axios.delete(route("deals.delete-image", filename))
    uploaded_images.value.splice(index, 1)
  } catch (error) {
    throw new Error("Une erreur est survenue lors de la suppression de l'image")
  }
}
</script>
<template>
  <div class="py-8">
    <!--      TODO: Refaire le front-->
    <Head :title="'Modification de ' + props.deal.title" />
    <Wrapper class="max-w-[800px]">
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
              <Link :href="route('home.index')"> Les bons plan </Link>
            </BreadcrumbLink>
          </BreadcrumbItem>
          <BreadcrumbSeparator />
          <BreadcrumbItem>
            <BreadcrumbLink>
              <Link :href="route('deals.show', deal.slug)">
                {{ deal.title }}
              </Link>
            </BreadcrumbLink>
          </BreadcrumbItem>
          <BreadcrumbSeparator />
          <BreadcrumbItem>
            <BreadcrumbLink>
              <Link
                class="font-semibold text-foreground"
                :href="route('deals.edit', deal.slug)"
              >
                Modifier le deal
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

            <FormField name="description">
              <FormItem>
                <FormLabel>Description</FormLabel>
                <FormControl>
                  <TipTap v-model="form.description" />
                  <!--                  <Textarea v-model="form.description" />-->
                </FormControl>
                <FormError :message="form.errors.description" />
              </FormItem>
            </FormField>

            <FormField name="price">
              <FormItem>
                <FormLabel>Prix</FormLabel>
                <FormControl>
                  <Input
                    placeholder="0,00"
                    type="number"
                    v-model="form.price"
                  />
                </FormControl>
                <FormError :message="form.errors.price" />
              </FormItem>
            </FormField>

            <FormField name="original_price">
              <FormItem>
                <FormLabel>Prix originel</FormLabel>
                <FormControl>
                  <Input
                    placeholder="0,00"
                    type="number"
                    v-model="form.original_price"
                  />
                </FormControl>
                <FormError :message="form.errors.original_price" />
              </FormItem>
            </FormField>

            <FormField name="deal_url">
              <FormItem>
                <FormLabel>Lien du deal</FormLabel>
                <FormControl>
                  <Input type="text" v-model="form.deal_url" />
                </FormControl>
                <FormError :message="form.errors.deal_url" />
              </FormItem>
            </FormField>

            <FormField name="promo_code">
              <FormItem>
                <FormLabel>Code promotionnel</FormLabel>
                <FormControl>
                  <Input
                    placeholder="Optionnel"
                    type="text"
                    v-model="form.promo_code"
                  />
                </FormControl>
                <FormError :message="form.errors.promo_code" />
              </FormItem>
            </FormField>

            <FormField name="images">
              <FormItem>
                <FormLabel>Images</FormLabel>
                <FormControl>
                  <Input type="file" multiple @change="handleImageUpload" />
                </FormControl>

                <div
                  v-if="images.length || uploaded_images.length"
                  class="flex flex-wrap gap-4 rounded-lg border bg-white p-4"
                >
                  <div
                    v-for="(image, index) in uploaded_images"
                    :key="index"
                    class="relative w-fit rounded-lg border"
                  >
                    <img
                      class="h-64 w-64 object-contain"
                      :src="'/storage/' + image.path + '/' + image.filename"
                      alt="Image Preview"
                    />
                    <Button
                      @click.prevent="deleteImage(image.filename, index)"
                      variant="link"
                      method="delete"
                      :href="'/deals/image/' + image.filename"
                      class="w-full text-center font-semibold"
                    >
                      Supprimer
                    </Button>
                  </div>

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
                    <FormError
                      class="text-center"
                      v-if="form.errors[`images.${index}`]"
                      :message="form.errors[`images.${index}`]"
                    />
                  </div>
                </div>
                <FormError :message="form.errors.images" />
              </FormItem>
            </FormField>

            <FormField name="category">
              <FormItem>
                <FormLabel>Catégorie</FormLabel>
                <Select v-model="form.category">
                  <FormControl>
                    <SelectTrigger v-model="form.category">
                      <SelectValue placeholder="Choisissez une catégorie" />
                    </SelectTrigger>
                  </FormControl>
                  <SelectContent>
                    <SelectGroup>
                      <SelectItem
                        v-for="category in props.categories"
                        :value="category.name"
                        >{{ category.name }}
                      </SelectItem>
                    </SelectGroup>
                  </SelectContent>
                </Select>
                <FormError :message="form.errors.category" />
              </FormItem>
            </FormField>

            <FormField name="start_date">
              <FormItem>
                <FormLabel>Date de début</FormLabel>
                <FormControl>
                  <Input type="datetime-local" v-model="form.start_date" />
                </FormControl>
                <FormError :message="form.errors.start_date" />
              </FormItem>
            </FormField>
            <FormField name="expiration_date">
              <FormItem>
                <FormLabel>Date d'expiration</FormLabel>
                <FormControl>
                  <Input type="datetime-local" v-model="form.expiration_date" />
                </FormControl>
                <FormError :message="form.errors.expiration_date" />
              </FormItem>
            </FormField>
          </div>

          <div class="mt-4 flex items-center justify-between">
            <Button size="sm" type="submit"> Modifier </Button>
          </div>
        </form>
      </Form>
    </Wrapper>
  </div>
</template>
