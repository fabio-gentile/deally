<script setup lang="ts">
import { Share2, Copy, Check } from "lucide-vue-next"
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from "@/Components/ui/dialog"
import { Label } from "@/Components/ui/label"
import { Input } from "@/Components/ui/input"
import { Button } from "@/Components/ui/button"
import { useClipboard } from "@vueuse/core"
import { ref } from "vue"

const props = defineProps<{
  url: string
  title: string
}>()

const source = ref(props.url)
const { copy, copied } = useClipboard({ source })

const socialShare = (social) => {
  if (social === "facebook") {
    const facebookIntentURL = "https://www.facebook.com/sharer/sharer.php"
    const contentQuery = `?u=${encodeURIComponent(props.url)}&quote=${encodeURIComponent(props.title)}`
    const shareURL = facebookIntentURL + contentQuery
    window.open(shareURL, "_blank")
  } else if (social === "x") {
    const twitterIntentURL = "https://twitter.com/intent/tweet"
    const contentQuery = `?url=${encodeURIComponent(props.url)}&text=${encodeURIComponent(props.title)}`
    const shareURL = twitterIntentURL + contentQuery
    window.open(shareURL, "_blank")
  }
}
</script>
<template>
  <Dialog>
    <DialogTrigger as-child>
      <div class="flex w-fit cursor-pointer items-center gap-1 text-sm">
        <Share2 class="h-5 w-5 cursor-pointer object-contain" />
        <slot />
      </div>
    </DialogTrigger>
    <DialogContent class="sm:max-w-md">
      <DialogHeader>
        <DialogTitle>Partager</DialogTitle>
        <DialogDescription> Réseaux sociaux </DialogDescription>
      </DialogHeader>
      <div class="flex gap-4">
        <div @click="socialShare('x')" class="rounded-lg bg-primary/10 p-2">
          <svg
            class="h-8 w-8 cursor-pointer object-contain"
            fill="#000000"
            role="img"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
          >
            <title>X</title>
            <path
              d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932ZM17.61 20.644h2.039L6.486 3.24H4.298Z"
            />
          </svg>
        </div>
        <div
          @click="socialShare('facebook')"
          class="rounded-lg bg-primary/10 p-2"
        >
          <svg
            class="h-8 w-8 cursor-pointer object-contain"
            fill="#0866FF"
            role="img"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
          >
            <title>Facebook</title>
            <path
              d="M9.101 23.691v-7.98H6.627v-3.667h2.474v-1.58c0-4.085 1.848-5.978 5.858-5.978.401 0 .955.042 1.468.103a8.68 8.68 0 0 1 1.141.195v3.325a8.623 8.623 0 0 0-.653-.036 26.805 26.805 0 0 0-.733-.009c-.707 0-1.259.096-1.675.309a1.686 1.686 0 0 0-.679.622c-.258.42-.374.995-.374 1.752v1.297h3.919l-.386 2.103-.287 1.564h-3.246v8.245C19.396 23.238 24 18.179 24 12.044c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.628 3.874 10.35 9.101 11.647Z"
            />
          </svg>
        </div>
      </div>
      <DialogDescription>Copier le lien</DialogDescription>
      <div class="flex items-center space-x-2">
        <div class="grid flex-1 gap-2">
          <Label for="link" class="sr-only"> Link </Label>
          <Input
            autofocus
            id="link"
            :default-value="props.url"
            readonly="readonly"
          />
        </div>
        <Button
          @click="copy(source)"
          title="Copier dans le presse papier"
          size="sm"
          class="w-24"
        >
          <Copy v-if="!copied" class="mr-2 h-4 w-4" />
          <Check v-else class="mr-2 h-4 w-4" />
          <span v-if="!copied">Copier</span>
          <span v-else>Copié</span>
        </Button>
      </div>
    </DialogContent>
  </Dialog>
</template>
