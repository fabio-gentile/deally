<script setup lang="ts">
import { Head, Link, useForm } from "@inertiajs/vue3"
import { Button } from "@/Components/ui/button"
import { Input } from "@/Components/ui/input"
import { Label } from "@/Components/ui/label"
import { Checkbox } from "@/Components/ui/checkbox"
import FormError from "@/Components/FormError.vue"
import Separator from "@/Components/ui/separator/Separator.vue"
import { LockKeyholeOpen } from "lucide-vue-next"
import Wrapper from "@/Components/layout/Wrapper.vue"

const form = useForm({
  email: "",
  password: "",
  remember: false,
})

const submit = () => {
  form.post(route("admin.login.store"), {
    onFinish: () => {
      form.reset("password")
    },
  })
}
</script>
<template>
  <Head>
    <title>Connexion</title>
  </Head>
  <div class="grid w-full grid-cols-1 place-items-center">
    <Wrapper>
      <form
        @submit.prevent="submit"
        class="mx-auto w-fit overflow-hidden rounded-lg border bg-white p-4 dark:bg-primary-foreground"
      >
        <div class="flex items-center justify-center">
          <div class="mx-auto grid w-[350px] gap-6">
            <div class="grid gap-2">
              <h1 class="text-3xl font-bold">Se connecter</h1>
              <p class="text-balance text-muted-foreground">
                Pour accéder à votre compte à l'administration Deally.
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
              <div class="grid gap-2">
                <div class="flex items-center">
                  <Label for="password">Mot de passe</Label>
                </div>
                <Input
                  id="password"
                  type="password"
                  v-model="form.password"
                  required
                  autocomplete="current-password"
                />
                <FormError :message="form.errors.password" />
              </div>
              <div class="flex items-center space-x-2">
                <Checkbox
                  id="remember"
                  name="remember"
                  v-model:checked="form.remember"
                />
                <Label
                  for="remember"
                  class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                >
                  Se souvenir
                </Label>
              </div>
              <Button
                type="submit"
                class="w-full"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
              >
                Se connecter
              </Button>
            </div>
            <div class="text-center text-sm">
              <Link :href="route('home.index')" class="underline">
                Revenir au site web.
              </Link>
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
