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
})

const submit = () => {
  console.log(form.data())
  // form.post(route("contact.store"), {
  //   preserveScroll: true,
  //   onSuccess: () => form.reset(),
  // })
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
      <div class="flex flex-col gap-4">
        <div class="flex flex-col gap-3">
          <Label for="name">Nom</Label>
          <Input
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
          <Select v-model="form.subject">
            <SelectTrigger v-model="form.subject">
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
            class="min-h-[150px]"
            v-model="form.message"
            id="message"
            name="message"
            autocomplete="message"
            placeholder="Développez votre message..."
          />
          <FormError :message="form.errors.message" />
        </div>
        <Button class="w-fit" @click="submit">Envoyer</Button>
      </div>
    </Wrapper>
  </div>
</template>
