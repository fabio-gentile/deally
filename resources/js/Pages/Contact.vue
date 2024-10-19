<script setup lang="ts">
import { Head, useForm } from "@inertiajs/vue3"
import Wrapper from "@/Components/layout/Wrapper.vue"
import Input from "@/Components/ui/input/Input.vue"
import FormError from "@/Components/FormError.vue"
import Label from "@/Components/ui/label/Label.vue"
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectLabel,
  SelectTrigger,
  SelectValue,
} from "@/Components/ui/select"
import { Textarea } from "@/Components/ui/textarea"
import { Button } from "@/Components/ui/button"
import GoogleReCaptchaV3 from "@/Components/googlerecaptchav3/GoogleReCaptchaV3.vue"
import { ref } from "vue"
import { Loader2 } from "lucide-vue-next"

const props = defineProps<{
  recaptchaKey: string
}>()

const subjects = [
  "Proposer une amélioration",
  "Signaler un contenu inapproprié",
  "Offre commerciale",
  "Signaler un bug",
  "Problème de connexion",
  "Autre",
]

const form = useForm({
  subject: "",
  name: "",
  email: "",
  message: "",
  recaptcha: "",
  website: "",
})

const captcha = ref(null)
const submit = async () => {
  form.post(route("contact.store"), {
    preserveScroll: true,
    onSuccess: () => form.reset(),
  })

  if (captcha.value) {
    await captcha.value.execute()
  }
}
</script>
<template>
  <div class="w-full py-8">
    <Head title="Contact" />
    <Wrapper class="!max-w-[800px]">
      <h1 class="mb-4 text-2xl font-semibold">Contactez-nous</h1>
      <p class="mb-3 text-muted-foreground">
        Pour toute demande, question ou signalement, n'hésitez pas à remplir le
        formulaire ci-dessous.
      </p>
      <p class="mb-6 text-muted-foreground">
        L'équipe Deally est à votre écoute et se fera un plaisir de vous
        répondre dans les plus brefs délais. Le temps de réponse peut varier
        entre quelques heures et 2 jours, en fonction de la nature de votre
        demande.
      </p>
      <form @submit.prevent="submit" class="flex flex-col gap-4">
        <div class="flex flex-col gap-3">
          <Label for="name">Nom</Label>
          <Input
            required
            v-model="form.name"
            type="text"
            id="name"
            name="name"
            autocomplete="name"
            placeholder="Votre nom ou pseudo"
          />
          <FormError :message="form.errors.name" />
        </div>
        <div class="flex flex-col gap-3">
          <Label for="name">Email</Label>
          <Input
            required
            v-model="form.email"
            type="email"
            id="email"
            name="email"
            autocomplete="email"
            placeholder="votre@email.fr"
          />
          <FormError :message="form.errors.email" />
        </div>
        <div class="flex flex-col gap-3">
          <Label for="name">Sujet</Label>
          <Select required v-model="form.subject">
            <SelectTrigger>
              <SelectValue placeholder="Sélectionner un sujet" />
            </SelectTrigger>
            <SelectContent>
              <SelectGroup>
                <SelectLabel class="sr-only">Sujet</SelectLabel>
                <SelectItem
                  v-for="(subject, index) in subjects"
                  :key="index"
                  :value="subject"
                >
                  {{ subject }}
                </SelectItem>
              </SelectGroup>
            </SelectContent>
          </Select>
          <FormError :message="form.errors.subject" />
        </div>

        <div class="flex flex-col gap-3">
          <Label for="name">Message</Label>
          <Textarea
            required
            class="min-h-[150px]"
            v-model="form.message"
            id="message"
            name="message"
            autocomplete="message"
            placeholder="Développez votre message..."
          />
          <FormError :message="form.errors.message" />
        </div>

        <!-- Honeypot -->
        <input
          type="text"
          v-model="form.website"
          name="website"
          style="display: none"
        />

        <!-- Google Recaptcha Widget-->
        <google-re-captcha-v3
          class="hidden"
          v-model="form.recaptcha"
          ref="captcha"
          :site-key="recaptchaKey"
          id="contact_us_id"
          inline
          action="contact_us"
        ></google-re-captcha-v3>
        <div class="text-sm text-muted-foreground">
          Ce site est protégé par reCAPTCHA et la
          <a
            class="text-primary underline"
            target="_blank"
            href="https://policies.google.com/privacy"
            >politique de confidentialité</a
          >
          et les
          <a
            class="text-primary underline"
            target="_blank"
            href="https://policies.google.com/terms"
            >conditions d'utilisation</a
          >
          de Google s'appliquent.
        </div>
        <Button :disabled="form.processing" class="w-fit" type="submit">
          <Loader2 v-if="form.processing" class="mr-2 h-5 w-5 animate-spin" />
          Envoyer
        </Button>
      </form>
    </Wrapper>
  </div>
</template>
