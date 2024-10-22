<script setup lang="ts">
import { useColorMode } from "@vueuse/core"
import { Moon, Sun } from "lucide-vue-next"
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from "@/Components/ui/dropdown-menu"
import { Button } from "@/Components/ui/button"
const mode = useColorMode()
</script>

<template>
  <DropdownMenu>
    <DropdownMenuTrigger as-child v-if="mode === 'dark'">
      <Button variant="ghost">
        <Moon />
      </Button>
    </DropdownMenuTrigger>
    <DropdownMenuTrigger as-child v-else>
      <Button variant="ghost">
        <Sun />
      </Button>
    </DropdownMenuTrigger>
    <DropdownMenuContent align="end">
      <DropdownMenuItem class="cursor-pointer" @click="mode = 'auto'">
        Système
      </DropdownMenuItem>
      <DropdownMenuItem class="cursor-pointer" @click="mode = 'light'">
        Clair
      </DropdownMenuItem>
      <DropdownMenuItem class="cursor-pointer" @click="mode = 'dark'">
        Sombre
      </DropdownMenuItem>
    </DropdownMenuContent>
  </DropdownMenu>
</template>
