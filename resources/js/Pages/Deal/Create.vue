<script setup lang="ts">
import { Head, useForm } from "@inertiajs/vue3"
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
import Wrapper from "@/Pages/Layout/Wrapper.vue"
import { CategoryDeal } from "@/types/model/category-deal"
import FormError from "@/Components/FormError.vue"
import { Textarea } from "@/Components/ui/textarea"
import TipTap from "@/Components/TipTap.vue"

const props = defineProps<{
  categories: CategoryDeal[]
}>()

const form = useForm({
  images: [],
  deal_url: "",
  title: "",
  description: "",
  price: 0,
  original_price: 0,
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
  form.images = formData.getAll("images[]")

  // console.log(form.description)
  form.post(route("deals.store"), {
    preserveScroll: true,
  })
}

const images = ref([])
const handleImageUpload = (event) => {
  const files = Array.from(event.target.files)

  files.forEach((file) => {
    const reader = new FileReader()
    reader.onload = (e) => {
      images.value.push({
        file,
        previewUrl: e.target.result, // Génère un aperçu pour l'image
      })
    }

    reader.readAsDataURL(file)
  })
}

// Fonction pour supprimer une image de la liste
const removeImage = (index) => {
  images.value.splice(index, 1)
}
</script>
<template>
  <div>
    <!--      TODO: Refaire le front-->
    <Head title="Créer un bon plan" />
    <Wrapper>
      <Form v-slot="{ meta, values, validate }" as="" keep-values>
        <form @submit.prevent="submit">
          <div class="mt-4 flex flex-col gap-4">
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
                <div v-if="images.length">
                  <div
                    v-for="(image, index) in images"
                    :key="index"
                    class="relative w-fit"
                  >
                    <img
                      class="h-64 w-64 object-contain"
                      :src="image.previewUrl"
                      alt="Image Preview"
                    />
                    <Button
                      @click="removeImage(index)"
                      variant="destructive"
                      size="xs"
                      class="absolute right-2 top-2 rounded-full"
                    >
                      <X class="h-4 w-4" />
                    </Button>
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
            <Button size="sm" type="submit"> Publier </Button>
          </div>
        </form>
      </Form>
    </Wrapper>
  </div>
</template>
