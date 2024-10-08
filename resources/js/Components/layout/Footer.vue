<script setup lang="ts">
import Wrapper from "@/Components/layout/Wrapper.vue"
import { Link } from "@inertiajs/vue3"
import Separator from "@/Components/ui/separator/Separator.vue"
</script>
<template>
  <footer class="w-full border-t bg-primary p-4">
    <Wrapper>
      <nav
        class="flex flex-col gap-8 text-white md:flex-row md:gap-28 lg:gap-40"
      >
        <div class="flex flex-col gap-4">
          <h3 class="font-semibold">Découvrir</h3>
          <ul class="flex flex-col gap-2 text-sm">
            <li>
              <Link class="hover:underline" href="/">Les bons plans</Link>
            </li>
            <li><Link class="hover:underline" href="/about">Forum</Link></li>
            <li>
              <Link class="hover:underline" href="/contact">Actualités</Link>
            </li>
          </ul>
        </div>
        <div class="flex flex-col gap-4">
          <h3 class="font-semibold">Entreprise</h3>
          <ul class="flex flex-col gap-2 text-sm">
            <li><Link class="hover:underline" href="/">À propos</Link></li>
            <li>
              <Link class="hover:underline" href="/about"
                >Foire aux questions</Link
              >
            </li>
            <li>
              <Link class="hover:underline" href="/contact">Contact</Link>
            </li>
          </ul>
        </div>
        <div class="flex flex-col gap-4">
          <h3 class="font-semibold">Légal</h3>
          <ul class="flex flex-col gap-2 text-sm">
            <li>
              <Link class="hover:underline" href="/">Conditions générales</Link>
            </li>
            <li>
              <Link class="hover:underline" href="/about"
                >Politique d'utilisation des cookies</Link
              >
            </li>
            <li>
              <Link class="hover:underline" href="/contact"
                >Politique de confidentialité</Link
              >
            </li>
            <li>
              <Link class="hover:underline" href="/contact"
                >Mention légales</Link
              >
            </li>
          </ul>
        </div>
      </nav>
      <Separator class="my-4" />
      <div class="flex flex-wrap items-center justify-between gap-4 text-white">
        <h1 class="font-bold">
          <Link :href="route('home.index')"> Deally </Link>
        </h1>
        <p class="text-sm">
          Copyright 2023 - 2024. Deally. Tous droits réservés
        </p>
      </div>
    </Wrapper>
  </footer>
</template>
