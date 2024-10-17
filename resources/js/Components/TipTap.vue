<script setup>
import {
  Popover,
  PopoverContent,
  PopoverTrigger,
} from "@/Components/ui/popover"
import { Button } from "@/Components/ui/button"
import { useEditor, EditorContent } from "@tiptap/vue-3"
import { Link as LinkExtension } from "@tiptap/extension-link"
import StarterKit from "@tiptap/starter-kit"
import Underline from "@tiptap/extension-underline"
import {
  Bold,
  Italic,
  Underline as UnderlineIcon,
  Heading1,
  Heading2,
  Heading3,
  ListIcon,
  ListOrderedIcon,
  TextQuote,
  CodeIcon,
  UndoIcon,
  RedoIcon,
  Minus,
  Link2,
  Link2Off,
} from "lucide-vue-next"
import Input from "@/Components/ui/input/Input.vue"
import Label from "@/Components/ui/label/Label.vue"

const props = defineProps({
  modelValue: String,
})

const emit = defineEmits(["update:modelValue"])

const editor = useEditor({
  content: props.modelValue,
  onUpdate: ({ editor }) => {
    // console.log(editor.getHTML())
    emit("update:modelValue", editor.getHTML())
  },
  extensions: [
    StarterKit,
    Underline,
    LinkExtension.configure({
      HTMLAttributes: { target: "_blank", class: "!underline" },
      openOnClick: true,
      defaultProtocol: "https",
    }),
  ],
  editorProps: {
    attributes: {
      class:
        "p-2 rounded-bl-lg rounded-br-lg border min-h-[12rem] bg-white dark:primary-foreground max-h-[12rem] overflow-y-auto outline-none prose max-w-none",
    },
  },
})

const url = defineModel("url")

const setLink = () => {
  const previousUrl = editor.value.getAttributes("link").href

  // cancelled
  if (url.value === null) {
    return
  }

  // empty
  if (url.value === "") {
    editor.value.chain().focus().extendMarkRange("link").unsetLink().run()

    return
  }

  // update link
  editor.value
    .chain()
    .focus()
    .extendMarkRange("link")
    .setLink({ href: url.value })
    .run()
}
</script>

<template>
  <div>
    <section
      v-if="editor"
      class="buttons flex flex-wrap items-center gap-x-4 rounded-tl-lg rounded-tr-lg border bg-white p-2 text-muted-foreground dark:bg-primary-foreground"
    >
      <button
        type="button"
        @click="editor.chain().focus().toggleBold().run()"
        :class="{ 'rounded bg-secondary': editor.isActive('bold') }"
        class="p-1"
      >
        <Bold title="Gras" />
      </button>
      <button
        type="button"
        @click="editor.chain().focus().toggleItalic().run()"
        :class="{ 'rounded bg-secondary': editor.isActive('italic') }"
        class="p-1"
      >
        <Italic title="Italique" />
      </button>
      <button
        type="button"
        @click="editor.chain().focus().toggleUnderline().run()"
        :class="{ 'rounded bg-secondary': editor.isActive('underline') }"
        class="p-1"
      >
        <UnderlineIcon title="Souligner" />
      </button>
      <button
        type="button"
        @click="editor.chain().focus().toggleHeading({ level: 1 }).run()"
        :class="{
          'rounded bg-secondary': editor.isActive('heading', { level: 1 }),
        }"
        class="p-1"
      >
        <Heading1 title="Titre 1" />
      </button>
      <button
        type="button"
        @click="editor.chain().focus().toggleHeading({ level: 2 }).run()"
        :class="{
          'rounded bg-secondary': editor.isActive('heading', { level: 2 }),
        }"
        class="p-1"
      >
        <Heading2 title="Titre 2" />
      </button>
      <button
        type="button"
        @click="editor.chain().focus().toggleHeading({ level: 3 }).run()"
        :class="{
          'rounded bg-secondary': editor.isActive('heading', { level: 3 }),
        }"
        class="p-1"
      >
        <Heading3 title="Titre 3" />
      </button>
      <Popover>
        <PopoverTrigger>
          <Link2 title="Lien" />
        </PopoverTrigger>
        <PopoverContent class="space-y-2">
          <Label for="url">Lien</Label>
          <Input
            @keyup.enter="setLink"
            type="text"
            id="url"
            placeholder="https://www.google.fr"
            v-model="url"
          />
          <div class="flex w-full justify-end">
            <Button
              :class="{ 'is-active': editor.isActive('link') }"
              class="!mt-4 ml-auto"
              @click="setLink"
              >Ajouter</Button
            >
          </div>
        </PopoverContent>
      </Popover>
      <button
        @click="editor.chain().focus().unsetLink().run()"
        :disabled="!editor.isActive('link')"
      >
        <Link2Off title="Défaire le lien" />
      </button>
      <button
        type="button"
        @click="editor.chain().focus().toggleBulletList().run()"
        :class="{ 'rounded bg-secondary': editor.isActive('bulletList') }"
        class="p-1"
      >
        <ListIcon title="Liste à puces" />
      </button>
      <button
        type="button"
        @click="editor.chain().focus().toggleOrderedList().run()"
        :class="{ 'rounded bg-secondary': editor.isActive('orderedList') }"
        class="p-1"
      >
        <ListOrderedIcon title="Liste ordonnée" />
      </button>
      <button
        type="button"
        @click="editor.chain().focus().toggleBlockquote().run()"
        :class="{ 'rounded bg-secondary': editor.isActive('blockquote') }"
        class="p-1"
      >
        <TextQuote title="Citation de bloc" />
      </button>
      <button
        type="button"
        @click="editor.chain().focus().toggleCode().run()"
        :class="{ 'rounded bg-secondary': editor.isActive('code') }"
        class="p-1"
      >
        <CodeIcon title="Code" />
      </button>
      <button
        type="button"
        @click="editor.chain().focus().setHorizontalRule().run()"
        class="p-1"
      >
        <Minus title="Règle horizontale" />
      </button>
      <button
        type="button"
        class="p-1 disabled:text-gray-400"
        @click="editor.chain().focus().undo().run()"
        :disabled="!editor.can().chain().focus().undo().run()"
      >
        <UndoIcon title="Défaire" />
      </button>
      <button
        type="button"
        @click="editor.chain().focus().redo().run()"
        :disabled="!editor.can().chain().focus().redo().run()"
        class="p-1 disabled:text-gray-400"
      >
        <RedoIcon title="Refaire" />
      </button>
    </section>
    <EditorContent :editor="editor" />
  </div>
</template>
