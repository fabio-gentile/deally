<script setup lang="ts">
import {
  CalendarClock,
  Clock,
  ImageOff,
  LucideSquareArrowOutUpRight,
  Ellipsis,
  Reply,
  Pencil,
  Trash2,
} from "lucide-vue-next"
import {
  Breadcrumb,
  BreadcrumbItem,
  BreadcrumbLink,
  BreadcrumbList,
  BreadcrumbSeparator,
} from "@/Components/ui/breadcrumb"
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
import { Link, router } from "@inertiajs/vue3"
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
import { calculatePercentage } from "@/lib/utils"
import { Separator } from "@/Components/ui/separator"
import SendMessage from "@/Components/SendMessage.vue"
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from "@/Components/ui/dropdown-menu"
import { Discussion } from "@/types/model/discussion"

const { discussion, category, similarDiscussions } = defineProps<{
  discussion: Discussion
  category: string
  similarDiscussions: Discussion[]
  // allComments: any
  // allCommentsCount: number
}>()

const since = timeAgo(new Date(discussion.created_at)) // string

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

// const handleRemoveComment = (id) => {
//   router.delete(
//     route(
//       "deals.comments.destroy",
//       { id: id },
//       {
//         preserveScroll: true,
//         onSuccess: () => {
//           console.log("Remove comment with id:", id)
//           console.log("Comment removed")
//         },
//       }
//     )
//   )
// }

const discussionDestroy = (id: number) => {
  router.delete(
    route(
      "discussions.destroy",
      { id: id },
      {
        preserveScroll: true,
        onSuccess: () => {
          console.log("Deal removed")
        },
      }
    )
  )
}
</script>

<template>
  <main class="bg-page py-8">
    <Wrapper>
      <Breadcrumb>
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
                :href="route('discussions.show', discussion.slug)"
              >
                {{ discussion.title }}
              </Link>
            </BreadcrumbLink>
          </BreadcrumbItem>
        </BreadcrumbList>
      </Breadcrumb>
      <Wrapper class="mt-6 !max-w-[850px] !p-0">
        <!-- status -->
        <div
          class="my-6 flex flex-wrap items-center justify-between gap-4 overflow-hidden rounded-lg bg-white p-4 dark:bg-primary-foreground"
          v-if="discussion.user_id === $page.props.auth?.user?.id"
        >
          <div class="font-semibold">Action</div>
          <div class="flex gap-4 text-muted-foreground">
            <Button variant="ghost" as-child class="flex items-center gap-4">
              <Link :href="route('discussions.edit', discussion.slug)">
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
                    définitivement votre discussion et les commentaires
                    associés.
                  </AlertDialogDescription>
                </AlertDialogHeader>
                <AlertDialogFooter>
                  <AlertDialogCancel>Annuler</AlertDialogCancel>
                  <AlertDialogAction
                    @click="discussionDestroy(discussion.id)"
                    class="!bg-destructive"
                  >
                    Supprimer</AlertDialogAction
                  >
                </AlertDialogFooter>
              </AlertDialogContent>
            </AlertDialog>
          </div>
        </div>

        <!-- discussion information-->

        <!-- description -->
        <div
          class="mt-6 grid gap-4 overflow-hidden rounded-lg bg-white p-4 dark:bg-primary-foreground"
        >
          <h2 class="text-xl font-semibold">{{ discussion.title }}</h2>
          <div
            class="tip-tap text-sm text-muted-foreground lg:text-base"
            v-html="discussion.content"
          ></div>
          <Separator class="!-mt-4" />
          <div class="text-sm text-muted-foreground">
            <!--            TODO: Add user avatar-->
            <div
              class="!-mt-4 flex flex-row items-center gap-2 text-sm text-muted-foreground"
            >
              <img
                src="/images/avatar.jpg"
                :alt="'Avatar de ' + discussion.user.name"
                class="avatar h-[52px] rounded-full object-contain"
              />
              <div class="grid gap-2">
                <!-- TODO: Add redirection to user profile -->
                <Link href="#" class="font-semibold">{{
                  discussion.user.name
                }}</Link>
                <div>Publié il y a {{ since }}.</div>
              </div>
            </div>
          </div>
          <div class="text-sm italic text-muted-foreground">
            Le contenu de cette discussion n'a pas été vérifié par notre équipe.
            Nous vous encourageons à faire preuve de vigilance. Si vous estimez
            que cette discussion enfreint nos règles, vous pouvez la signaler.
          </div>
          <div class="flex flex-wrap gap-6 font-medium text-muted-foreground">
            <ShareSocial
              :url="route('discussions.show', discussion.slug)"
              :title="discussion.title"
              >Partager</ShareSocial
            >
            <MessageSquare url="#comments">Commentaires </MessageSquare>
            <Report type="deal" url="#"> Signaler la discussion</Report>
            <SaveBookmark type="deal" url="#">Sauvegarder</SaveBookmark>
          </div>
        </div>

        <!-- similar deals -->
        <div
          v-if="similarDiscussions.length > 0"
          class="mt-6 grid gap-4 rounded-lg bg-white p-4 dark:bg-primary-foreground"
        >
          <h2 class="text-xl font-semibold">Discussions similaires</h2>
          <div class="flex shrink-0 flex-row flex-nowrap gap-6 overflow-x-auto">
            <article
              v-for="(similarDiscussion, index) in similarDiscussions"
              :key="index"
            >
              <Link
                :href="route('deals.show', similarDiscussion.slug)"
                class="grid w-[160px] gap-2"
              >
                <h3 class="line-clamp-1 text-sm font-semibold text-foreground">
                  {{ similarDiscussion.title }}
                </h3>
              </Link>
            </article>
          </div>
        </div>

        <!-- comments -->
        <!--        <div-->
        <!--          class="mt-6 grid gap-4 rounded-lg bg-white p-4 dark:bg-primary-foreground"-->
        <!--          id="comments"-->
        <!--        >-->
        <!--          <h2 class="text-xl font-semibold">-->
        <!--            Commentaire{{ allCommentsCount > 1 ? "s" : "" }} ({{-->
        <!--              allCommentsCount-->
        <!--            }})-->
        <!--          </h2>-->
        <!--          <SendMessage :deal="deal" />-->
        <!--          <div-->
        <!--            v-for="comment in allComments"-->
        <!--            :key="comment.id"-->
        <!--            class="flex flex-col gap-3 p-4 text-sm"-->
        <!--          >-->
        <!--            <div class="flex items-start justify-between">-->
        <!--              <div class="flex flex-row gap-2">-->
        <!--                <img-->
        <!--                  src="/images/avatar.jpg"-->
        <!--                  :alt="'Avatar de ' + comment.user.name"-->
        <!--                  class="avatar h-[52px] rounded-full object-contain"-->
        <!--                />-->
        <!--                <div class="flex flex-col justify-evenly gap-1">-->
        <!--                  <Link href="#" class="font-medium">{{-->
        <!--                    comment.user.name-->
        <!--                  }}</Link>-->
        <!--                  <span-->
        <!--                    >Il y a {{ timeAgo(new Date(comment.created_at)) }}</span-->
        <!--                  >-->
        <!--                </div>-->
        <!--              </div>-->

        <!--              <DropdownMenu>-->
        <!--                <DropdownMenuTrigger>-->
        <!--                  <Ellipsis />-->
        <!--                </DropdownMenuTrigger>-->
        <!--                <DropdownMenuContent align="end">-->
        <!--                  &lt;!&ndash; TODO: Ajouter action &ndash;&gt;-->
        <!--                  <DropdownMenuItem>Signaler</DropdownMenuItem>-->
        <!--                  <DropdownMenuItem-->
        <!--                    v-if="comment.user.id === $page?.props?.auth?.user?.id"-->
        <!--                    @click="handleRemoveComment(comment.id)"-->
        <!--                    >Supprimer</DropdownMenuItem-->
        <!--                  >-->
        <!--                </DropdownMenuContent>-->
        <!--              </DropdownMenu>-->
        <!--            </div>-->
        <!--            <p>-->
        <!--              {{ comment.content }}-->
        <!--            </p>-->

        <!--            <Button-->
        <!--              variant="ghost"-->
        <!--              class="w-fit"-->
        <!--              @click="toggleReplyForm(comment.id)"-->
        <!--              ><Reply class="mr-2" />Répondre</Button-->
        <!--            >-->
        <!--            <SendMessage-->
        <!--              v-if="activeCommentId === comment.id"-->
        <!--              :deal="deal"-->
        <!--              :comment="comment"-->
        <!--              @submitted="closeReplyForm"-->
        <!--              class="ml-8"-->
        <!--            />-->

        <!--            &lt;!&ndash; Boucler pour afficher les réponses &ndash;&gt;-->
        <!--            <div-->
        <!--              v-if="comment.replies.length"-->
        <!--              class="ml-4 grid gap-4 border-l py-2 pl-2"-->
        <!--            >-->
        <!--              <div-->
        <!--                v-for="reply in allReplies(comment)"-->
        <!--                :key="reply.id"-->
        <!--                class="flex flex-col gap-4 pl-4"-->
        <!--              >-->
        <!--                <div class="flex items-start justify-between">-->
        <!--                  <div class="flex flex-row gap-2">-->
        <!--                    <img-->
        <!--                      src="/images/avatar.jpg"-->
        <!--                      :alt="'Avatar de ' + reply.user.name"-->
        <!--                      class="avatar h-[52px] rounded-full object-contain"-->
        <!--                    />-->
        <!--                    <div class="flex flex-col justify-evenly gap-1">-->
        <!--                      <Link href="#" class="font-medium">{{-->
        <!--                        reply.user.name-->
        <!--                      }}</Link>-->
        <!--                      <span-->
        <!--                        >Il y a {{ timeAgo(new Date(reply.created_at)) }}</span-->
        <!--                      >-->
        <!--                    </div>-->
        <!--                  </div>-->

        <!--                  <DropdownMenu>-->
        <!--                    <DropdownMenuTrigger>-->
        <!--                      <Ellipsis />-->
        <!--                    </DropdownMenuTrigger>-->
        <!--                    <DropdownMenuContent align="end">-->
        <!--                      &lt;!&ndash; TODO: Ajouter action &ndash;&gt;-->
        <!--                      <DropdownMenuItem>Signaler</DropdownMenuItem>-->
        <!--                      <DropdownMenuItem-->
        <!--                        v-if="reply.user_id === $page?.props?.auth?.user?.id"-->
        <!--                        @click="handleRemoveComment(reply.id)"-->
        <!--                        >Supprimer</DropdownMenuItem-->
        <!--                      >-->
        <!--                    </DropdownMenuContent>-->
        <!--                  </DropdownMenu>-->
        <!--                </div>-->
        <!--                <div v-if="reply.answer_to_user" class="text-muted-foreground">-->
        <!--                  &lt;!&ndash;  TODO: redirection                  &ndash;&gt;-->
        <!--                  En réponse à-->
        <!--                  <Link href="#" class="font-semibold text-primary">{{-->
        <!--                    reply.answer_to_user.name-->
        <!--                  }}</Link>-->
        <!--                </div>-->
        <!--                <p>-->
        <!--                  {{ reply.content }}-->
        <!--                </p>-->

        <!--                <Button-->
        <!--                  variant="ghost"-->
        <!--                  class="w-fit"-->
        <!--                  @click="toggleReplyForm(reply.id)"-->
        <!--                  ><Reply class="mr-2" />Répondre</Button-->
        <!--                >-->
        <!--                <SendMessage-->
        <!--                  v-if="activeCommentId === reply.id"-->
        <!--                  :deal="deal"-->
        <!--                  :comment="comment"-->
        <!--                  :answer-to="reply.user_id"-->
        <!--                  @submitted="closeReplyForm"-->
        <!--                  class="ml-8"-->
        <!--                />-->
        <!--              </div>-->
        <!--            </div>-->
        <!--          </div>-->
        <!--        </div>-->
      </Wrapper>
    </Wrapper>
  </main>
</template>
