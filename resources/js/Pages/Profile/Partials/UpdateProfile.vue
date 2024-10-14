<script setup lang="ts">
import Label from "@/Components/ui/label/Label.vue"
import Button from "@/Components/ui/button/Button.vue"
import Input from "@/Components/ui/input/Input.vue"
import Textarea from "@/Components/ui/textarea/Textarea.vue"
import { useForm } from "laravel-precognition-vue-inertia"
import { usePage } from "@inertiajs/vue3"
import { User } from "@/types/model/user"
import FormError from "@/Components/FormError.vue"
import { timeAgo } from "@/lib/time-ago"
import { ref } from "vue"

const user: User = usePage().props.auth.user
const nameUpdatedAt = new Date(user.name_updated_at)
let isNameChangeable =
  nameUpdatedAt.getTime() + 30 * 24 * 60 * 60 * 1000 < Date.now() ||
  !user.name_updated_at

const form = useForm(
  "post",
  route("profile.update.profile.informations", { user: user.name }),
  {
    name: user.name,
    biography: user.biography ?? "",
    avatar: user.avatar ?? "",
    _method: "patch",
  }
)

form.setValidationTimeout(1000)
form.validateFiles()

const submit = () =>
  form.submit({
    preserveScroll: true,
    onSuccess: () => {
      isNameChangeable = false
      images.value = []
      form.avatar = ""
    },
  })

const images = ref([])
const handleImageUpload = (event: Event): void => {
  images.value = []
  const target = event.target as HTMLInputElement
  if (!target.files) return

  const files = Array.from(target.files)
  form.avatar = files[0]

  files.forEach((file: File) => {
    const reader = new FileReader()

    reader.onload = (e: ProgressEvent<FileReader>) => {
      if (e.target && e.target.result) {
        images.value.push({
          file,
          previewUrl: e.target.result as string, // Génère un aperçu pour l'image
        })
      }
    }

    reader.readAsDataURL(file)
  })
}

// Fonction pour supprimer une image de la liste
const removeImage = (index: number): void => {
  images.value.splice(index, 1)
}
</script>
<template>
  <div>
    <h2 class="text-xl font-semibold">Profil</h2>
    <p class="mt-3 text-sm text-muted-foreground">
      C'est ainsi que les autres vous verront sur le site.
    </p>
  </div>
  <div class="flex flex-col gap-2">
    <Label for="name"
      >Nom d'utilisateur
      <span
        v-if="user.name_updated_at"
        class="ml-2 text-xs text-muted-foreground"
      >
        (modifié il y a
        {{ timeAgo(new Date(user.name_updated_at)) }})
      </span>
    </Label>
    <Input
      :disabled="!isNameChangeable"
      v-model="form.name"
      @change="form.validate('name')"
      autocomplete="name"
      type="text"
      required
      id="name"
      name="name"
    />
    <FormError v-if="form.invalid('name')" :message="form.errors.name" />
    <p class="text-sm text-muted-foreground">
      Il s'agit de votre nom d'affichage public. Il peut s'agir de votre vrai
      nom ou d'un pseudonyme. Vous ne pouvez le modifier qu'une fois tous les 30
      jours.
    </p>
  </div>
  <div class="flex flex-col gap-3">
    <Label for="biography">Biographie</Label>
    <Textarea
      v-model="form.biography"
      @change="form.validate('biography')"
      autocomplete="biography"
      class="min-h-36"
      type="text"
      id="biography"
      name="biography"
    />
    <FormError
      v-if="form.invalid('biography')"
      :message="form.errors.biography"
    />
    <p class="text-sm text-muted-foreground">
      Décrivez-vous en quelques mots. Cette partie est visible par tous.
    </p>
  </div>
  <div class="flex flex-col gap-3">
    <Label for="avatar">Avatar</Label>
    <Input
      @change="handleImageUpload"
      type="file"
      accept=".jpg, .jpeg, .png, .webp"
      id="avatar"
      name="avatar"
    />
    <div v-if="images.length" class="rounded-lg border bg-white p-4">
      <div
        v-for="(image, index) in images"
        :key="index"
        class="relative w-fit rounded-lg border"
      >
        <img
          class="h-64 w-64 object-contain"
          :src="image.previewUrl"
          alt="Image Preview"
        />
        <Button
          variant="link"
          @click="removeImage(index)"
          class="w-full text-center font-semibold"
          >Supprimer
        </Button>
      </div>
    </div>
    <FormError v-if="form.invalid('avatar')" :message="form.errors.avatar" />
    <p class="text-sm text-muted-foreground">
      La taille maximale de l’image est fixée à 2MB. <br />
      Les fichiers acceptés sont PNG/JPEG/WEBP.
    </p>
  </div>
  <Button :disabled="form.processing" @click="submit" class="w-fit"
    >Mettre à jour</Button
  >
</template>
