<script setup lang="ts">
import { Head, useForm } from "@inertiajs/vue3"
import Wrapper from "@/Components/layout/Wrapper.vue"
import { Label } from "@/Components/ui/label"
import { Input } from "@/Components/ui/input"
import { Button } from "@/Components/ui/button"
import FormError from "@/Components/FormError.vue"

const props = defineProps<{
  email: string
  token: string
}>()

const form = useForm({
  token: props.token,
  email: props.email,
  password: "",
  password_confirmation: "",
})

const submit = () => {
  form.post(route("password.store"), {
    onFinish: () => {
      form.reset("password", "password_confirmation")
    },
  })
}
</script>

<template>
  <Head title="Réinitialiser le mot de passe" />
  <div class="my-auto grid w-full grid-cols-1 place-items-center py-8">
    <Wrapper>
      <form
        @submit.prevent="submit"
        class="mx-auto w-fit overflow-hidden rounded-lg border bg-white p-4 dark:bg-primary-foreground"
      >
        <div class="flex items-center justify-center">
          <div class="mx-auto grid max-w-[350px] gap-6">
            <div class="grid gap-2">
              <h1 class="text-3xl font-bold">Réinitialiser le mot de passe</h1>
              <p class="text-muted-foreground">
                Entrez votre nouveau mot de passe pour continuer.
              </p>
            </div>
            <div class="grid gap-4">
              <div class="grid gap-2">
                <Label for="password">Mot de passe</Label>
                <Input
                  id="password"
                  type="password"
                  class="mt-1 block w-full"
                  v-model="form.password"
                  required
                  autocomplete="new-password"
                />
                <FormError :message="form.errors.password" />
              </div>
              <div class="grid gap-2">
                <Label for="password_confirmation"
                  >Confirmer le mot de passe</Label
                >
                <Input
                  id="password_confirmation"
                  type="password"
                  class="mt-1 block w-full"
                  v-model="form.password_confirmation"
                  required
                  autocomplete="new-password"
                />
                <FormError
                  class="mt-2"
                  :message="form.errors.password_confirmation"
                />
              </div>
              <Button
                type="submit"
                class="w-full"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
              >
                Réinitialiser le mot de passe
              </Button>
            </div>
          </div>
        </div>
      </form>
    </Wrapper>
  </div>
</template>
