<script setup lang="ts">
import { Reply, Pencil, Trash2 } from "lucide-vue-next"
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
import { timeAgo } from "@/lib/time-ago"
import Report from "@/Components/common/Report.vue"
import SaveBookmark from "@/Components/common/SaveBookmark.vue"
import ShareSocial from "@/Components/common/ShareSocial.vue"
import Button from "@/Components/ui/button/Button.vue"
import { Separator } from "@/Components/ui/separator"
import SendMessage from "@/Components/SendMessage.vue"
import { CommentDiscussion, Discussion } from "@/types/model/discussion"
import { ScrollArea, ScrollBar } from "@/Components/ui/scroll-area"
import Breadcrumb from "@/Components/Breadcrumb.vue"
import MessageSquare from "@/Components/common/MessageSquare.vue"
import { truncateString } from "@/lib/utils"

const { discussion, category, similarDiscussions, allCommentsCount } =
  defineProps<{
    discussion: Discussion
    category: string
    similarDiscussions: Discussion[]
    allComments: CommentDiscussion[]
    allCommentsCount: number
  }>()

const since = timeAgo(new Date(discussion.created_at as string)) // string

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
      "discussions.comments.destroy",
      { id: id },
      {
        preserveScroll: true,
        preserveState: true,
      }
    )
  )
}

const discussionDestroy = (id: number) => {
  router.delete(
    route(
      "discussions.destroy",
      { id: id },
      {
        preserveScroll: true,
      }
    )
  )
}
</script>

<template>
  <Head>
    <title>
      {{ discussion.title }}
    </title>
    <meta
      head-key="description"
      name="description"
      :content="truncateString(discussion.content, 200)"
    />
    <meta head-key="og_title" property="og:title" :content="discussion.title" />
    <meta
      head-key="og_description"
      property="og:description"
      :content="truncateString(discussion.content, 200)"
    />
    <meta
      head-key="twitter_title"
      name="twitter:title"
      :content="discussion.title"
    />
    <meta
      head-key="twitter_description"
      name="twitter:description"
      :content="truncateString(discussion.content, 200)"
    />
  </Head>
  <main class="bg-page py-6">
    <Wrapper>
      <Breadcrumb
        :breadcrumbs="[
          { label: 'Accueil', route: 'home.index', active: false },
          {
            label: 'Rechercher une discussion',
            route: 'search.discussions',
            active: false,
          },
          {
            label: category,
            route: 'search.discussions',
            query: '?category=' + category,
            active: false,
          },
          {
            label: discussion.title,
            route: 'discussions.show',
            params: discussion.slug,
            active: true,
          },
        ]"
      />
      <Wrapper class="!max-w-[850px] !p-0">
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

        <!-- description -->
        <div
          class="mt-6 grid gap-4 overflow-hidden rounded-lg bg-white p-4 dark:bg-primary-foreground"
        >
          <h2 class="text-xl font-semibold">{{ discussion.title }}</h2>
          <div v-if="discussion.thumbnail">
            <img
              class="max-h-[500px] w-full rounded-lg object-contain"
              :src="'/storage/' + discussion.path + '/' + discussion.thumbnail"
              :alt="'Image ' + discussion.title"
            />
          </div>
          <div
            class="tip-tap break-all text-sm text-muted-foreground lg:text-base"
            v-html="discussion.content"
          ></div>
          <Separator class="!mb-4" />
          <div class="text-sm text-muted-foreground">
            <div
              class="!-mt-4 flex flex-row items-center gap-2 text-sm text-muted-foreground"
            >
              <img
                v-if="discussion.user.avatar"
                :src="'/storage/uploads/avatar/' + discussion.user.avatar"
                :alt="'Avatar de ' + discussion.user.name"
                class="h-[52px] w-[52px] rounded-full object-cover"
              />
              <img
                v-else
                :src="`https://ui-avatars.com/api/?size=64&name=${discussion.user.name}`"
                :alt="'Avatar de ' + discussion.user.name"
                class="h-[52px] w-[52px] rounded-full object-cover"
              />
              <div class="grid gap-2">
                <Link
                  :href="route('profile.deals', discussion.user.name)"
                  class="font-semibold"
                  >{{ discussion.user.name }}</Link
                >
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
            <MessageSquare :url="route('discussions.show', discussion.slug)"
              >Commentaires
            </MessageSquare>
            <Report :id="discussion.id" type="discussion">
              Signaler la discussion</Report
            >
            <SaveBookmark
              :is-bookmarked="discussion.user_favorite"
              type="discussion"
              :id="discussion.id"
            >
              <p
                v-if="discussion.user_favorite"
                :class="{ '!text-primary': discussion.user_favorite }"
              >
                Ajouté aux favoris
              </p>
              <p v-else>Sauvegarder</p>
            </SaveBookmark>
          </div>
        </div>

        <!-- similar discussion -->
        <div
          v-if="similarDiscussions.length > 0"
          class="mt-6 grid gap-4 rounded-lg bg-white p-4 dark:bg-primary-foreground"
        >
          <h2 class="text-xl font-semibold">Discussions similaires</h2>
          <ScrollArea>
            <div class="flex shrink-0 flex-row flex-nowrap gap-6 pb-6">
              <article
                v-for="(similarDiscussion, index) in similarDiscussions"
                :key="index"
                class="grid w-[200px] gap-3"
              >
                <Link :href="route('discussions.show', similarDiscussion.slug)">
                  <h3
                    class="line-clamp-2 text-sm font-semibold text-foreground hover:underline"
                  >
                    {{ similarDiscussion.title }}
                  </h3>
                </Link>
                <div
                  class="flex flex-row justify-between gap-6 text-sm text-muted-foreground"
                >
                  <Link
                    :href="route('profile.deals', similarDiscussion.user.name)"
                    class="flex min-w-0 flex-row items-center gap-2"
                  >
                    <img
                      v-if="similarDiscussion.user.avatar"
                      :src="
                        '/storage/uploads/avatar/' +
                        similarDiscussion.user.avatar
                      "
                      :alt="'Avatar de ' + similarDiscussion.user.name"
                      class="avatar h-[32px] rounded-full object-contain"
                    />
                    <img
                      v-else
                      :src="`https://ui-avatars.com/api/?size=64&name=${similarDiscussion.user.name}`"
                      :alt="'Avatar de ' + similarDiscussion.user.name"
                      class="avatar h-[32px] rounded-full object-contain"
                    />
                    <p class="truncate text-sm font-medium hover:underline">
                      {{ similarDiscussion.user.name }}
                    </p>
                  </Link>
                  <div
                    class="flex shrink-0 flex-row items-center gap-1 text-sm text-muted-foreground"
                  >
                    <MessageSquare
                      :url="route('discussions.show', similarDiscussion.slug)"
                      class="h-4 w-4"
                    />
                    {{ similarDiscussion.comments_count }}
                  </div>
                </div>
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
          <SendMessage :content-type="'discussion'" :discussion="discussion" />
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
                    >Il y a
                    {{ timeAgo(new Date(comment.created_at as string)) }}</span
                  >
                </div>
              </div>

              <div
                class="flex items-center gap-2 text-sm text-muted-foreground"
              >
                <Report
                  v-if="comment.user_id !== $page?.props?.auth?.user?.id"
                  :id="comment.id"
                  type="comment_discussion"
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
              :content-type="'discussion'"
              :discussion="discussion"
              :comment="comment"
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
                      type="comment_discussion"
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
                  :content-type="'discussion'"
                  :discussion="discussion"
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
