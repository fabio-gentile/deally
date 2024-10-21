<script setup lang="ts">
import AdminTitle from "@/Components/Admin/AdminTitle.vue"
import { Button } from "@/Components/ui/button"
import Breadcrumb from "@/Components/Breadcrumb.vue"
import { Report as IReport } from "@/types/model/report"
import { useDateFormat } from "@vueuse/core"
import { ExternalLink } from "lucide-vue-next"
import { computed } from "vue"

const props = defineProps<{
  report: IReport
}>()

const modifyLink = computed(() => {
  if (props.report.reportable_type === "App\\Models\\Deal") {
    return route("admin.deals.edit", props.report.reportable.id)
  }
  if (props.report.reportable_type === "App\\Models\\Discussion") {
    return route("admin.discussions.edit", props.report.reportable.id)
  }
})

const showLink = computed(() => {
  if (props.report.reportable_type === "App\\Models\\CommentDeal") {
    return route("admin.deal.comments.show", props.report.reportable.id)
  }

  if (props.report.reportable_type === "App\\Models\\CommentDiscussion") {
    return route("admin.discussions.comments.show", props.report.reportable_id)
  }
})
</script>

<template>
  <AdminTitle :title="'Signalement n°' + report.id" />
  <Breadcrumb
    :breadcrumbs="[
      { label: 'Tableau de bord', route: 'admin.dashboard', active: false },
      {
        label: 'Signalements',
        route: 'admin.reports.list',
        active: false,
      },
      {
        label: 'Signalement n°' + report.id,
        route: 'admin.reports.show',
        params: { id: report.id },
        active: true,
      },
    ]"
  />
  <div class="grid gap-4">
    <div class="grid w-fit gap-4">
      <h3 class="font-semibold">Signalement effectué par</h3>
      <div class="flex items-center gap-4">
        <img
          v-if="report.user.avatar"
          :src="'/storage/uploads/avatar/' + report.user.avatar"
          :alt="'Avatar de ' + report.user.name"
          class="h-24 w-24 rounded-full object-cover"
        />
        <img
          v-else
          :src="`https://ui-avatars.com/api/?size=64&name=${report.user.name}`"
          :alt="'Avatar de ' + report.user.name"
          class="h-24 w-24 rounded-full object-cover"
        />
        <div class="flex flex-col justify-between gap-4">
          <p class="font-medium text-muted-foreground">
            {{ report.user.name }}
          </p>
          <a target="_blank" :href="route('profile.index', report.user.name)">
            <Button variant="outline">Voir le profil</Button>
          </a>
        </div>
      </div>
    </div>
    <div class="grid gap-4">
      <h3 class="font-semibold">Créé le</h3>
      <p class="text-muted-foreground">
        {{
          useDateFormat(report.created_at, "DD-MM-YY", {
            locales: "fr-FR",
          })
        }}
      </p>
    </div>
    <div class="grid gap-4">
      <h3 class="font-semibold">Raison</h3>
      <p class="font-medium text-muted-foreground">{{ report.reason }}</p>
    </div>
    <div v-if="report.description" class="grid gap-4">
      <h3 class="font-semibold">Description</h3>
      <p class="max-w-[200px] break-all font-medium text-muted-foreground">
        {{ report.description }}
      </p>
    </div>
    <div class="grid gap-4">
      <h3 class="font-semibold">Contenu signalé</h3>
      <p
        v-if="report.reportable_type === 'App\\Models\\CommentDeal'"
        class="text-muted-foreground"
      >
        Commentaire deal n°{{ report.reportable_id }}
      </p>
      <p
        v-if="report.reportable_type === 'App\\Models\\Deal'"
        class="text-muted-foreground"
      >
        Deal n°{{ report.reportable_id }}
      </p>
      <p
        v-if="report.reportable_type === 'App\\Models\\Discussion'"
        class="text-muted-foreground"
      >
        Discussion n°{{ report.reportable_id }}
      </p>
      <p
        v-if="report.reportable_type === 'App\\Models\\CommentDiscussion'"
        class="text-muted-foreground"
      >
        Commentaire discussion n°{{ report.reportable_id }}
      </p>
      <Button v-if="modifyLink" asChild class="w-fit">
        <a target="_blank" :href="modifyLink">
          <ExternalLink class="mr-2 h-4 w-4" />
          Modifier le contenu
        </a>
      </Button>
      <Button v-if="showLink" asChild class="w-fit">
        <a target="_blank" :href="showLink">
          <ExternalLink class="mr-2 h-4 w-4" />
          Voir le message
        </a>
      </Button>
    </div>
  </div>
</template>
