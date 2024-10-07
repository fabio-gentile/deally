<script setup lang="ts">
import { useForm } from "@inertiajs/vue3"
import { Deal } from "@/types/model/deal"
import Button from "@/Components/ui/button/Button.vue"
import Label from "@/Components/ui/label/Label.vue"
import Textarea from "@/Components/ui/textarea/Textarea.vue"
import { SendHorizonal } from "lucide-vue-next"

const emit = defineEmits(["submitted"])

const { deal, comment } = defineProps<{
  deal: Deal
  comment?: Comment | null
}>()

const form = useForm({
  content: "",
  deal_id: deal.id,
  parent_id: comment ? comment.id : null,
})

const submitForm = () => {
  form.post(route("deals.comments.store", { slug: deal.slug }), {
    preserveScroll: true,
    onSuccess: () => {
      form.content = ""
      emit("submitted")
    },
  })
}
</script>
<template>
  <form @submit.prevent="submitForm" class="flex gap-4">
    <img
      src="/images/avatar.jpg"
      :alt="'Avatar de ' + deal.user.name"
      class="avatar h-[52px] rounded-full object-contain"
    />
    <div class="relative grow">
      <Label for="content" value="Content" />
      <Textarea
        placeholder="Écrivez votre commentaire..."
        v-model="form.content"
        type="text"
        class="min-h-[12rem] resize-none"
        minlength="1"
      />
      <Button
        :disabled="!form.content"
        class="absolute bottom-2 right-2"
        type="submit"
      >
        <SendHorizonal class="mr-2" />
        Envoyer
      </Button>
    </div>
  </form>
</template>
