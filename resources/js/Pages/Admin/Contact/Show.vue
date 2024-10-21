<script setup lang="ts">
import AdminTitle from "@/Components/Admin/AdminTitle.vue"
import { Button } from "@/Components/ui/button"
import Breadcrumb from "@/Components/Admin/Breadcrumb.vue"
import { router } from "@inertiajs/vue3"
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
import { Contact } from "@/types/model/contact"
import { MailPlus } from "lucide-vue-next"

const props = defineProps<{
  contact: Contact
}>()

const submit = () => {
  router.delete(route("admin.contacts.destroy", props.contact.id))
}
</script>

<template>
  <AdminTitle :title="'Message de ' + contact.email" />
  <Breadcrumb
    :breadcrumbs="[
      { label: 'Tableau de bord', route: 'admin.dashboard', active: false },
      { label: 'Contacts', route: 'admin.contacts.list', active: false },
      {
        label: 'Message de ' + contact.email,
        route: 'admin.contacts.show',
        params: { id: contact.id },
        active: true,
      },
    ]"
  />
  <div class="grid max-w-xl gap-4">
    <div class="grid gap-3">
      <h3 class="text-xl font-semibold">Nom</h3>
      <p class="text-muted-foreground">{{ contact.name }}</p>
    </div>
    <div class="grid gap-3">
      <h3 class="text-xl font-semibold">Email</h3>
      <p class="text-muted-foreground">{{ contact.email }}</p>
    </div>
    <div class="grid gap-3">
      <h3 class="text-xl font-semibold">Sujet</h3>
      <p class="text-muted-foreground">{{ contact.subject }}</p>
    </div>
    <div class="grid gap-3">
      <h3 class="text-xl font-semibold">Message</h3>
      <p class="text-muted-foreground">{{ contact.message }}</p>
    </div>

    <div class="flex flex-wrap gap-4">
      <a
        class="cursor-pointer"
        :href="`mailto:${contact.email}?subject=${contact.subject}`"
        ><Button variant="outline"
          ><MailPlus class="mr-2 w-4" /> Répondre</Button
        ></a
      >
      <AlertDialog>
        <AlertDialogTrigger
          ><Button variant="destructive" class="w-fit"
            >Supprimer le contact</Button
          ></AlertDialogTrigger
        >
        <AlertDialogContent>
          <AlertDialogHeader>
            <AlertDialogTitle
              >Etes-vous sûr de vouloir supprimer le contact ?
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
  </div>
</template>
