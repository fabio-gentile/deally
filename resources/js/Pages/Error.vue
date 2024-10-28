<script setup lang="ts">
import { ShieldAlert } from "lucide-vue-next"
import { computed } from "vue"
import Wrapper from "@/Components/layout/Wrapper.vue"
import Button from "@/Components/ui/button/Button.vue"
import { Link } from "@inertiajs/vue3"

const props = defineProps({ status: Number })

const title = computed(() => {
  return {
    503: "503 : Service indisponible",
    500: "500 : Erreur du serveur",
    404: "404 : Page non trouvée",
    403: "403 : Accès interdit",
  }[props.status]
})

const description = computed(() => {
  return {
    503: "Désolé, nous effectuons une maintenance. Merci de revenir plus tard.",
    500: "Oups, quelque chose a mal tourné sur nos serveurs.",
    404: "Désolé, la page que vous recherchez est introuvable.",
    403: "Désolé, vous n'êtes pas autorisé à accéder à cette page.",
  }[props.status]
})
</script>

<template>
  <Wrapper class="my-8 !max-w-[800px] text-center">
    <ShieldAlert class="mx-auto mb-4 h-16 w-16" />
    <h1 class="text-2xl font-semibold">{{ title }}</h1>
    <div class="my-4 text-muted-foreground">{{ description }}</div>
    <Button
      ><Link :href="route('home.index')">Revenir à l'accueil</Link></Button
    >
  </Wrapper>
</template>
