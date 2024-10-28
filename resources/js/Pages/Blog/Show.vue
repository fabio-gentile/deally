<script setup lang="ts">
import { Reply, Trash2, MessageSquare, ImageOff } from "lucide-vue-next"
import { Link, router, Head } from "@inertiajs/vue3"
import { ref } from "vue"
import Wrapper from "@/Components/layout/Wrapper.vue"
import { timeAgo } from "@/lib/time-ago"
import Report from "@/Components/common/Report.vue"
import ShareSocial from "@/Components/common/ShareSocial.vue"
import Button from "@/Components/ui/button/Button.vue"
import { Separator } from "@/Components/ui/separator"
import SendMessage from "@/Components/SendMessage.vue"
import { Blog, CommentBlog } from "@/types/model/blog"
import AspectRatio from "@/Components/ui/aspect-ratio/AspectRatio.vue"
import Breadcrumb from "@/Components/Breadcrumb.vue"
import { truncateString } from "@/lib/utils"

const { blog, allCommentsCount } = defineProps<{
  blog: Blog
  allComments: CommentBlog[]
  allCommentsCount: number
}>()

const since = timeAgo(new Date(blog.published_at as string))

const activeCommentId = ref<number | null>(null)

const toggleReplyForm = (commentId: number) => {
  activeCommentId.value = activeCommentId.value === commentId ? null : commentId
}

// Fermer le formulaire après l'envoi
const closeReplyForm = () => {
  activeCommentId.value = null
}

const allReplies = (comment: any) => {
  const replies = []

  comment.replies.forEach((reply) => {
    replies.push(reply)
    getAllSubReplies(reply, replies)
  })

  return replies
}

function getAllSubReplies(reply, replies) {
  if (reply.replies && reply.replies.length) {
    reply.replies.forEach((subReply) => {
      replies.push(subReply)
      getAllSubReplies(subReply, replies)
    })
  }
}

const handleRemoveComment = (id: number) => {
  router.delete(
    route(
      "blog.comments.destroy",
      { id: id },
      {
        preserveScroll: true,
        preserveState: true,
      }
    )
  )
}
</script>

<template>
  <Head>
    <title>
      {{ blog.meta_title ? blog.meta_title : blog.title }}
    </title>
    <meta
      head-key="description"
      name="description"
      :content="blog.meta_description ? blog.meta_description : blog.title"
    />
    <meta
      head-key="keywords"
      v-if="blog.meta_keywords"
      name="keywords"
      :content="blog.meta_keywords"
    />
    <meta head-key="og_title" property="og:title" :content="blog.title" />
    <meta
      head-key="og_description"
      property="og:description"
      :content="truncateString(blog.content, 200)"
    />
    <meta head-key="twitter_title" name="twitter:title" :content="blog.title" />
    <meta
      head-key="twitter_description"
      name="twitter:description"
      :content="truncateString(blog.content, 200)"
    />
  </Head>
  <main class="w-full bg-page pb-6">
    <Wrapper class="mt-6 !max-w-[850px]">
      <Breadcrumb
        :breadcrumbs="[
          { label: 'Accueil', route: 'home.index', active: false },
          {
            label: 'Blog',
            route: 'blog.index',
            active: false,
          },
          {
            label: blog.title,
            route: 'blog.show',
            params: { slug: blog.slug },
            active: true,
          },
        ]"
      />
      <div
        class="mt-6 grid gap-4 overflow-hidden rounded-lg bg-white p-4 dark:bg-primary-foreground"
      >
        <h2 class="text-xl font-semibold">{{ blog.title }}</h2>
        <AspectRatio :ratio="16 / 9">
          <img
            v-if="blog.image"
            class="h-full w-full rounded-md bg-page object-cover"
            :src="'/storage/uploads/blog/' + blog.image"
            :alt="'Image article ' + blog.title"
          />
          <ImageOff
            v-else
            class="h-full w-full rounded-md bg-page object-cover"
          />
        </AspectRatio>
        <div
          class="tip-tap break-all text-sm text-muted-foreground lg:text-base"
          v-html="blog.content"
        ></div>
        <Separator class="!mb-4" />
        <div class="text-sm text-muted-foreground">
          <div
            class="!-mt-4 flex flex-row items-center gap-2 text-sm text-muted-foreground"
          >
            <img
              src="/logo.svg"
              alt="Logo Deally"
              class="h-12 w-12 rounded-full"
            />
            <div class="grid gap-2">
              <div>Publié il y a {{ since }}.</div>
            </div>
          </div>
        </div>
        <div class="flex flex-wrap gap-6 font-medium text-muted-foreground">
          <ShareSocial :url="route('blog.show', blog.slug)" :title="blog.title"
            >Partager</ShareSocial
          >

          <a href="#comments" class="flex items-center gap-2 text-sm">
            <MessageSquare />
            Commentaire{{ allCommentsCount > 1 ? "s" : "" }} ({{
              allCommentsCount
            }})
          </a>
        </div>
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
        <SendMessage :content-type="'blog'" :blog="blog" />
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

            <div class="flex items-center gap-2 text-sm text-muted-foreground">
              <Report
                v-if="comment.user_id !== $page?.props?.auth?.user?.id"
                :id="comment.id"
                type="comment_blog"
              />
              <div class="flex w-fit cursor-pointer items-center gap-1 text-sm">
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
            :content-type="'blog'"
            :blog="blog"
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
                      >Il y a
                      {{ timeAgo(new Date(reply.created_at as string)) }}</span
                    >
                  </div>
                </div>
                <div
                  class="flex items-center gap-2 text-sm text-muted-foreground"
                >
                  <Report
                    v-if="reply.user_id !== $page?.props?.auth?.user?.id"
                    :id="reply.id"
                    type="comment_blog"
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
                :content-type="'blog'"
                :blog="blog"
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
  </main>
</template>
