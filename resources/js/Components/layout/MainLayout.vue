<script setup lang="ts">
import NavigationBar from "@/Components/layout/NavigationBar.vue"
import { Toaster } from "@/Components/ui/toast"
import { useToast } from "@/Components/ui/toast/use-toast"
import Footer from "@/Components/layout/Footer.vue"
import { usePage } from "@inertiajs/vue3"
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
  <div>
    <Toaster />
    <div
      class="mx-auto flex min-h-[100dvh] flex-col justify-center font-sans antialiased"
    >
      <NavigationBar :user="$page.props.auth.user?.name" />
      <div class="flex grow flex-col items-center justify-center bg-page">
        <slot />
      </div>
      <Footer />
    </div>
  </div>
</template>
