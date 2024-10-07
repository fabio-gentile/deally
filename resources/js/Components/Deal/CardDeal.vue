<script setup lang="ts">
import { Link } from "@inertiajs/vue3"
import { CalendarClock, Clock, Eye } from "lucide-vue-next"
import UpVote from "@/Components/Deal/UpVote.vue"
import ShareSocial from "@/Components/common/ShareSocial.vue"
import MessageSquare from "@/Components/common/MessageSquare.vue"
import SaveBookmark from "@/Components/common/SaveBookmark.vue"
import Report from "@/Components/common/Report.vue"
import Button from "@/Components/ui/button/Button.vue"
import { Deal } from "@/types/model/deal"
import { useDateFormat } from "@vueuse/core"
import { timeAgo } from "@/lib/time-ago"

const { deal } = defineProps<{
  deal: Deal
}>()

const expirationDate = useDateFormat(deal.expiration_date, "DD/MM/YYYY")
const since = timeAgo(new Date(deal.created_at)) // string
</script>

<template>
  <article
    class="w-full max-w-[800px] gap-4 overflow-hidden rounded-lg border bg-background p-4 text-muted-foreground"
  >
    <div class="flex flex-col gap-4 md:flex-row">
      <div class="flex shrink-0 gap-2">
        <img
          class="h-32 w-32 shrink-0 overflow-hidden rounded-lg bg-page object-contain md:h-52 md:w-52"
          src="https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco,u_126ab356-44d8-4a06-89b4-fcdcc8df0245,c_scale,fl_relative,w_1.0,h_1.0,fl_layer_apply/b9e219ac-36be-4252-a00d-304de20d599b/WMNS+AIR+JORDAN+1+MID.png"
          alt=""
        />
        <div class="flex flex-col gap-3 md:hidden">
          <UpVote
            :deal="deal"
            :votes="deal.votes"
            :vote="deal.user_vote ?? false"
          />
          <div class="flex flex-col gap-6 text-sm md:flex-row">
            <div class="flex items-center gap-2">
              <CalendarClock />
              {{ expirationDate }}
            </div>
            <div class="flex items-center gap-2">
              <Clock />
              {{ since }}
            </div>
          </div>
        </div>
      </div>
      <div class="flex grow flex-col justify-evenly gap-3">
        <div
          class="hidden flex-col gap-3 md:flex md:flex-row md:items-center md:justify-between"
        >
          <UpVote
            :deal="deal"
            :votes="deal.votes"
            :vote="deal.user_vote ?? false"
          />
          <div class="flex flex-col gap-6 text-sm md:flex-row">
            <div class="flex items-center gap-2">
              <CalendarClock />
              {{ expirationDate }}
            </div>
            <div class="flex items-center gap-2">
              <Clock />
              {{ since }}
            </div>
          </div>
        </div>

        <strong>
          <!--          TODO: add route redirection-->
          <Link
            :href="route('deals.show', deal.slug)"
            class="line-clamp-4 font-semibold text-foreground md:line-clamp-2"
            >{{ deal.title }}</Link
          >
        </strong>
        <div class="flex gap-2">
          <span class="font-semibold text-primary">
            <span v-if="!deal.price && deal.original_price">GRATUIT</span>
            <span v-if="deal.price">{{ deal.price }}€</span>
          </span>
          <span
            v-if="deal.original_price"
            class="font-semibold text-muted-foreground line-through"
            >{{ deal.original_price }}€</span
          >
          <!--        <span></span>-->
        </div>
        <p
          class="line-clamp-3 text-sm text-muted-foreground md:line-clamp-2"
          v-html="deal.description"
        ></p>
        <div
          class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between"
        >
          <div class="flex gap-6">
            <ShareSocial
              :url="route('deals.show', deal.slug)"
              title="Jordan 1 Mid"
            />
            <MessageSquare :count="12" url="#comments" />
            <Report type="deal" url="#" />
            <SaveBookmark type="deal" url="#" />
          </div>
          <Link :href="route('deals.show', deal.slug)" class="w-full md:w-fit">
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
