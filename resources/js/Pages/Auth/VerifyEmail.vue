<script setup lang="ts">
import { computed } from "vue"
import { Button } from "@/Components/ui/button"
import { Head, Link, useForm } from "@inertiajs/vue3"
import {
  Card,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from "@/Components/ui/card"

const props = defineProps<{
  status?: string
}>()

const form = useForm({})

const submit = () => {
  form.post(route("verification.send"), {
    onFinish: () => {
      form.reset()
    },
  })
}

const verificationLinkSent = computed(
  () => props.status === "verification-link-sent"
)
</script>

<template>
  <Head title="Vérification email" />
  <form
    @submit.prevent="submit"
    class="my-auto max-w-[350px] p-4 py-8 sm:max-w-[450px]"
  >
    <Card>
      <CardHeader>
        <CardTitle class="mb-4">Vérification email</CardTitle>
        <div v-if="!verificationLinkSent">
          <CardDescription
            >Pour pouvoir accéder à toutes les fonctionnalités de Deally,
            veuillez vérifier votre adresse email en cliquant sur le lien que
            nous venons de vous envoyer par email.
          </CardDescription>
          <CardDescription class="mt-2">
            Si vous n'avez pas reçu l'email, nous vous en enverrons un autre
            avec plaisir.
          </CardDescription>
        </div>
        <CardDescription v-else>
          Un nouveau lien de vérification a été envoyé à l'adresse e-mail que
          vous avez fournie lors de l'inscription.
        </CardDescription>
      </CardHeader>
      <CardContent>
        <Button
          class="w-full"
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing"
        >
          Renvoyer l'email de vérification
        </Button>
      </CardContent>
      <CardFooter>
        <Link
          :href="route('logout')"
          method="post"
          as="button"
          class="ml-auto cursor-pointer"
          ><Button variant="link">Se déconnecter</Button></Link
        >
      </CardFooter>
    </Card>
  </form>
</template>
