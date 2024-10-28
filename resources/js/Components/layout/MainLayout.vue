<script setup lang="ts">
import NavigationBar from "@/Components/layout/NavigationBar.vue"
import { Toaster } from "@/Components/ui/toast"
import { useToast } from "@/Components/ui/toast/use-toast"
import Footer from "@/Components/layout/Footer.vue"
import { usePage, Head } from "@inertiajs/vue3"
import { watch } from "vue"
const page = usePage()

const { toast } = useToast()
watch(
  () => usePage().props.flash,
  (flash: { success: string | null; error: string | null }) => {
    // console.log(flash)
    if (flash.success) {
      toast({
        description: flash.success,
        duration: 3000,
      })
    }

    if (flash.error) {
      toast({
        description: flash.error,
        variant: "destructive",
        duration: 3000,
      })
    }
  },
  { deep: true, immediate: true }
)
</script>

<template>
  <Head>
    <link rel="icon" type="image/svg+xml" href="/logo.svg" />
    <meta
      head-key="description"
      name="description"
      content="Deally est une plateforme de partage de bons plans et de réductions. Venez découvrir les meilleures offres et partager vos bons plans avec la communauté."
    />
    <meta
      head-key="keywords"
      name="keywords"
      content="deal, bon plan, réduction, gratuit, discussion, blog, deally, promotion"
    />
    <meta name="author" content="Deally" />
    <meta name="robots" content="noindex, follow" />

    <link rel="apple-touch-icon" sizes="57x38" href="/logo.svg" />
    <link rel="apple-touch-icon" sizes="72x49" href="/logo.svg" />
    <link rel="apple-touch-icon" sizes="76x52" href="/logo.svg" />
    <link rel="apple-touch-icon" sizes="114x78" href="/logo.svg" />
    <link rel="apple-touch-icon" sizes="120x82" href="/logo.svg" />
    <link rel="apple-touch-icon" sizes="144x98" href="/logo.svg" />
    <link rel="apple-touch-icon" sizes="152x104" href="/logo.svg" />
    <link rel="icon" type="image/png" href="/logo.svg" sizes="196x134" />
    <link rel="icon" type="image/png" href="/logo.svg" sizes="160x110" />
    <link rel="icon" type="image/png" href="/logo.svg" sizes="96x66" />
    <link rel="icon" type="image/png" href="/logo.svg" sizes="16x11" />
    <link rel="icon" type="image/png" href="/logo.svg" sizes="32x22" />
    <link rel="icon" type="image/png" href="/logo.svg" />
    <meta property="og:image" content="/logo-deally.svg" />
    <meta property="og:image:width" content="1800" />
    <meta property="og:image:height" content="945" />
    <meta property="og:url" content="https://deally.fabiogentile.me" />
    <meta head-key="og_title" property="og:title" content="Deally" />
    <meta
      head-key="og_description"
      property="og:description"
      content="Deally est une plateforme de partage de bons plans et de réductions. Venez découvrir les meilleures offres et partager vos bons plans avec la communauté."
    />
    <meta property="og:type" content="website" />
    <meta property="og:locale" content="fr_FR" />
    <meta property="og:site_name" content="Deally" />
    <meta name="twitter:site" content="@deally" />
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:url" content="https://deally.fabiogentile.me" />
    <meta head-key="twitter_title" name="twitter:title" content="Deally" />
    <meta
      head-key="twitter_description"
      name="twitter:description"
      content="Deally est une plateforme de partage de bons plans et de réductions. Venez découvrir les meilleures offres et partager vos bons plans avec la communauté."
    />
    <meta name="twitter:image" content="/logo-deally.svg" />
  </Head>
  <div>
    <Toaster />
    <div
      class="mx-auto flex min-h-[100dvh] flex-col justify-center font-sans antialiased"
    >
      <NavigationBar :user="$page.props.auth.user?.name" />
      <div class="flex grow flex-col items-center bg-page">
        <slot />
      </div>
      <Footer />
    </div>
  </div>
</template>
