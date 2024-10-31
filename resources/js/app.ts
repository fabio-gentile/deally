import "../css/app.css"
import "./bootstrap"

import { createInertiaApp } from "@inertiajs/vue3"
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers"
import { createApp, DefineComponent, h } from "vue"
import { ZiggyVue } from "../../vendor/tightenco/ziggy"
import MainLayout from "@/Components/layout/MainLayout.vue"
import AdminLayout from "@/Components/layout/AdminLayout.vue"

const appName = import.meta.env.VITE_APP_NAME || "Laravel"

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  // resolve: (name) =>
  //   resolvePageComponent(
  //     `./Pages/${name}.vue`,
  //     import.meta.glob<DefineComponent>("./Pages/**/*.vue")
  //   ),
  resolve: async (name) => {
    const page = await resolvePageComponent(
      `./Pages/${name}.vue`,
      import.meta.glob<DefineComponent>("./Pages/**/*.vue")
    )
    const isAdminRoute = route().current()?.includes("admin")
    page.default.layout =
      page.default.layout || (isAdminRoute ? AdminLayout : MainLayout)

    return page
  },

  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ZiggyVue)
      .mount(el)
  },
  progress: {
    color: "#4B5563",
  },
})
