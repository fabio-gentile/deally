<script setup lang="ts">
import { Head, Link, useForm } from "@inertiajs/vue3"
import { Button } from "@/Components/ui/button"
import { Input } from "@/Components/ui/input"
import { Label } from "@/Components/ui/label"
import { Checkbox } from "@/Components/ui/checkbox"
import FormError from "@/Components/FormError.vue"

defineProps<{
  canResetPassword?: boolean
  status?: string
}>()

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
  <form
    @submit.prevent="submit"
    class="w-full lg:grid lg:min-h-[600px] lg:grid-cols-2 xl:min-h-[800px]"
  >
    <div class="flex items-center justify-center py-12">
      <div class="mx-auto grid w-[350px] gap-6">
        <div class="grid gap-2 text-center">
          <h1 class="text-3xl font-bold">Login</h1>
          <p class="text-balance text-muted-foreground">
            Enter your email below to login to your account
          </p>
        </div>
        <div class="grid gap-4">
          <div class="grid gap-2">
            <Label for="email">Email</Label>
            <Input
              id="email"
              type="email"
              placeholder="m@example.com"
              v-model="form.email"
              required
            />
            <FormError :message="form.errors.email" />
          </div>
          <div class="grid gap-2">
            <div class="flex items-center">
              <Label for="password">Password</Label>
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
              Forgot your password?
            </a>
          </div>
          <div class="mt-4 flex items-center space-x-2">
            <Checkbox
              id="remember"
              name="remember"
              v-model:checked="form.remember"
            />
            <label
              for="remember"
              class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
            >
              Remember me
            </label>
          </div>
          <Button
            type="submit"
            class="w-full"
            :class="{ 'opacity-25': form.processing }"
            :disabled="form.processing"
          >
            Login
          </Button>
          <Button variant="outline" class="w-full"> Login with Google </Button>
        </div>
        <div class="mt-4 text-center text-sm">
          Don't have an account?
          <a href="#" class="underline"> Sign up </a>
        </div>
      </div>
    </div>
    <div class="hidden bg-muted lg:block">
      <img
        src="/placeholder.svg"
        alt="Image"
        width="1920"
        height="1080"
        class="h-full w-full object-cover dark:brightness-[0.2] dark:grayscale"
      />
    </div>
  </form>
</template>
