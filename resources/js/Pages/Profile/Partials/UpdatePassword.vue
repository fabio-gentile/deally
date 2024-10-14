<script setup lang="ts">
import Label from "@/Components/ui/label/Label.vue"
import Button from "@/Components/ui/button/Button.vue"
import Input from "@/Components/ui/input/Input.vue"
import { ref } from "vue"
import { useForm } from "@inertiajs/vue3"
import FormError from "@/Components/FormError.vue"

const passwordInput = ref<HTMLInputElement | null>(null)

const form = useForm({
  password: "",
  password_confirmation: "",
})

const updatePassword = () => {
  form.put(route("password.update"), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset()
    },
    onError: () => {
      if (form.errors.password) {
        form.reset("password", "password_confirmation")
        passwordInput.value?.focus()
      }
    },
  })
}
</script>
<template>
  <div>
    <h2 class="text-xl font-semibold">Compte</h2>
    <p class="mt-3 text-sm text-muted-foreground">
      Modifier les paramètres de votre compte.
    </p>
  </div>
  <div class="flex flex-col gap-3">
    <Label for="password">Nouveau mot de passe</Label>
    <Input
      v-model="form.password"
      type="password"
      id="password"
      name="password"
      autocomplete="new-password"
    />
    <FormError :message="form.errors.password" />
    <p class="text-sm text-muted-foreground">
      Assurez-vous que votre compte utilise un mot de passe long et aléatoire
      pour rester sécurisé.
    </p>
  </div>
  <div class="flex flex-col gap-3">
    <Label for="password_confirmation">Confirmer le nouveau mot de passe</Label>
    <Input
      v-model="form.password_confirmation"
      type="password"
      id="password_confirmation"
      name="password_confirmation"
      autocomplete="new-password"
    />
    <FormError :message="form.errors.password_confirmation" />
  </div>
  <Button @click="updatePassword" :disabled="form.processing" class="w-fit"
    >Mettre à jour</Button
  >
</template>
