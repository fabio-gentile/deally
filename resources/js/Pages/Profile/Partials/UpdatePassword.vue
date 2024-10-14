<script setup lang="ts">
import Label from "@/Components/ui/label/Label.vue"
import Button from "@/Components/ui/button/Button.vue"
import Input from "@/Components/ui/input/Input.vue"
</script>
<template>
  <div>
    <h2 class="text-xl font-semibold">Compte</h2>
    <p class="mt-3 text-sm text-muted-foreground">
      Modifier les paramètres de votre compte.
    </p>
  </div>
  <div class="flex flex-col gap-3">
    <Label for="current_password">Mot de passe actuel</Label>
    <Input type="text" id="current_password" name="current_password" />
  </div>
  <div class="flex flex-col gap-3">
    <Label for="password">Nouveau mot de passe</Label>
    <Input type="text" id="password" name="password" />
    <p class="text-sm text-muted-foreground">
      Assurez-vous que votre compte utilise un mot de passe long et aléatoire
      pour rester sécurisé.
    </p>
  </div>
  <div class="flex flex-col gap-3">
    <Label for="password_confirmation">Confirmer le nouveau mot de passe</Label>
    <Input
      type="text"
      id="password_confirmation"
      name="password_confirmation"
    />
  </div>
  <Button class="w-fit">Mettre à jour</Button>
</template>
