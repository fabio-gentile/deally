<script setup lang="ts">
import { useForm } from "@inertiajs/vue3"
import Button from "@/Components/ui/button/Button.vue"
import Label from "@/Components/ui/label/Label.vue"
import Textarea from "@/Components/ui/textarea/Textarea.vue"
import { SendHorizonal } from "lucide-vue-next"
import { Deal } from "@/types/model/deal"
import { Discussion } from "@/types/model/discussion"
import { Blog } from "@/types/model/blog"

const emit = defineEmits(["submitted"])

const { contentType, deal, discussion, blog, comment, answerTo } = defineProps<{
  contentType: "deal" | "discussion" | "blog"
  deal?: Deal | null
  discussion?: Discussion | null
  blog?: Blog | null
  comment?: Comment | null
  answerTo?: number | null
}>()

const form = useForm({
  content: "",
  content_id: deal?.id || discussion?.id || blog?.id,
  parent_id: comment ? comment.id : null,
  answer_to: answerTo,
})

const submitForm = () => {
  let routeName = ""
  let slug = ""

  if (contentType === "deal" && deal) {
    routeName = "deals.comments.store"
    slug = deal.slug
  } else if (contentType === "discussion" && discussion) {
    routeName = "discussions.comments.store"
    slug = discussion.slug
  } else if (contentType === "blog" && blog) {
    routeName = "blog.comments.store"
    slug = blog.slug
  }

  form.post(route(routeName, { slug }), {
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
      v-if="$page.props.auth?.user?.avatar"
      :src="'/storage/uploads/avatar/' + $page.props.auth.user.avatar"
      :alt="'Avatar de ' + $page.props.auth.user.avatar"
      class="hidden h-[52px] w-[52px] rounded-full object-cover sm:block"
    />
    <img
      v-else-if="$page.props.auth?.user"
      :src="`https://ui-avatars.com/api/?size=64&name=${$page.props.auth.user.name}`"
      :alt="'Avatar de ' + $page.props.auth.user.name"
      class="hidden h-[52px] w-[52px] rounded-full object-cover sm:block"
    />
    <img
      v-else
      src="https://ui-avatars.com/api/?size=64&name=deally"
      alt="'Avatar de Deally'"
      class="hidden h-[52px] w-[52px] rounded-full object-cover sm:block"
    />
    <div class="relative grow">
      <Label for="content" value="Contenu" />
      <Textarea
        id="content"
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
