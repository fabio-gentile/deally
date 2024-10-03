<script setup lang="ts">
import { Link } from "@inertiajs/vue3"
import { CalendarClock, Clock, Eye } from "lucide-vue-next"
import UpVote from "@/Components/Deal/UpVote.vue"
import ShareSocial from "@/Components/common/ShareSocial.vue"
import MessageSquare from "@/Components/common/MessageSquare.vue"
import SaveBookmark from "@/Components/common/SaveBookmark.vue"
import Report from "@/Components/common/Report.vue"
import Button from "@/Components/ui/button/Button.vue"
</script>

<template>
  <article
    class="w-full max-w-[800px] gap-4 overflow-hidden rounded-lg border bg-background p-4 text-muted-foreground"
  >
    <div class="flex flex-col gap-4 md:flex-row">
      <div class="flex gap-2">
        <img
          class="h-32 w-32 shrink-0 overflow-hidden rounded-lg bg-page object-contain md:h-52 md:w-52"
          src="https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco,u_126ab356-44d8-4a06-89b4-fcdcc8df0245,c_scale,fl_relative,w_1.0,h_1.0,fl_layer_apply/b9e219ac-36be-4252-a00d-304de20d599b/WMNS+AIR+JORDAN+1+MID.png"
          alt=""
        />
        <div class="flex flex-col gap-3 md:hidden">
          <UpVote />
          <div class="flex flex-col gap-6 text-sm md:flex-row">
            <div class="flex items-center gap-2">
              <CalendarClock />
              11/10/2024
            </div>
            <div class="flex items-center gap-2">
              <Clock />
              3 heures et 7 minutes
            </div>
          </div>
        </div>
      </div>
      <div class="flex shrink flex-col gap-3">
        <div
          class="hidden flex-col gap-3 md:flex md:flex-row md:items-center md:justify-between"
        >
          <UpVote />
          <div class="flex flex-col gap-6 text-sm md:flex-row">
            <div class="flex items-center gap-2">
              <CalendarClock />
              11/10/2024
            </div>
            <div class="flex items-center gap-2">
              <Clock />
              3 heures et 7 minutes
            </div>
          </div>
        </div>

        <strong>
          <!--          TODO: add route redirection-->
          <Link href="#" class="font-semibold text-foreground"
            >Jordan 1 Mid</Link
          >
        </strong>
        <div class="flex gap-2">
          <span class="font-semibold text-primary">64,99€</span>
          <span class="font-semibold text-muted-foreground line-through"
            >89,99€</span
          >
          <!--        <span></span>-->
        </div>
        <p class="text-sm text-muted-foreground">
          Faites découvrir la culture sneakers à votre enfant avec ce modèle
          inspiré de la première chaussure signature de Michael Jordan.
          Fabriquée en matières premium...
        </p>
        <div
          class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between"
        >
          <div class="flex gap-6">
            <ShareSocial url="ewtqweq" title="Jordan 1 Mid" />
            <MessageSquare count="12" url="#comments" />
            <Report type="deal" url="" />
            <SaveBookmark type="deal" url="#" />
          </div>
          <Link class="w-full md:w-fit">
            <Button class="w-full">
              <Eye class="mr-2" />
              Voir l'annonce
            </Button>
          </Link>
        </div>
      </div>
    </div>
  </article>
</template>
