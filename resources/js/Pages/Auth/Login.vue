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
  form.post(route("login"), {
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
                Pour accéder à votre compte Deally.
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
                <a
                  :href="route('password.request')"
                  class="ml-auto inline-block text-sm underline"
                >
                  Mot de passe oublié ?
                </a>
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
              <Separator label="OU" class="!dark:bg-primary-foreground" />
              <div>
                <Link :href="route('social.redirect', 'google')">
                  <Button variant="outline" class="w-full">
                    <svg
                      class="mr-2 h-4 w-4"
                      viewBox="-3 0 262 262"
                      xmlns="http://www.w3.org/2000/svg"
                      preserveAspectRatio="xMidYMid"
                    >
                      <path
                        d="M255.878 133.451c0-10.734-.871-18.567-2.756-26.69H130.55v48.448h71.947c-1.45 12.04-9.283 30.172-26.69 42.356l-.244 1.622 38.755 30.023 2.685.268c24.659-22.774 38.875-56.282 38.875-96.027"
                        fill="#4285F4"
                      />
                      <path
                        d="M130.55 261.1c35.248 0 64.839-11.605 86.453-31.622l-41.196-31.913c-11.024 7.688-25.82 13.055-45.257 13.055-34.523 0-63.824-22.773-74.269-54.25l-1.531.13-40.298 31.187-.527 1.465C35.393 231.798 79.49 261.1 130.55 261.1"
                        fill="#34A853"
                      />
                      <path
                        d="M56.281 156.37c-2.756-8.123-4.351-16.827-4.351-25.82 0-8.994 1.595-17.697 4.206-25.82l-.073-1.73L15.26 71.312l-1.335.635C5.077 89.644 0 109.517 0 130.55s5.077 40.905 13.925 58.602l42.356-32.782"
                        fill="#FBBC05"
                      />
                      <path
                        d="M130.55 50.479c24.514 0 41.05 10.589 50.479 19.438l36.844-35.974C195.245 12.91 165.798 0 130.55 0 79.49 0 35.393 29.301 13.925 71.947l42.211 32.783c10.59-31.477 39.891-54.251 74.414-54.251"
                        fill="#EB4335"
                      />
                    </svg>
                    Google
                  </Button>
                </Link>

                <Link href="#">
                  <Button variant="outline" class="mt-2 w-full">
                    <svg
                      class="mr-2 h-4 w-4"
                      height="800px"
                      viewBox="38.657999999999994 12.828 207.085 207.085"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path
                        d="M158.232 219.912v-94.461h31.707l4.747-36.813h-36.454V65.134c0-10.658 2.96-17.922 18.245-17.922l19.494-.009V14.278c-3.373-.447-14.944-1.449-28.406-1.449-28.106 0-47.348 17.155-47.348 48.661v27.149H88.428v36.813h31.788v94.461l38.016-.001z"
                        fill="#3c5a9a"
                      />
                    </svg>
                    Facebook
                  </Button>
                </Link>
              </div>
            </div>
            <div class="text-center text-sm">
              Vous n'avez pas de compte ?
              <Link :href="route('register')" class="underline">
                Créer un compte
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
