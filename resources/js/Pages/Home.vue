<script setup lang="ts">
import { Head, Link } from "@inertiajs/vue3"
import Button from "@/Components/ui/button/Button.vue"
import { CategoryDeal } from "@/types/model/category-deal"
import Wrapper from "@/Pages/Layout/Wrapper.vue"
import {
  Carousel,
  CarouselContent,
  CarouselItem,
  CarouselNext,
  CarouselPrevious,
} from "@/Components/ui/carousel"
import { Card, CardContent } from "@/Components/ui/card"

defineProps<{
  categories: CategoryDeal[]
}>()
</script>

<template>
  <div>
    <Head title="Accueil" />
    <div class="bg-primary py-4">
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
            <CarouselContent class="mx-auto">
              <CarouselItem
                v-for="category in categories"
                :key="category.slug"
                class="flex basis-auto"
              >
                <!--                TODO: add route redirection-->
                <Link href="#">
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
  </div>
</template>
