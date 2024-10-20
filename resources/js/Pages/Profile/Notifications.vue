<script setup lang="ts">
import UserProfile from "@/Pages/Profile/Partials/UserProfile.vue"
import Wrapper from "@/Components/layout/Wrapper.vue"
import ProfileLayout from "@/Components/layout/ProfileLayout.vue"
import { Head, useForm } from "@inertiajs/vue3"
import { User } from "@/types/model/user"
import { Switch } from "@/Components/ui/switch"
import { Label } from "@/Components/ui/label"
import { Button } from "@/Components/ui/button"

defineOptions({ layout: ProfileLayout })
const props = defineProps<{
  user: User
  dealsCount: number
  discussionsCount: number
  commentsCount: number
  isCurrentUser: boolean
  newsletter: {
    newsletter: boolean
    blog_notification: boolean
  }
}>()

const form = useForm({
  newsletter: props.newsletter?.newsletter ?? false,
  blog_notification: props.newsletter?.blog_notification ?? false,
  _method: "patch",
})

const submit = () => {
  form.post(route("newsletter.update"), {
    preserveScroll: true,
  })
}
</script>
<template>
  <Head title="Newsletter" />
  <UserProfile
    :user="user"
    :deals-count="dealsCount"
    :discussions-count="discussionsCount"
    :comments-count="commentsCount"
    :is-current-user="isCurrentUser"
  />
  <Wrapper class="!max-w-[calc(800px+64px)] py-8">
    <div class="w-full space-y-6">
      <div>
        <h3 class="mb-4 text-lg font-medium">Notifications email</h3>

        <div class="space-y-4">
          <div>
            <div
              class="flex flex-row items-center justify-between rounded-lg border bg-white p-4"
            >
              <div class="space-y-0.5">
                <Label class="text-base">Newsletter</Label>
                <p class="text-sm text-muted-foreground">
                  Recevez un email quotidiennement sur récapitulant les derniers
                  deals postés sur le site.
                </p>
              </div>
              <Switch v-model:checked="form.newsletter" />
            </div>
          </div>
          <div>
            <div
              class="flex flex-row items-center justify-between rounded-lg border bg-white p-4"
            >
              <div class="space-y-0.5">
                <Label class="text-base"> Article </Label>
                <p class="text-sm text-muted-foreground">
                  Recevez un email à chaque fois qu'un nouvel article est publié
                  par l'équipe Deally.
                </p>
              </div>
              <Switch v-model:checked="form.blog_notification" />
            </div>
          </div>
        </div>
      </div>
      <Button :disabled="form.processing" @click="submit" type="submit">
        Sauvegarder
      </Button>
    </div>
  </Wrapper>
</template>
