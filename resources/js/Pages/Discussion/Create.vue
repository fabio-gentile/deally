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
import { CategoryDeal } from "@/types/model/category-deal"
import FormError from "@/Components/FormError.vue"
import { Textarea } from "@/Components/ui/textarea"
import TipTap from "@/Components/TipTap.vue"
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

const form = useForm({
  images: [],
  title: "",
  content: "",
  category: "",
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
  <div class="w-full bg-page py-8">
    <Wrapper>
      <Breadcrumb>
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
      <!--      TODO: Refaire le front-->
      <Head title="Créer un bon plan" />
      <Wrapper class="mt-6 !max-w-[850px]">
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
            </div>

            <div class="mt-4 flex items-center justify-between">
              <Button size="sm" type="submit"> Publier </Button>
            </div>
          </form>
        </Form>
      </Wrapper>
    </Wrapper>
  </div>
</template>
