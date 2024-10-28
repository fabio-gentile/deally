<script setup lang="ts">
import { BookmarkPlus, Flag } from "lucide-vue-next"
import { Link, router } from "@inertiajs/vue3"
import { ref } from "vue"
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from "@/Components/ui/dialog"
import { Button } from "@/Components/ui/button"

const props = defineProps<{
  type: "deal" | "discussion"
  id: number
  isBookmarked: boolean
}>()

const isBookmarked = ref(props.isBookmarked)

const saveBookmark = () => {
  router.post(
    route("favorite.store"),
    {
      type: props.type,
      id: props.id,
    },
    {
      preserveState: true,
      preserveScroll: true,
      onSuccess: () => {
        isBookmarked.value = !isBookmarked.value
      },
    }
  )
}
</script>
<template>
  <Dialog v-if="!$page.props.auth.user?.id">
    <DialogTrigger as-child>
      <div class="flex w-fit cursor-pointer items-center gap-1 text-sm">
        <BookmarkPlus class="h-5 w-5 object-contain" />
        <slot />
      </div>
    </DialogTrigger>
    <DialogContent class="text-left sm:max-w-[425px]">
      <DialogHeader>
        <DialogTitle>Sauvegarder</DialogTitle>
        <DialogDescription>
          Pour sauvegarder dans les favoris, vous devez être connecté.
        </DialogDescription>
      </DialogHeader>
      <DialogFooter>
        <Link class="w-fit" :href="route('login')">
          <Button type="submit">Se connecter</Button>
        </Link>
      </DialogFooter>
    </DialogContent>
  </Dialog>
  <div
    v-else
    @click="saveBookmark"
    class="flex w-fit cursor-pointer items-center gap-1 text-sm"
  >
    <BookmarkPlus
      class="h-5 w-5 object-contain"
      :class="{ '!text-primary': isBookmarked }"
    />
    <slot />
  </div>
</template>
