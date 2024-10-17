<script setup lang="ts" xmlns="http://www.w3.org/1999/html">
import { Flag } from "lucide-vue-next"
import { Link, router, useForm } from "@inertiajs/vue3"
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from "@/Components/ui/dialog"
import { Button } from "@/Components/ui/button"
import { RadioGroup, RadioGroupItem } from "@/Components/ui/radio-group"
import { Label } from "@/Components/ui/label"
import Textarea from "@/Components/ui/textarea/Textarea.vue"
import FormError from "@/Components/FormError.vue"
import { dialogState } from "@/lib/dialog"

const props = defineProps<{
  id: number
  type: "deal" | "discussion" | "comment_deal" | "comment_discussion"
}>()

interface Reason {
  value: string
  label: string
}

const dealReasons: Reason[] = [
  { value: "spam", label: "Spam" },
  { value: "inappropriate", label: "Inapproprié / Injurieux / Vulgaire" },
  { value: "missing_information", label: "Informations manquantes" },
  { value: "wrong_category", label: "Mauvaise catégorie" },
  { value: "other", label: "Autre" },
]
const commentReasons: Reason[] = [
  { value: "spam", label: "Spam" },
  { value: "inappropriate", label: "Inapproprié / Injurieux / Vulgaire" },
  { value: "other", label: "Autre" },
]
const discussionReasons: Reason[] = [
  { value: "spam", label: "Spam" },
  { value: "inappropriate", label: "Inapproprié / Injurieux / Vulgaire" },
  { value: "wrong_category", label: "Mauvaise catégorie" },
  { value: "other", label: "Autre" },
]

const form = useForm({
  reason: "",
  description: "",
  type: props.type,
  id: props.id,
})

const submit = () => {
  router.post(
    route("report.store"),
    {
      type: form.type,
      id: form.id,
      reason: form.reason,
      description: form.description,
    },
    {
      preserveState: true,
      preserveScroll: true,
      onSuccess: () => {
        form.reason = ""
        form.description = ""
        closeDialog()
      },
    }
  )
}

const [isOpen, closeDialog] = dialogState()
</script>
<template>
  <Dialog v-if="!$page.props.auth.user?.id">
    <DialogTrigger as-child>
      <Flag class="h-5 w-5 cursor-pointer object-contain" />
    </DialogTrigger>
    <DialogContent class="sm:max-w-[425px]">
      <DialogHeader>
        <DialogTitle>Signaler</DialogTitle>
        <DialogDescription>
          Pour signaler un contenu, vous devez être connecté.
        </DialogDescription>
      </DialogHeader>
      <DialogFooter>
        <Link :href="route('login')">
          <Button type="submit">Se connecter</Button>
        </Link>
      </DialogFooter>
    </DialogContent>
  </Dialog>
  <Dialog v-else v-model:open="isOpen">
    <DialogTrigger>
      <Flag class="h-5 w-5 cursor-pointer object-contain" />
    </DialogTrigger>
    <DialogContent>
      <DialogHeader>
        <DialogTitle>Signalement</DialogTitle>
        <DialogDescription>
          <div class="mt-4">
            <Label for="reason">Raison</Label>
            <!-- Reason Deal -->
            <RadioGroup
              v-if="props.type === 'deal'"
              v-model="form.reason"
              id="reason"
              default-value="spam"
              class="mt-2 grid gap-3"
              required
            >
              <div
                v-for="(reason, index) in dealReasons"
                :key="index"
                class="flex items-center space-x-2"
              >
                <RadioGroupItem :name="reason.value" :value="reason.value" />
                <Label :for="reason.value">{{ reason.label }}</Label>
              </div>
              <FormError :message="form.errors.reason" />
            </RadioGroup>
            <!-- Reason Discussion -->
            <RadioGroup
              v-if="props.type === 'discussion'"
              v-model="form.reason"
              id="reason"
              default-value="spam"
              class="mt-2 grid gap-3"
              required
            >
              <div
                v-for="(reason, index) in discussionReasons"
                :key="index"
                class="flex items-center space-x-2"
              >
                <RadioGroupItem :name="reason.value" :value="reason.value" />
                <Label :for="reason.value">{{ reason.label }}</Label>
              </div>
              <FormError :message="form.errors.reason" />
            </RadioGroup>
            <!-- Reason Comment -->
            <RadioGroup
              v-if="
                props.type === 'comment_deal' ||
                props.type === 'comment_discussion'
              "
              v-model="form.reason"
              id="reason"
              default-value="spam"
              class="mt-2 grid gap-3"
              required
            >
              <div
                v-for="(reason, index) in commentReasons"
                :key="index"
                class="flex items-center space-x-2"
              >
                <RadioGroupItem :name="reason.value" :value="reason.value" />
                <Label :for="reason.value">{{ reason.label }}</Label>
              </div>
              <FormError :message="form.errors.reason" />
            </RadioGroup>
          </div>
          <div class="mt-4">
            <Label for="description">Description (opt.)</Label>
            <Textarea
              v-model="form.description"
              id="description"
              type="text"
              class="mt-2 min-h-[100px]"
            />
            <FormError :message="form.errors.description" />
          </div>
        </DialogDescription>
      </DialogHeader>

      <DialogFooter class="sm:justify-start">
        <Button @click="submit" :disabled="!form.reason" type="button">
          Signaler
        </Button>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>
