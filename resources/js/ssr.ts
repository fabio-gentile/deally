import { createInertiaApp } from "@inertiajs/vue3"
import createServer from "@inertiajs/vue3/server"
import { renderToString } from "@vue/server-renderer"
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers"
import { createSSRApp, DefineComponent, h } from "vue"
import { ZiggyVue } from "../../vendor/tightenco/ziggy"
import MainLayout from "@/Components/layout/MainLayout.vue"
import AdminLayout from "@/Components/layout/AdminLayout.vue"

const appName = import.meta.env.VITE_APP_NAME || "Laravel"

createServer((page) =>
  createInertiaApp({
    page,
    render: renderToString,
    title: (title) => `${title} - ${appName}`,
    // resolve: (name) =>
    //     resolvePageComponent(
    //         `./Pages/${name}.vue`,
    //         import.meta.glob<DefineComponent>('./Pages/**/*.vue'),
    //     ),
    resolve: async (name) => {
      const page = await resolvePageComponent(
        `./Pages/${name}.vue`,
        import.meta.glob<DefineComponent>("./Pages/**/*.vue")
      )
      const isAdminRoute = window.location.pathname.startsWith("/admin")
      page.default.layout =
        page.default.layout || (isAdminRoute ? AdminLayout : MainLayout)
      return page
    },
    setup({ App, props, plugin }) {
      return createSSRApp({ render: () => h(App, props) })
        .use(plugin)
        .use(ZiggyVue, {
          ...page.props.ziggy,
          location: new URL(page.props.ziggy.location),
        })
    },
  })
)
