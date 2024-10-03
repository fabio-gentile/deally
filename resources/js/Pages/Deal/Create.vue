<script setup lang="ts">
import { Head, useForm } from "@inertiajs/vue3"
import { Check, Circle, Dot } from "lucide-vue-next"
import { toTypedSchema } from "@vee-validate/zod"
import * as z from "zod"
import { h, ref } from "vue"
import {
  Stepper,
  StepperDescription,
  StepperItem,
  StepperSeparator,
  StepperTitle,
  StepperTrigger,
} from "@/Components/ui/stepper"
import {
  Form,
  FormControl,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
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
import { toast } from "@/Components/ui/toast"
import Wrapper from "@/Pages/Layout/Wrapper.vue"
import { CategoryDeal } from "@/types/model/category-deal"

const props = defineProps<{
  categories: CategoryDeal[]
}>()

const categoryNames = props.categories.map((category) => category.name)

console.log(categoryNames)
const MAX_FILE_SIZE = 1024 * 1024 * 2 // 2MB
const ACCEPTED_IMAGE_MIME_TYPES = [
  "image/png",
  "image/jpeg",
  "image/jpg",
  "image/webp",
]
const ACCEPTED_IMAGE_TYPES = ["jpeg", "jpg", "png", "webp"]

const formSchema = [
  z
    .object({
      deal_url: z
        .string({
          errorMap: () => ({
            message: "Le lien du deal doit être une URL valide.",
          }),
        })
        .url()
        .nullable()
        .optional(),
      title: z
        .string({
          errorMap: () => ({
            message: "Le titre doit contenir entre 6 et 140 caractères.",
          }),
        })
        .min(6)
        .max(140),
      price: z
        .number({
          errorMap: () => ({
            message: "Le prix doit être compris entre 0 et 99999€.",
          }),
        })
        .min(0)
        .max(99999),
      original_price: z
        .number({
          errorMap: () => ({
            message: "Le prix doit être compris entre 0 et 99999€.",
          }),
        })
        .min(0)
        .max(99999),
      promo_code: z
        .string({
          errorMap: () => ({
            message: "Le code promo doit contenir entre 2 et 50 caractères.",
          }),
        })
        .min(2)
        .max(50)
        .nullable()
        .optional(),
    })
    .refine((data) => data.original_price > data.price, {
      message: "Le prix original doit être supérieur au prix réduit.",
      path: ["original_price"],
    }),
  z.object({
    images: z.array(
      z
        .instanceof(FileList)
        .refine((files) => {
          return files?.[0]?.size <= MAX_FILE_SIZE
        }, `Taille maximale de fichier de 2MB.`)
        .refine(
          (files) => ACCEPTED_IMAGE_MIME_TYPES.includes(files?.[0]?.type),
          "Seuls les fichiers d'images png/jpg/jpeg/webp sont autorisés."
        )
    ),
  }),
  z
    .object({
      /* @ts-ignore */
      category: z.union(categoryNames.map((name) => z.literal(name))),
      start_date: z.date(),
      expiration_date: z.date(),
    })
    .refine((data) => data.expiration_date > data.start_date, {
      message: "La date d'expiration doit être postérieure à la date de début.",
      path: ["expiration_date"],
    }),
  ,
]

const form = useForm({
  name: "",
  email: "",
  password: "",
  password_confirmation: "",
})

const stepIndex = ref(1)
const steps = [
  {
    step: 1,
    title: "Informations",
    description: "Les informations de base de votre bon plan",
  },
  {
    step: 2,
    title: "Images",
    description: "Ajoutez des images pour illustrer votre bon plan",
  },
  {
    step: 3,
    title: "Derniers détails",
    description: "Les derniers détails de votre bon plan",
  },
]

function onSubmit(values: any) {
  toast({
    title: "You submitted the following values:",
    description: h(
      "pre",
      { class: "mt-2 w-[340px] rounded-md bg-slate-950 p-4" },
      h("code", { class: "text-white" }, JSON.stringify(values, null, 2))
    ),
  })
}
</script>
<template>
  <div>
    <Head title="Créer un bon plan" />
    <Wrapper>
      <Form
        v-slot="{ meta, values, validate }"
        as=""
        keep-values
        :validation-schema="toTypedSchema(formSchema[stepIndex - 1])"
      >
        <Stepper
          v-slot="{ isNextDisabled, isPrevDisabled, nextStep, prevStep }"
          v-model="stepIndex"
          class="block w-full"
        >
          <form
            @submit="
              (e) => {
                e.preventDefault()
                validate()

                if (stepIndex === steps.length && meta.valid) {
                  onSubmit(values)
                }
              }
            "
          >
            <div class="flex-start flex w-full gap-2">
              <StepperItem
                v-for="step in steps"
                :key="step.step"
                v-slot="{ state }"
                class="relative flex w-full flex-col items-center justify-center"
                :step="step.step"
              >
                <StepperSeparator
                  v-if="step.step !== steps[steps.length - 1].step"
                  class="absolute left-[calc(50%+20px)] right-[calc(-50%+10px)] top-5 block h-0.5 shrink-0 rounded-full bg-muted group-data-[state=completed]:bg-primary"
                />

                <StepperTrigger as-child>
                  <Button
                    :variant="
                      state === 'completed' || state === 'active'
                        ? 'default'
                        : 'outline'
                    "
                    size="icon"
                    class="z-10 shrink-0 rounded-full"
                    :class="[
                      state === 'active' &&
                        'ring-2 ring-ring ring-offset-2 ring-offset-background',
                    ]"
                    :disabled="state !== 'completed' && !meta.valid"
                  >
                    <Check v-if="state === 'completed'" class="size-5" />
                    <Circle v-if="state === 'active'" />
                    <Dot v-if="state === 'inactive'" />
                  </Button>
                </StepperTrigger>

                <div class="mt-5 flex flex-col items-center text-center">
                  <StepperTitle
                    :class="[state === 'active' && 'text-primary']"
                    class="text-sm font-semibold transition lg:text-base"
                  >
                    {{ step.title }}
                  </StepperTitle>
                  <StepperDescription
                    :class="[state === 'active' && 'text-primary']"
                    class="sr-only text-xs text-muted-foreground transition md:not-sr-only lg:text-sm"
                  >
                    {{ step.description }}
                  </StepperDescription>
                </div>
              </StepperItem>
            </div>

            <div class="mt-4 flex flex-col gap-4">
              <template v-if="stepIndex === 1">
                <FormField v-slot="{ componentField }" name="title">
                  <FormItem>
                    <FormLabel>Titre</FormLabel>
                    <FormControl>
                      <Input type="text" v-bind="componentField" />
                    </FormControl>
                    <FormMessage />
                  </FormItem>
                </FormField>

                <FormField v-slot="{ componentField }" name="deal_url">
                  <FormItem>
                    <FormLabel>Lien du deal</FormLabel>
                    <FormControl>
                      <Input type="text" v-bind="componentField" />
                    </FormControl>
                    <FormMessage />
                  </FormItem>
                </FormField>

                <FormField v-slot="{ componentField }" name="price">
                  <FormItem>
                    <FormLabel>Prix</FormLabel>
                    <FormControl>
                      <Input
                        placeholder="0,00"
                        type="number"
                        v-bind="componentField"
                      />
                    </FormControl>
                    <FormMessage />
                  </FormItem>
                </FormField>

                <FormField v-slot="{ componentField }" name="original_price">
                  <FormItem>
                    <FormLabel>Prix originel</FormLabel>
                    <FormControl>
                      <Input
                        placeholder="0,00"
                        type="number"
                        v-bind="componentField"
                      />
                    </FormControl>
                    <FormMessage />
                  </FormItem>
                </FormField>

                <FormField v-slot="{ componentField }" name="promo_code">
                  <FormItem>
                    <FormLabel>Lien du deal</FormLabel>
                    <FormControl>
                      <Input type="text" v-bind="componentField" />
                    </FormControl>
                    <FormMessage />
                  </FormItem>
                </FormField>
              </template>

              <template v-if="stepIndex === 2">
                <FormField v-slot="{ componentField }" name="images[]">
                  <FormItem>
                    <FormLabel>Images</FormLabel>
                    <FormControl>
                      <Input type="file" multiple v-bind="componentField" />
                    </FormControl>
                    <FormMessage />
                  </FormItem>
                </FormField>
              </template>

              <template v-if="stepIndex === 3">
                <FormField v-slot="{ componentField }" name="favoriteDrink">
                  <FormItem>
                    <FormLabel>Drink</FormLabel>

                    <Select v-bind="componentField">
                      <FormControl>
                        <SelectTrigger>
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
                    <FormMessage />
                  </FormItem>
                </FormField>
              </template>
            </div>

            <div class="mt-4 flex items-center justify-between">
              <Button
                :disabled="isPrevDisabled"
                variant="outline"
                size="sm"
                @click="prevStep()"
              >
                Retour
              </Button>
              <div class="flex items-center gap-3">
                <Button
                  v-if="stepIndex !== 3"
                  :type="meta.valid ? 'button' : 'submit'"
                  :disabled="isNextDisabled"
                  size="sm"
                  @click="meta.valid && nextStep()"
                >
                  Suivant
                </Button>
                <Button v-if="stepIndex === 3" size="sm" type="submit">
                  Publier
                </Button>
              </div>
            </div>
          </form>
        </Stepper>
      </Form>
    </Wrapper>
  </div>
</template>
