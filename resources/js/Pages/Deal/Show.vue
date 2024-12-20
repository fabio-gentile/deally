<script setup lang="ts">
import { CommentDeal, Deal } from "@/types/model/deal"
import { ImageDeal } from "@/types/model/deal"
import { ScrollArea, ScrollBar } from "@/Components/ui/scroll-area"
import {
  CalendarClock,
  Clock,
  ImageOff,
  LucideSquareArrowOutUpRight,
  Reply,
  Pencil,
  Trash2,
  Check,
  Copy,
  CircleAlert,
} from "lucide-vue-next"
import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
  AlertDialogTrigger,
} from "@/Components/ui/alert-dialog"
import { Head, Link, router } from "@inertiajs/vue3"
import { ref } from "vue"
import Wrapper from "@/Components/layout/Wrapper.vue"
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
import { calculatePercentage, truncateString } from "@/lib/utils"
import { Separator } from "@/Components/ui/separator"
import SendMessage from "@/Components/SendMessage.vue"
import { useClipboard } from "@vueuse/core/index"
import Breadcrumb from "@/Components/Breadcrumb.vue"

const { deal, images, category, userDealsCount, allCommentsCount, isExpired } =
  defineProps<{
    deal: Deal
    images: ImageDeal[]
    category: string
    userDealsCount: number
    similarDeals: Deal[]
    allComments: CommentDeal[]
    allCommentsCount: number
    isExpired: boolean
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
const since = timeAgo(new Date(deal.created_at as string)) // string

const discountPercentage = calculatePercentage(
  deal.price || 0,
  deal.original_price || 0
)

const activeCommentId = ref<number | null>(null) // stocke l'ID du commentaire auquel on répond

const toggleReplyForm = (commentId: number) => {
  activeCommentId.value = activeCommentId.value === commentId ? null : commentId
}

// Fermer le formulaire après l'envoi
const closeReplyForm = () => {
  activeCommentId.value = null
}

const allReplies = (comment: any) => {
  const replies = []

  // Récupérer les réponses directes
  comment.replies.forEach((reply) => {
    replies.push(reply) // Ajouter la réponse directe

    // Ajouter les sous-réponses de manière récursive
    getAllSubReplies(reply, replies)
  })

  return replies
}

function getAllSubReplies(reply, replies) {
  // Vérifier s'il y a des sous-réponses
  if (reply.replies && reply.replies.length) {
    reply.replies.forEach((subReply) => {
      replies.push(subReply) // Ajouter la sous-réponse
      getAllSubReplies(subReply, replies) // Appeler récursivement pour les sous-réponses
    })
  }
}

const handleRemoveComment = (id: number) => {
  router.delete(
    route(
      "deals.comments.destroy",
      { id: id },
      {
        preserveScroll: true,
        onSuccess: () => {
          // console.log("Remove comment with id:", id)
          // console.log("Comment removed")
        },
      }
    )
  )
}

const destroyDeal = (id: number) => {
  router.delete(
    route(
      "deals.destroy",
      { id: id },
      {
        preserveScroll: true,
        onSuccess: () => {
          // console.log("Deal removed")
        },
      }
    )
  )
}

const source = ref(deal.promo_code)
const { copy, copied } = useClipboard({ source })
</script>

<template>
  <Head>
    <title>
      {{ deal.title }}
    </title>
    <meta
      head-key="description"
      name="description"
      :content="truncateString(deal.description, 200)"
    />
    <meta head-key="og_title" property="og:title" :content="deal.title" />
    <meta
      head-key="og_description"
      property="og:description"
      :content="truncateString(deal.description, 200)"
    />
    <meta head-key="twitter_title" name="twitter:title" :content="deal.title" />
    <meta
      head-key="twitter_description"
      name="twitter:description"
      :content="truncateString(deal.description, 200)"
    />
  </Head>
  <main class="bg-page py-6">
    <Wrapper>
      <Breadcrumb
        :breadcrumbs="[
          { label: 'Accueil', route: 'home.index', active: false },
          {
            label: 'Rechercher un deal',
            route: 'search.deals',
            active: false,
          },
          {
            label: category,
            route: 'search.deals',
            query: '?category=' + category,
            active: false,
          },
          {
            label: deal.title,
            route: 'deals.show',
            params: deal.slug,
            active: true,
          },
        ]"
      />
      <Wrapper class="mt-6 !max-w-[850px] !p-0">
        <!-- status -->
        <div
          class="my-6 flex flex-wrap items-center justify-between gap-4 overflow-hidden rounded-lg bg-white p-4 dark:bg-primary-foreground"
          v-if="deal.user_id === $page.props.auth?.user?.id"
        >
          <div class="font-semibold">Action</div>
          <div class="flex gap-4 text-muted-foreground">
            <Button variant="ghost" as-child class="flex items-center gap-4">
              <Link :href="route('deals.edit', deal.slug)">
                <Pencil />
                Modifier
              </Link>
            </Button>
            <Button as-child class="flex items-center gap-4"> </Button>
            <AlertDialog>
              <AlertDialogTrigger as-child>
                <Button variant="ghost"><Trash2 />Supprimer</Button>
              </AlertDialogTrigger>
              <AlertDialogContent>
                <AlertDialogHeader>
                  <AlertDialogTitle>Êtes-vous vraiment sûr ?</AlertDialogTitle>
                  <AlertDialogDescription>
                    Cette action ne peut pas être annulée. Cela supprimera
                    définitivement votre deal et celui-ci sera supprimé de nos
                    serveurs.
                  </AlertDialogDescription>
                </AlertDialogHeader>
                <AlertDialogFooter>
                  <AlertDialogCancel>Annuler</AlertDialogCancel>
                  <AlertDialogAction
                    @click="destroyDeal(deal.id)"
                    class="!bg-destructive"
                  >
                    Supprimer</AlertDialogAction
                  >
                </AlertDialogFooter>
              </AlertDialogContent>
            </AlertDialog>
          </div>
        </div>
        <!-- deal information-->
        <div
          v-if="isExpired"
          class="my-6 grid gap-4 overflow-hidden rounded-lg bg-white p-4 dark:bg-primary-foreground"
        >
          <!--            isExpired-->
          <div class="flex items-center justify-center gap-4">
            <CircleAlert class="h-8 w-8 text-destructive" />
            <p class="text-center text-xl font-bold text-destructive">
              Ce deal a expiré il y a
              {{ timeAgo(new Date(deal.expiration_date)) }}
            </p>
          </div>
        </div>
        <div
          class="flex flex-col gap-6 overflow-hidden rounded-lg bg-white p-4 dark:bg-primary-foreground md:flex-row md:gap-8"
        >
          <div
            v-if="images.length < 1"
            class="flex items-center justify-center"
          >
            <ImageOff
              class="mx-auto h-52 w-52 object-contain text-muted-foreground"
            />
          </div>
          <!-- carousel -->
          <div v-else class="w-full shrink-0 sm:w-auto">
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
              :is-expired="isExpired"
              :deal="deal"
              :votes="deal.votes"
              :vote="deal.user_vote ?? false"
            />

            <h1 class="text-3xl font-semibold text-foreground">
              {{ deal.title }}
            </h1>

            <div class="flex gap-2 text-xl">
              <span class="font-semibold text-primary">
                <span
                  v-if="(!deal.price || deal.price == 0) && deal.original_price"
                  >GRATUIT</span
                >
                <span v-if="deal.price > 0">{{ deal.price }}€</span>
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

            <div class="flex items-center gap-4" v-if="deal.promo_code">
              <p>Code promotionnel</p>
              <Button
                :disabled="isExpired"
                @click="copy(source)"
                class="flex cursor-pointer items-center gap-1 rounded-lg border bg-primary px-4 py-1 font-semibold uppercase text-white"
              >
                <Copy v-if="!copied && !isExpired" class="mr-2 h-4 w-4" />
                <Check v-else v-if="!isExpired" class="mr-2 h-4 w-4" />
                <span v-if="!copied">{{ deal.promo_code }}</span>
                <span v-else>Copié</span>
              </Button>
            </div>

            <div
              class="grid grid-cols-2 gap-y-6 font-medium text-muted-foreground"
            >
              <div class="flex flex-col gap-4">
                <ShareSocial
                  :url="route('deals.show', deal.slug)"
                  :title="deal.title"
                  >Partager</ShareSocial
                >
                <Report :id="deal.id" type="deal"> Signaler l'annonce</Report>
              </div>
              <div class="flex flex-col gap-4">
                <MessageSquare :url="route('deals.show', deal.slug)"
                  >Commentaires
                </MessageSquare>
                <SaveBookmark
                  :is-bookmarked="deal.user_favorite"
                  type="deal"
                  :id="deal.id"
                >
                  <p
                    v-if="deal.user_favorite"
                    :class="{ '!text-primary': deal.user_favorite }"
                  >
                    Ajouté aux favoris
                  </p>
                  <p v-else>Sauvegarder</p>
                </SaveBookmark>
              </div>
            </div>

            <a
              :href="deal.deal_url"
              target="_blank"
              class="w-full"
              rel="nofollow noopener noreferrer"
            >
              <Button class="w-full" :disabled="isExpired">
                <LucideSquareArrowOutUpRight class="mr-2" />
                Voir le deal
              </Button>
            </a>

            <div class="flex flex-col gap-4 md:flex-row md:justify-between">
              <div
                class="flex flex-row items-center gap-2 text-sm text-muted-foreground"
              >
                <img
                  v-if="deal.user.avatar"
                  :src="'/storage/uploads/avatar/' + deal.user.avatar"
                  :alt="'Avatar de ' + deal.user.avatar"
                  class="h-[52px] w-[52px] rounded-full object-cover"
                />
                <img
                  v-else
                  :src="`https://ui-avatars.com/api/?size=64&name=${deal.user.name}`"
                  :alt="'Avatar de ' + deal.user.name"
                  class="h-[52px] w-[52px] rounded-full object-cover"
                />
                <div class="grid gap-2">
                  <div>
                    Proposé par
                    <Link
                      :href="route('profile.deals', deal.user.name)"
                      class="font-semibold"
                      >{{ deal.user.name }}</Link
                    >
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

        <!-- description -->
        <div
          class="mt-6 grid gap-4 overflow-hidden rounded-lg bg-white p-4 dark:bg-primary-foreground"
        >
          <h2 class="text-xl font-semibold">Description</h2>
          <div
            class="break-all text-sm text-muted-foreground lg:text-base"
            v-html="deal.description"
          ></div>
          <Separator class="!mb-4" />
          <div class="text-sm text-muted-foreground">
            <div class="italic">
              Dernière édition par
              <Link
                :href="route('profile.deals', deal.user.name)"
                class="font-semibold"
                >{{ deal.user.name }}</Link
              >, il y a {{ timeAgo(new Date(deal.updated_at as string)) }}.
            </div>
          </div>
          <div class="text-sm italic text-muted-foreground">
            Le contenu de cette annonce n'a pas été vérifié par notre équipe.
            Nous vous encourageons à faire preuve de vigilance. Si vous estimez
            que cette annonce enfreint nos règles ou contient des informations
            inappropriées, vous pouvez la signaler.
          </div>
        </div>

        <!-- similar deals -->
        <div
          v-if="similarDeals.length > 0"
          class="mt-6 grid gap-4 rounded-lg bg-white p-4 dark:bg-primary-foreground"
        >
          <h2 class="text-xl font-semibold">Deals similaires</h2>
          <ScrollArea>
            <div class="flex shrink-0 flex-row flex-nowrap gap-6 pb-6">
              <article
                v-for="(similarDeal, index) in similarDeals"
                :key="index"
              >
                <Link
                  :href="route('deals.show', similarDeal.slug)"
                  class="grid w-[160px] gap-2"
                >
                  <img
                    class="mx-auto h-40 w-40 overflow-hidden rounded-lg bg-page object-contain dark:bg-primary-foreground"
                    v-if="similarDeal.images[0]"
                    :src="
                      '/storage/' +
                      similarDeal.images[0].path +
                      '/' +
                      similarDeal.images[0].filename
                    "
                    :alt="similarDeal.images[0].original_filename"
                  />
                  <ImageOff
                    v-else
                    class="mx-auto h-40 w-40 overflow-hidden rounded-lg bg-page object-contain text-muted-foreground dark:bg-primary-foreground"
                  />
                  <h3
                    class="line-clamp-1 text-sm font-semibold text-foreground"
                  >
                    {{ similarDeal.title }}
                  </h3>
                  <div class="flex gap-1 text-sm">
                    <span class="font-semibold text-primary">
                      <span
                        v-if="
                          (!similarDeal.price || similarDeal.price == 0) &&
                          similarDeal.original_price
                        "
                        >GRATUIT</span
                      >
                      <span v-if="similarDeal.price != 0 && similarDeal.price"
                        >{{ similarDeal.price }}€</span
                      >
                    </span>
                    <span
                      v-if="similarDeal.original_price"
                      class="font-medium text-muted-foreground line-through"
                      >{{ similarDeal.original_price }}€</span
                    >
                    <span
                      v-if="similarDeal.original_price && deal.price"
                      class="font-medium text-muted-foreground"
                      >-{{
                        calculatePercentage(
                          similarDeal.price,
                          similarDeal.original_price
                        )
                      }}</span
                    >
                  </div>
                </Link>
              </article>
            </div>
            <ScrollBar orientation="horizontal" />
          </ScrollArea>
        </div>

        <!-- comments -->
        <div
          class="mt-6 grid gap-4 rounded-lg bg-white p-4 dark:bg-primary-foreground"
          id="comments"
        >
          <h2 class="text-xl font-semibold">
            Commentaire{{ allCommentsCount > 1 ? "s" : "" }} ({{
              allCommentsCount
            }})
          </h2>
          <SendMessage :content-type="'deal'" :deal="deal" />
          <div
            v-for="comment in allComments"
            :key="comment.id"
            class="flex flex-col gap-3 p-4 text-sm"
          >
            <div class="flex items-start justify-between">
              <div class="flex flex-row gap-2">
                <img
                  v-if="comment.user.avatar"
                  :src="'/storage/uploads/avatar/' + comment.user.avatar"
                  :alt="'Avatar de ' + comment.user.name"
                  class="h-[52px] w-[52px] rounded-full object-cover"
                />
                <img
                  v-else
                  :src="`https://ui-avatars.com/api/?size=64&name=${comment.user.name}`"
                  :alt="'Avatar de ' + comment.user.name"
                  class="h-[52px] w-[52px] rounded-full object-cover"
                />
                <div class="flex flex-col justify-evenly gap-1">
                  <Link
                    :href="route('profile.deals', comment.user.name)"
                    class="font-medium"
                    >{{ comment.user.name }}</Link
                  >
                  <span
                    >Il y a {{ timeAgo(new Date(comment.created_at)) }}</span
                  >
                </div>
              </div>

              <div
                class="flex items-center gap-2 text-sm text-muted-foreground"
              >
                <Report
                  v-if="comment.user_id !== $page?.props?.auth?.user?.id"
                  :id="comment.id"
                  type="comment_deal"
                />
                <div
                  class="flex w-fit cursor-pointer items-center gap-1 text-sm"
                >
                  <Trash2
                    v-if="comment.user_id === $page?.props?.auth?.user?.id"
                    @click="handleRemoveComment(comment.id)"
                  />
                </div>
              </div>
            </div>
            <p>
              {{ comment.content }}
            </p>

            <Button
              variant="ghost"
              class="w-fit"
              @click="toggleReplyForm(comment.id)"
              ><Reply class="mr-2" />Répondre</Button
            >
            <SendMessage
              v-if="activeCommentId === comment.id"
              :comment="comment"
              :content-type="'deal'"
              :deal="deal"
              @submitted="closeReplyForm"
              class="ml-8"
            />

            <!-- Boucler pour afficher les réponses -->
            <div
              v-if="comment.replies.length"
              class="ml-4 grid gap-4 border-l py-2 pl-2"
            >
              <div
                v-for="reply in allReplies(comment)"
                :key="reply.id"
                class="flex flex-col gap-4 pl-4"
              >
                <div class="flex items-start justify-between">
                  <div class="flex flex-row gap-2">
                    <img
                      v-if="reply.user.avatar"
                      :src="'/storage/uploads/avatar/' + reply.user.avatar"
                      :alt="'Avatar de ' + reply.user.name"
                      class="h-[52px] w-[52px] rounded-full object-cover"
                    />
                    <img
                      v-else
                      :src="`https://ui-avatars.com/api/?size=64&name=${reply.user.name}`"
                      :alt="'Avatar de ' + reply.user.name"
                      class="h-[52px] w-[52px] rounded-full object-cover"
                    />
                    <div class="flex flex-col justify-evenly gap-1">
                      <Link
                        :href="route('profile.deals', reply.user.name)"
                        class="font-medium"
                        >{{ reply.user.name }}</Link
                      >
                      <span
                        >Il y a {{ timeAgo(new Date(reply.created_at)) }}</span
                      >
                    </div>
                  </div>

                  <div
                    class="flex items-center gap-2 text-sm text-muted-foreground"
                  >
                    <Report
                      v-if="reply.user_id !== $page?.props?.auth?.user?.id"
                      :id="reply.id"
                      type="comment_deal"
                    />
                    <div
                      class="flex w-fit cursor-pointer items-center gap-1 text-sm"
                    >
                      <Trash2
                        v-if="reply.user_id === $page?.props?.auth?.user?.id"
                        @click="handleRemoveComment(reply.id)"
                      />
                    </div>
                  </div>
                </div>
                <div v-if="reply.answer_to_user" class="text-muted-foreground">
                  En réponse à
                  <Link
                    :href="route('profile.deals', reply.answer_to_user.name)"
                    class="font-semibold text-primary"
                    >{{ reply.answer_to_user.name }}</Link
                  >
                </div>
                <p>
                  {{ reply.content }}
                </p>

                <Button
                  variant="ghost"
                  class="w-fit"
                  @click="toggleReplyForm(reply.id)"
                  ><Reply class="mr-2" />Répondre</Button
                >
                <SendMessage
                  v-if="activeCommentId === reply.id"
                  :content-type="'deal'"
                  :deal="deal"
                  :comment="comment"
                  :answer-to="reply.user_id"
                  @submitted="closeReplyForm"
                  class="ml-8"
                />
              </div>
            </div>
          </div>
        </div>
      </Wrapper>
    </Wrapper>
  </main>
</template>
