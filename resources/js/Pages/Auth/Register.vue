<script setup lang="ts">
import { Head, Link, useForm } from "@inertiajs/vue3"
import { Input } from "@/Components/ui/input"
import { Label } from "@/Components/ui/label"
import FormError from "@/Components/FormError.vue"
import { Button } from "@/Components/ui/button"
import Wrapper from "@/Pages/Layout/Wrapper.vue"

const form = useForm({
  name: "",
  email: "",
  password: "",
  password_confirmation: "",
})

const submit = () => {
  form.post(route("register"), {
    onFinish: () => {
      form.reset("password", "password_confirmation")
    },
  })
}
</script>

<template>
  <Head>
    <title>Créer un compte</title>
  </Head>
  <div class="grid w-full grid-cols-1 place-items-center">
    <Wrapper>
      <form
        @submit.prevent="submit"
        class="mx-auto w-fit overflow-hidden rounded-lg border bg-white p-4 dark:bg-primary-foreground"
      >
        <div class="flex items-center justify-center">
          <div class="mx-auto grid max-w-[350px] gap-6">
            <div class="grid gap-2">
              <h1 class="text-3xl font-bold">Créer un compte</h1>
              <p class="text-balance text-muted-foreground">
                Créer un compte pour accéder à toutes les fonctionnalités sur
                Deally.
              </p>
            </div>
            <div class="grid gap-4">
              <div class="grid gap-2">
                <Label for="username">Nom d'utilisateur</Label>
                <Input
                  id="username"
                  type="text"
                  class="mt-1 block w-full"
                  v-model="form.name"
                  required
                  autofocus
                  autocomplete="name"
                />
                <FormError :message="form.errors.name" />
              </div>
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
                Créer un compte
              </Button>
            </div>
            <div>
              <div class="text-center text-sm">
                Vous avez déjà un compte ?
                <Link :href="route('login')" class="underline">
                  Se connecter
                </Link>
              </div>
              <!-- TODO: Add redirection -->
              <div class="mt-3 text-center text-xs text-muted-foreground">
                En créant un compte, vous acceptez nos
                <Link :href="route('login')" class="underline">
                  conditions d'utilisation
                </Link>
                et notre
                <Link :href="route('login')" class="underline">
                  politique de confidentialité
                </Link>
                .
              </div>
            </div>
          </div>
        </div>
      </form>
    </Wrapper>

    <!--    <div class="hidden h-full w-full bg-primary-foreground lg:block">-->
    <!--      <img-->
    <!--        src="/placeholder.svg"-->
    <!--        alt="Image"-->
    <!--        width="1920"-->
    <!--        height="1080"-->
    <!--        class="h-full w-full object-cover dark:brightness-[0.2] dark:grayscale"-->
    <!--      />-->
    <!--      <LockKeyholeOpen class="m-auto h-64 w-64 object-contain text-primary" />-->
    <!--    </div>-->
  </div>
</template>
