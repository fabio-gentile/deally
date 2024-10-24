<script setup lang="ts">
import { Head, Link, useForm } from "@inertiajs/vue3"
import { Input } from "@/Components/ui/input"
import { Label } from "@/Components/ui/label"
import FormError from "@/Components/FormError.vue"
import { Button } from "@/Components/ui/button"
import Wrapper from "@/Components/layout/Wrapper.vue"

defineProps<{
  status?: string
}>()

const form = useForm({
  email: "",
})

const submit = () => {
  form.post(route("password.email"), {
    onFinish: () => {
      form.reset("email")
    },
  })
}
</script>

<template>
  <Head>
    <title>Mot de passe oublié</title>
  </Head>
  <div class="grid w-full grid-cols-1 place-items-center py-8">
    <Wrapper>
      <form
        @submit.prevent="submit"
        class="mx-auto w-fit overflow-hidden rounded-lg border bg-white p-4 dark:bg-primary-foreground"
      >
        <div class="flex items-center justify-center">
          <div class="mx-auto grid w-[350px] gap-6">
            <div class="grid gap-4">
              <h1 class="text-3xl font-bold">Mot de passe oublié</h1>
              <p class="text-muted-foreground">
                Indiquez votre adresse e-mail et nous vous enverrons un lien de
                réinitialisation du mot de passe par e-mail qui vous permettra
                d'en choisir un nouveau.
              </p>
              <p
                v-if="status"
                class="text-sm font-semibold text-muted-foreground text-success"
              >
                {{ status }}
              </p>
            </div>
            <div class="grid gap-4">
              <div class="grid gap-2">
                <Label for="email">Email</Label>
                <Input
                  id="email"
                  type="email"
                  autocomplete="email"
                  v-model="form.email"
                  required
                />
                <FormError :message="form.errors.email" />
              </div>
              <Button
                type="submit"
                class="w-full"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
              >
                Obtenir un lien de réinitialisation
              </Button>
            </div>
            <div class="text-center text-sm">
              Revenir à la page de
              <Link :href="route('login')" class="underline"> connexion </Link>
            </div>
          </div>
        </div>
      </form>
    </Wrapper>
  </div>
</template>
