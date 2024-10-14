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
import Label from "@/Components/ui/label/Label.vue"
import Button from "@/Components/ui/button/Button.vue"
import Input from "@/Components/ui/input/Input.vue"
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
  <AlertDialog>
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
          Une fois votre compte supprimé, toutes ses données seront
          définitivement supprimées. Veuillez saisir votre mot de passe pour
          confirmer que vous souhaitez supprimer définitivement votre compte.
        </AlertDialogDescription>

        <AlertDialogDescription>
          <Label for="password" class="sr-only">Mot de passe</Label>
          <Input
            placeholder="Mot de passe"
            type="text"
            id="password"
            name="password"
            class="mt-3"
          />
        </AlertDialogDescription>
      </AlertDialogHeader>
      <AlertDialogFooter>
        <AlertDialogCancel>Annuler</AlertDialogCancel>
        <AlertDialogAction
          class="bg-destructive text-inherit hover:bg-destructive/80"
          >Supprimer votre compte</AlertDialogAction
        >
      </AlertDialogFooter>
    </AlertDialogContent>
  </AlertDialog>
</template>
