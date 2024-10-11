<script setup lang="ts">
import { Head, Link, useForm } from "@inertiajs/vue3"
import { ref } from "vue"
import {
  Form,
  FormControl,
  FormField,
  FormItem,
  FormLabel,
} from "@/Components/ui/form"
import { Input } from "@/Components/ui/input"
import { Button } from "@/Components/ui/button"
import Wrapper from "@/Components/layout/Wrapper.vue"
import { CategoryDeal } from "@/types/model/deal"
import FormError from "@/Components/FormError.vue"
import TipTap from "@/Components/TipTap.vue"
import { ToggleGroup, ToggleGroupItem } from "@/Components/ui/toggle-group"
import {
  Breadcrumb,
  BreadcrumbItem,
  BreadcrumbLink,
  BreadcrumbList,
  BreadcrumbSeparator,
} from "@/Components/ui/breadcrumb"

const props = defineProps<{
  categories: CategoryDeal[]
}>()

const form = useForm<{
  images: File[]
  deal_url: string
  title: string
  description: string
  price: number | null
  original_price: number | null
  promo_code: string
  category: string
  start_date: string
  expiration_date: string
}>({
  images: [],
  deal_url: "",
  title: "",
  description: "",
  price: null,
  original_price: null,
  promo_code: "",
  category: "",
  start_date: "",
  expiration_date: "",
})

const submit = () => {
  const formData = new FormData()

  images.value.forEach((image) => {
    formData.append("images[]", image.file)
  })

  form.images = formData.getAll("images[]") as File[]

  if (form.price === "") {
    form.price = 0
  }

  form.post(route("deals.store"), {
    preserveScroll: true,
  })
}

const images = ref<{ file: File; previewUrl: string }[]>([])

const handleImageUpload = (event: Event) => {
  const input = event.target as HTMLInputElement
  const files = Array.from(input.files || [])

  files.forEach((file) => {
    const reader = new FileReader()
    reader.onload = (e) => {
      if (e.target?.result) {
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
const removeImage = (index: number) => {
  images.value.splice(index, 1)
}
</script>
<template>
  <div class="py-8">
    <!--      TODO: Refaire le front-->
    <Head title="Créer un bon plan" />
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
              <Link :href="route('home.index')"> Les bons plans </Link>
            </BreadcrumbLink>
          </BreadcrumbItem>
          <BreadcrumbSeparator />
          <BreadcrumbItem>
            <BreadcrumbLink>
              <Link
                class="font-semibold text-foreground"
                :href="route('deals.create')"
              >
                Créer un deal
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
                  <!--                  <Textarea v-model="form.description" />-->
                  <TipTap v-model="form.description" />
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
                    <FormError
                      class="text-center"
                      v-if="form.errors[`images.${index}`]"
                      :message="form.errors[`images.${index}`]"
                    />
                  </div>
                </div>
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
            <Button size="sm" type="submit"> Publier </Button>
          </div>
        </form>
      </Form>
    </Wrapper>
  </div>
</template>
