<script setup lang="ts">
import { Link, router } from "@inertiajs/vue3"
import { ArrowDown, ArrowUp } from "lucide-vue-next"
import { Deal } from "@/types/model/deal"
import { ref } from "vue"
import { VoteDeals } from "@/types/model/deal"
const { votes, vote, deal, isExpired } = defineProps<{
  votes: number
  vote?: VoteDeals | boolean
  deal: Deal
  isExpired: boolean
}>()

const voteCount = ref(votes)
const currentVote = ref({
  //@ts-ignore
  type: vote?.type,
  //@ts-ignore
  hasVoted: vote?.deal_id === deal.id || false,
})

const handleVote = (type: "up" | "down") => {
  if (isExpired) {
    return
  }

  if (currentVote.value.hasVoted) {
    return
  }

  currentVote.value.type = type

  router.post(
    route("deals.vote.store", { id: deal.id }),
    { type },
    {
      preserveState: true,
      preserveScroll: true,
      onSuccess: () => {
        currentVote.value.hasVoted = true
        currentVote.value.type = type
        voteCount.value =
          type === "up" ? voteCount.value + 1 : voteCount.value - 1
      },
    }
  )
}
</script>

<template>
  <div
    class="flex w-fit gap-3 rounded-lg bg-border p-2"
    :class="isExpired ? '!pointer-events-none opacity-50' : ''"
  >
    <Link
      :disabled="isExpired"
      v-if="!$page.props.auth.user"
      :href="route('login')"
    >
      <ArrowDown
        @click="handleVote('down')"
        :class="[
          isExpired ? 'opacity-50' : '',
          currentVote.hasVoted ? 'pointer-events-none' : 'cursor-pointer',
          currentVote.type === 'down'
            ? 'text-primary'
            : 'text-muted-foreground',
          currentVote.type === 'up' && currentVote.hasVoted ? 'opacity-15' : '',
          'transition-all ease-in-out',
        ]"
      />
    </Link>
    <ArrowDown
      v-else
      @click="handleVote('down')"
      :class="[
        currentVote.hasVoted ? 'pointer-events-none' : 'cursor-pointer',
        currentVote.type === 'down' ? 'text-primary' : 'text-muted-foreground',
        currentVote.type === 'up' && currentVote.hasVoted ? 'opacity-15' : '',
        'transition-all ease-in-out',
      ]"
    />
    <span
      :class="[
        currentVote.hasVoted ? 'font-bold text-primary' : '',
        voteCount >= 50 ? 'text-primary' : 'text-muted-foreground',
        'transition-all ease-in-out',
      ]"
      >{{ voteCount }}</span
    >
    <Link v-if="!$page.props.auth.user" :href="route('login')">
      <ArrowUp
        @click="handleVote('up')"
        :class="[
          currentVote.hasVoted ? 'pointer-events-none' : 'cursor-pointer',
          currentVote.type === 'up'
            ? 'text-lg font-bold text-primary'
            : 'text-muted-foreground',
          currentVote.type === 'down' && currentVote.hasVoted
            ? 'opacity-15'
            : '',
          'transition-all ease-in-out',
        ]"
      />
    </Link>
    <ArrowUp
      v-else
      @click="handleVote('up')"
      :class="[
        currentVote.hasVoted ? 'pointer-events-none' : 'cursor-pointer',
        currentVote.type === 'up'
          ? 'text-lg font-bold text-primary'
          : 'text-muted-foreground',
        currentVote.type === 'down' && currentVote.hasVoted ? 'opacity-15' : '',
        'transition-all ease-in-out',
      ]"
    />
  </div>
</template>
