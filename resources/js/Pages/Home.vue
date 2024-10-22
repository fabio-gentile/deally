<script setup lang="ts">
import { Head, Link } from "@inertiajs/vue3"
import Button from "@/Components/ui/button/Button.vue"
import { CategoryDeal } from "@/types/model/deal"
import Wrapper from "@/Components/layout/Wrapper.vue"
import {
  Carousel,
  CarouselContent,
  CarouselItem,
  CarouselNext,
  CarouselPrevious,
} from "@/Components/ui/carousel"
import CardDeal from "@/Components/Deal/CardDeal.vue"
import { Deal } from "@/types/model/deal"
import HomeDiscussion from "@/Components/Home/HomeDiscussion.vue"
import { Discussion } from "@/types/model/discussion"
import { Blog } from "@/types/model/blog"
import HomeBlog from "@/Components/Home/HomeBlog.vue"

defineProps<{
  categories: CategoryDeal[]
  deals: Deal[]
  discussions: Discussion[]
  blogs: Blog[]
}>()
</script>

<template>
  <div class="w-full pb-8">
    <Head title="Accueil" />
    <div class="bg-primary py-4 dark:bg-primary-foreground">
      <Wrapper class="max-w-2xl lg:max-w-4xl">
        <Carousel
          class="relative flex w-full max-w-full"
          :opts="{
            align: 'start',
            dragFree: true,
          }"
          v-slot="{ canScrollNext, canScrollPrev }"
        >
          <div class="overflow-hidden">
            <CarouselContent class="mx-auto gap-3">
              <CarouselItem
                v-for="category in categories"
                :key="category.slug"
                class="flex basis-auto !pl-0"
              >
                <!--                TODO: add route redirection-->
                <Link
                  :href="route('search.deals') + '?category=' + category.name"
                >
                  <Button variant="outline" class="font-semibold lg:ml-3">
                    {{ category.name }}
                  </Button>
                </Link>
              </CarouselItem>
            </CarouselContent>
          </div>
          <CarouselPrevious
            v-if="canScrollPrev"
            class="absolute -left-10 hidden md:flex"
          />
          <CarouselNext
            v-if="canScrollNext"
            class="- absolute -right-10 hidden md:flex"
          />
        </Carousel>
      </Wrapper>
    </div>
    <div class="border-b bg-background py-3">
      <Wrapper>
        <div class="flex gap-6 font-semibold">
          <Link :href="route('home.index')" class="text-primary"
            >Populaire</Link
          >
          <Link :href="route('home.new')" class="text-muted-foreground"
            >Nouveauté</Link
          >
          <Link :href="route('home.for-you')" class="text-muted-foreground"
            >Pour vous</Link
          >
        </div>
      </Wrapper>
    </div>

    <main class="bg-page">
      <Wrapper class="pt-8">
        <div class="flex flex-col gap-3 lg:flex-row lg:gap-6">
          <div class="flex grow flex-col gap-3">
            <CardDeal
              v-for="(deal, index) in deals"
              :key="index"
              :deal="deal"
            />
          </div>
          <div
            class="flex w-full shrink-0 flex-col justify-between gap-4 md:flex-row lg:w-fit lg:flex-col lg:justify-normal"
          >
            <HomeDiscussion
              class="md:w-[calc(50%-12px)] lg:w-full"
              :discussions="discussions"
            />
            <HomeBlog class="md:w-[calc(50%-12px)] lg:w-full" :blogs="blogs" />
          </div>
        </div>
      </Wrapper>
    </main>
  </div>
</template>
