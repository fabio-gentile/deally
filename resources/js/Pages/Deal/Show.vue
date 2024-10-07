<script setup lang="ts">
import { Deal } from "@/types/model/deal"
import { ImageDeal } from "@/types/model/image-deal"
import {
  CalendarClock,
  Clock,
  ImageOff,
  LucideSquareArrowOutUpRight,
} from "lucide-vue-next"
import {
  Breadcrumb,
  BreadcrumbItem,
  BreadcrumbLink,
  BreadcrumbList,
  BreadcrumbSeparator,
} from "@/Components/ui/breadcrumb"
import { Link } from "@inertiajs/vue3"
import { ref } from "vue"
import Wrapper from "@/Pages/Layout/Wrapper.vue"
import UpVote from "@/Components/Deal/UpVote.vue"
import Carousel from "@/Components/ui/carousel/Carousel.vue"
import CarouselContent from "@/Components/ui/carousel/CarouselContent.vue"
import CarouselItem from "@/Components/ui/carousel/CarouselItem.vue"
import CardContent from "@/Components/ui/card/CardContent.vue"
import Card from "@/Components/ui/card/Card.vue"
import { watchOnce } from "@vueuse/core"
import { useDateFormat } from "@vueuse/shared"
import { timeAgo } from "@/lib/time-ago"
import MessageSquare from "@/Components/common/MessageSquare.vue"
import Report from "@/Components/common/Report.vue"
import SaveBookmark from "@/Components/common/SaveBookmark.vue"
import ShareSocial from "@/Components/common/ShareSocial.vue"
import Button from "@/Components/ui/button/Button.vue"
import { CarouselApi } from "@/Components/ui/carousel"
import { calculatePercentage } from "@/lib/utils"

const { deal, images, category, userDealsCount } = defineProps<{
  deal: Deal
  images: ImageDeal[]
  category: string
  userDealsCount: number
}>()

const emblaMainApi = ref<CarouselApi>()
const emblaThumbnailApi = ref<CarouselApi>()
const selectedIndex = ref(0)

function onSelect() {
  if (!emblaMainApi.value || !emblaThumbnailApi.value) return
  selectedIndex.value = emblaMainApi.value.selectedScrollSnap()
  emblaThumbnailApi.value.scrollTo(emblaMainApi.value.selectedScrollSnap())
}

function onThumbClick(index: number) {
  if (!emblaMainApi.value || !emblaThumbnailApi.value) return
  emblaMainApi.value.scrollTo(index)
}

watchOnce(emblaMainApi, (emblaMainApi) => {
  if (!emblaMainApi) return

  onSelect()
  emblaMainApi.on("select", onSelect)
  emblaMainApi.on("reInit", onSelect)
})

const expirationDate = useDateFormat(deal.expiration_date, "DD/MM/YYYY")
const since = timeAgo(new Date(deal.created_at)) // string

const discountPercentage = calculatePercentage(deal.price, deal.original_price)
</script>

<template>
  <main class="bg-page">
    <Wrapper>
      <Breadcrumb class="mt-8">
        <BreadcrumbList>
          <BreadcrumbItem>
            <BreadcrumbLink>
              <Link :href="route('home.index')"> Deally </Link>
            </BreadcrumbLink>
          </BreadcrumbItem>
          <BreadcrumbSeparator />
          <BreadcrumbItem>
            <BreadcrumbLink>
              <!-- TODO: Add redirection to category-->
              <Link :href="route('home.index')"> Catégorie </Link>
            </BreadcrumbLink>
          </BreadcrumbItem>
          <BreadcrumbSeparator />
          <BreadcrumbItem>
            <BreadcrumbLink>
              <Link :href="route('home.index')"> {{ category }} </Link>
            </BreadcrumbLink>
          </BreadcrumbItem>
          <BreadcrumbSeparator />
          <BreadcrumbItem>
            <BreadcrumbLink>
              <Link
                class="font-semibold text-foreground"
                :href="route('deals.show', deal.slug)"
              >
                {{ deal.title }}
              </Link>
            </BreadcrumbLink>
          </BreadcrumbItem>
        </BreadcrumbList>
      </Breadcrumb>
      <Wrapper class="mt-6 !max-w-[850px]">
        <div
          class="flex flex-col gap-6 overflow-hidden rounded-lg bg-white p-4 md:flex-row md:gap-8"
        >
          <ImageOff
            v-if="!images"
            class="mx-auto h-52 w-52 w-full object-contain text-muted-foreground"
          />

          <!--            carousel-->
          <div v-else class="w-full sm:w-auto">
            <Carousel
              class="relative w-full max-w-xs"
              @init-api="(val) => (emblaMainApi = val)"
            >
              <CarouselContent>
                <CarouselItem v-for="(image, index) in images" :key="index">
                  <div class="p-1">
                    <Card>
                      <CardContent
                        class="flex aspect-square items-center justify-center bg-page !p-0"
                      >
                        <!--                        <span class="text-4xl font-semibold">{{-->
                        <!--                          index + 1-->
                        <!--                        }}</span>-->
                        <img
                          class="h-64 w-64 object-contain"
                          :src="'/storage/' + image.path + '/' + image.filename"
                          alt="Image Preview"
                        />
                      </CardContent>
                    </Card>
                  </div>
                </CarouselItem>
              </CarouselContent>
            </Carousel>

            <Carousel
              class="relative mt-2 w-full max-w-xs"
              @init-api="(val) => (emblaThumbnailApi = val)"
            >
              <CarouselContent class="ml-0 flex gap-1">
                <CarouselItem
                  v-for="(image, index) in images"
                  :key="index"
                  class="basis-1/4 cursor-pointer pl-0"
                  @click="onThumbClick(index)"
                >
                  <div
                    class="h-16 w-16 p-1"
                    :class="index === selectedIndex ? '' : 'opacity-50'"
                  >
                    <img
                      class="object-cover"
                      :src="'/storage/' + image.path + '/' + image.filename"
                      alt="Image Preview"
                    />
                  </div>
                </CarouselItem>
              </CarouselContent>
            </Carousel>
          </div>

          <div class="flex grow flex-col justify-evenly gap-6">
            <UpVote
              :deal="deal"
              :votes="deal.votes"
              :vote="deal.user_vote ?? false"
            />

            <h1 class="text-3xl font-semibold text-foreground">
              {{ deal.title }}
            </h1>

            <div class="flex gap-2 text-xl">
              <span class="font-semibold text-primary">
                <span v-if="!deal.price && deal.original_price">GRATUIT</span>
                <span v-if="deal.price">{{ deal.price }}€</span>
              </span>
              <span
                v-if="deal.original_price"
                class="font-medium text-muted-foreground line-through"
                >{{ deal.original_price }}€</span
              >
              <span
                v-if="deal.original_price && deal.price"
                class="font-medium text-muted-foreground"
                >-{{ discountPercentage }}</span
              >
            </div>

            <div
              class="grid grid-cols-2 gap-y-6 font-medium text-muted-foreground"
            >
              <div class="flex flex-col gap-4">
                <ShareSocial
                  :url="route('deals.show', deal.slug)"
                  title="Jordan 1 Mid"
                  >Partager</ShareSocial
                >
                <Report type="deal" url="#"> Signaler l'annonce</Report>
              </div>
              <div class="flex flex-col gap-4">
                <MessageSquare url="#comments">Commentaires </MessageSquare>
                <SaveBookmark type="deal" url="#">Sauvegarder</SaveBookmark>
              </div>
            </div>

            <a
              :href="deal.deal_url"
              target="_blank"
              class="w-full"
              rel="nofollow noopener noreferrer"
            >
              <Button class="w-full">
                <LucideSquareArrowOutUpRight class="mr-2" />
                Voir le deal
              </Button>
            </a>

            <div class="flex flex-col gap-4 md:flex-row md:justify-between">
              <!--            TODO: Add user avatar-->
              <div
                class="flex flex-row items-center gap-2 text-sm text-muted-foreground"
              >
                <img
                  src="/images/avatar.jpg"
                  :alt="'Avatar de ' + deal.user.name"
                  class="avatar h-[52px] rounded-full object-contain"
                />
                <div class="grid gap-2">
                  <div>
                    Proposé par
                    <!--                    TODO: Add redirection to user profile-->
                    <Link href="#" class="font-semibold">{{
                      deal.user.name
                    }}</Link>
                  </div>
                  <div v-if="userDealsCount > 1">
                    {{ userDealsCount }} deals partagés
                  </div>
                  <div v-else>{{ userDealsCount }} deal partagé</div>
                </div>
              </div>

              <div class="flex flex-col gap-2 text-sm text-muted-foreground">
                <div class="flex items-center gap-2">
                  <Clock />
                  {{ since }}
                </div>
                <div class="flex items-center gap-2">
                  <CalendarClock />
                  {{ expirationDate }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </Wrapper>
    </Wrapper>
  </main>
</template>
