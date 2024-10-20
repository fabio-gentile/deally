<script setup lang="ts">
import AdminTitle from "@/Components/Admin/AdminTitle.vue"
import { Button } from "@/Components/ui/button"
import { Label } from "@/Components/ui/label"
import Breadcrumb from "@/Components/Admin/Breadcrumb.vue"
import { router } from "@inertiajs/vue3"
import { Blog } from "@/types/model/blog"
import { Textarea } from "@/Components/ui/textarea"
import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
  AlertDialogTrigger,
} from "@/Components/ui/alert-dialog"

const props = defineProps<{
  comment: {
    id: number
    content: string
    created_at: string
    user: { id: number; name: string }
  }
  blog: Blog
}>()

const submit = () => {
  router.delete(route("admin.blog.comments.destroy", props.comment.id))
}
</script>

<template>
  <AdminTitle :title="'Commentaires de l\'article ' + blog.title" />
  <Breadcrumb
    :breadcrumbs="[
      { label: 'Tableau de bord', route: 'admin.dashboard', active: false },
      { label: 'Blog', route: 'admin.blog.list', active: false },
      {
        label: 'Commentaires du blog ' + blog.title,
        route: 'admin.blog.comments.list',
        params: { id: blog.id },
        active: true,
      },
    ]"
  />
  <div class="grid gap-4">
    <div class="grid gap-2">
      <Label for="name">Contenu du message</Label>
      <Textarea
        v-model="props.comment.content"
        disabled
        id="name"
        type="text"
        autocomplete="name"
        required
      />
    </div>

    <AlertDialog>
      <AlertDialogTrigger class="mr-auto"
        ><Button variant="destructive" class="w-fit"
          >Supprimer le commentaire</Button
        ></AlertDialogTrigger
      >
      <AlertDialogContent>
        <AlertDialogHeader>
          <AlertDialogTitle
            >Etes-vous sûr de vouloir supprimer le commentaire ?
          </AlertDialogTitle>
        </AlertDialogHeader>
        <AlertDialogFooter class="mt-8">
          <AlertDialogCancel>Annuler</AlertDialogCancel>
          <AlertDialogAction
            class="bg-destructive hover:bg-destructive/80"
            @click="submit"
            >Supprimer</AlertDialogAction
          >
        </AlertDialogFooter>
      </AlertDialogContent>
    </AlertDialog>
  </div>
</template>
