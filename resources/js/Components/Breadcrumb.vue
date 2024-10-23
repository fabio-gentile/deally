<script lang="ts" setup>
import {
  Breadcrumb,
  BreadcrumbItem,
  BreadcrumbLink,
  BreadcrumbList,
  BreadcrumbSeparator,
} from "@/Components/ui/breadcrumb"
import { Link } from "@inertiajs/vue3"

interface Breadcrumb {
  label: string
  route?: string
  params?: Record<string, any>
  query?: string
  active?: boolean
}

// Props to pass dynamic breadcrumbs
const props = defineProps<{
  breadcrumbs: Breadcrumb[]
}>()
</script>

<template>
  <Breadcrumb>
    <BreadcrumbList>
      <BreadcrumbItem v-for="(crumb, index) in breadcrumbs" :key="index">
        <BreadcrumbLink>
          <Link
            v-if="crumb.route"
            :href="
              crumb.params
                ? route(crumb.route, crumb.params) + (crumb.query || '')
                : route(crumb.route) + (crumb.query || '')
            "
            :class="crumb.active ? 'font-semibold text-foreground' : ''"
          >
            {{ crumb.label }}
          </Link>
          <span v-else>{{ crumb.label }}</span>
        </BreadcrumbLink>
        <BreadcrumbSeparator v-if="index < breadcrumbs.length - 1" />
      </BreadcrumbItem>
    </BreadcrumbList>
  </Breadcrumb>
</template>
