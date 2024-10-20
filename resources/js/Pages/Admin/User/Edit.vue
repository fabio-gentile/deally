<script setup lang="ts">
import AdminTitle from "@/Components/Admin/AdminTitle.vue"
import { Button } from "@/Components/ui/button"
import { User as IUser } from "@/types/model/user"
import { Input } from "@/Components/ui/input"
import { Label } from "@/Components/ui/label"
import FormError from "@/Components/FormError.vue"
import Textarea from "@/Components/ui/textarea/Textarea.vue"
import { useForm } from "laravel-precognition-vue-inertia"
import { capitalizeFirstLetter } from "@/lib/utils"
import { Role } from "@/types/model/role"
import { ToggleGroup, ToggleGroupItem } from "@/Components/ui/toggle-group"
import Breadcrumb from "@/Components/Admin/Breadcrumb.vue"

const props = defineProps<{
  user: IUser
  roles: Role[]
  userRoles: string[]
}>()

const form = useForm(
  "post",
  route("admin.users.update", { id: props.user.id }),
  {
    name: props.user.name,
    biography: props.user.biography ?? "",
    isAvatarRemoved: false,
    roles: props.userRoles ?? [],
    _method: "patch",
  }
)

const submit = () =>
  form.submit({
    preserveScroll: true,
  })
</script>

<template>
  <AdminTitle :title="'Modification ' + user.name" />
  <Breadcrumb
    :breadcrumbs="[
      { label: 'Tableau de bord', route: 'admin.dashboard', active: false },
      { label: 'Utilisateurs', route: 'admin.users.list', active: false },
      {
        label: user.name,
        route: 'admin.users.edit',
        params: { id: user.id },
        active: true,
      },
    ]"
  />
  <div class="grid gap-4">
    <div class="grid gap-2">
      <Label for="name">Nom</Label>
      <Input
        v-model="form.name"
        @change="form.validate('name')"
        id="name"
        type="text"
        autocomplete="name"
        required
      />
      <FormError :message="form.errors.name" />
    </div>
    <div class="grid gap-2">
      <Label for="biography">Biographie</Label>
      <Textarea
        id="biography"
        v-model="form.biography"
        @change="form.validate('biography')"
        type="text"
        autocomplete="biography"
        class="min-h-[150px]"
      />
      <FormError :message="form.errors.biography" />
    </div>
    <div class="flex flex-wrap gap-2 md:gap-8">
      <div v-if="user.avatar && !form.isAvatarRemoved" class="grid w-fit gap-2">
        <Label>Avatar</Label>
        <div
          v-if="user.avatar && !form.isAvatarRemoved"
          class="rounded-lg border bg-white p-4"
        >
          <img
            class="h-64 w-64 object-contain"
            :src="'/storage/uploads/avatar/' + user.avatar"
            alt="Image Preview"
          />
          <Button
            variant="link"
            @click="form.isAvatarRemoved = true"
            class="w-full text-center font-semibold"
            >Supprimer
          </Button>
        </div>
      </div>
      <div class="flex w-fit flex-col gap-2">
        <Label for="roles">Rôles</Label>
        <ToggleGroup
          id="roles"
          type="multiple"
          class="flex flex-wrap !justify-normal gap-4 rounded-lg border bg-white p-4"
          v-model="form.roles"
        >
          <ToggleGroupItem
            aria-label="Toggle bold"
            v-for="role in props.roles"
            :value="role.name"
            :key="role.id"
            class="border data-[state=on]:bg-primary data-[state=on]:text-white"
          >
            {{ capitalizeFirstLetter(role.name) }}
          </ToggleGroupItem>
        </ToggleGroup>
      </div>
    </div>

    <Button @click="submit" type="submit" class="mt-4 w-fit">Modifier</Button>
  </div>
</template>
