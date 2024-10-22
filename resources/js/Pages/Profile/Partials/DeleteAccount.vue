<script setup lang="ts">
import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
  AlertDialogTrigger,
} from "@/Components/ui/alert-dialog"
import { useForm } from "@inertiajs/vue3"
import Label from "@/Components/ui/label/Label.vue"
import Button from "@/Components/ui/button/Button.vue"
import Input from "@/Components/ui/input/Input.vue"

const form = useForm({
  confirm: "",
  textConfirmDeleteAccount: "Supprimer mon compte",
})

const deleteAccount = () => {
  form.delete(route("profile.destroy"))
}
</script>
<template>
  <div>
    <h2 class="mt-6 text-xl font-semibold">Supprimez votre compte</h2>
    <p class="mt-3 text-sm text-muted-foreground">
      Une fois votre compte supprimé, toutes ses données seront définitivement
      supprimées. Cette action est irréversible. Vous pourrez vous réinscrire à
      l'avenir.
    </p>
  </div>
  <AlertDialog @update:open="form.confirm = ''">
    <AlertDialogTrigger class="mr-auto"
      ><Button variant="destructive" class="w-fit"
        >Supprimer votre compte</Button
      ></AlertDialogTrigger
    >
    <AlertDialogContent>
      <AlertDialogHeader>
        <AlertDialogTitle
          >Etes-vous sûr de vouloir supprimer votre compte ?
        </AlertDialogTitle>
        <AlertDialogDescription>
          <p class="my-2">
            Une fois votre compte supprimé, toutes ses données seront
            définitivement supprimées.
          </p>
          <p>
            Pour confirmer, écrivez
            <span class="font-semibold"
              >"{{ form.textConfirmDeleteAccount }}"</span
            >
            ci-dessous.
          </p>
        </AlertDialogDescription>

        <AlertDialogDescription>
          <Label for="confirm" class="sr-only"
            >Confirmer la suppression du compte</Label
          >
          <Input
            v-model="form.confirm"
            type="text"
            id="confirm"
            name="confirm"
            class="mt-3"
          />
        </AlertDialogDescription>
      </AlertDialogHeader>
      <AlertDialogFooter>
        <AlertDialogCancel>Annuler</AlertDialogCancel>
        <AlertDialogAction
          as-child
          class="bg-destructive text-inherit hover:bg-destructive/80"
          @click="deleteAccount"
          ><Button
            :disabled="
              form.confirm.toLocaleLowerCase() !==
              form.textConfirmDeleteAccount.toLocaleLowerCase()
            "
            >Supprimer votre compte</Button
          ></AlertDialogAction
        >
      </AlertDialogFooter>
    </AlertDialogContent>
  </AlertDialog>
</template>
