<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3"
import { useEditor, EditorContent } from "@tiptap/vue-3"
import StarterKit from "@tiptap/starter-kit"
import Underline from "@tiptap/extension-underline"
import {
  Bold,
  Italic,
  Underline as UnderlineIcon,
  Heading1,
  Heading2,
  ListIcon,
  ListOrderedIcon,
  TextQuote,
  CodeIcon,
  UndoIcon,
  RedoIcon,
  Minus,
} from "lucide-vue-next"

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
  extensions: [StarterKit, Underline],
  editorProps: {
    attributes: {
      class:
        "p-2 rounded-bl-lg rounded-br-lg border min-h-[12rem] max-h-[12rem] overflow-y-auto outline-none prose max-w-none",
    },
  },
})
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
