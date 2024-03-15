<template>
    <div
        v-if="editor"
        class="bg-white border-0 rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-set focus:ring-indigo-600"
    >
        <menu class="flex border-b divide-x">
            <li>
                <button
                    type="button"
                    class="px-3 py-2 rounded-tl-md"
                    title="Bold"
                    @click="() => editor.chain().focus().toggleBold().run()"
                    :class="[
                        editor.isActive('bold')
                            ? 'bg-indigo-500 text-white'
                            : 'hover:bg-gray-200',
                    ]"
                >
                    <i class="ri-bold"></i>
                </button>
            </li>
            <li>
                <button
                    type="button"
                    class="px-3 py-2"
                    title="Italic"
                    @click="() => editor.chain().focus().toggleItalic().run()"
                    :class="[
                        editor.isActive('italic')
                            ? 'bg-indigo-500 text-white'
                            : 'hover:bg-gray-200',
                    ]"
                >
                    <i class="ri-italic"></i>
                </button>
            </li>
            <li>
                <button
                    type="button"
                    class="px-3 py-2"
                    title="Strike"
                    @click="() => editor.chain().focus().toggleStrike().run()"
                    :class="[
                        editor.isActive('strike')
                            ? 'bg-indigo-500 text-white'
                            : 'hover:bg-gray-200',
                    ]"
                >
                    <i class="ri-strikethrough"></i>
                </button>
            </li>
            <li>
                <button
                    type="button"
                    class="px-3 py-2"
                    title="Blockquote"
                    @click="
                        () => editor.chain().focus().toggleBlockquote().run()
                    "
                    :class="[
                        editor.isActive('blockquote')
                            ? 'bg-indigo-500 text-white'
                            : 'hover:bg-gray-200',
                    ]"
                >
                    <i class="ri-double-quotes-l"></i>
                </button>
            </li>
            <li>
                <button
                    type="button"
                    class="px-3 py-2"
                    title="Bullet List"
                    @click="
                        () => editor.chain().focus().toggleBulletList().run()
                    "
                    :class="[
                        editor.isActive('bulletList')
                            ? 'bg-indigo-500 text-white'
                            : 'hover:bg-gray-200',
                    ]"
                >
                    <i class="ri-list-unordered"></i>
                </button>
            </li>
            <li>
                <button
                    type="button"
                    class="px-3 py-2"
                    title="Add Link"
                    @click="promptUserForLink"
                    :class="[
                        editor.isActive('link')
                            ? 'bg-indigo-500 text-white'
                            : 'hover:bg-gray-200',
                    ]"
                >
                    <i class="ri-link"></i>
                </button>
            </li>
            <li>
                <button
                    type="button"
                    class="px-3 py-2"
                    title="Ordered List"
                    @click="
                        () => editor.chain().focus().toggleOrderedList().run()
                    "
                    :class="[
                        editor.isActive('orderedList')
                            ? 'bg-indigo-500 text-white'
                            : 'hover:bg-gray-200',
                    ]"
                >
                    <i class="ri-list-ordered"></i>
                </button>
            </li>
            <li>
                <button
                    type="button"
                    class="px-3 py-2"
                    title="Heading 1"
                    @click="
                        () =>
                            editor
                                .chain()
                                .focus()
                                .toggleHeading({ level: 2 })
                                .run()
                    "
                    :class="[
                        editor.isActive('heading', { level: 2 })
                            ? 'bg-indigo-500 text-white'
                            : 'hover:bg-gray-200',
                    ]"
                >
                    <i class="ri-h-1"></i>
                </button>
            </li>
            <li>
                <button
                    type="button"
                    class="px-3 py-2"
                    title="Heading 2"
                    @click="
                        () =>
                            editor
                                .chain()
                                .focus()
                                .toggleHeading({ level: 3 })
                                .run()
                    "
                    :class="[
                        editor.isActive('heading', { level: 3 })
                            ? 'bg-indigo-500 text-white'
                            : 'hover:bg-gray-200',
                    ]"
                >
                    <i class="ri-h-2"></i>
                </button>
            </li>
            <li>
                <button
                    type="button"
                    class="px-3 py-2"
                    title="Heading 3"
                    @click="
                        () =>
                            editor
                                .chain()
                                .focus()
                                .toggleHeading({ level: 4 })
                                .run()
                    "
                    :class="[
                        editor.isActive('heading', { level: 4 })
                            ? 'bg-indigo-500 text-white'
                            : 'hover:bg-gray-200',
                    ]"
                >
                    <i class="ri-h-3"></i>
                </button>
            </li>
        </menu>
        <EditorContent :editor="editor" />
    </div>
</template>

<script setup>
import StarterKit from "@tiptap/starter-kit";
import { EditorContent, useEditor } from "@tiptap/vue-3";
import { Markdown } from "tiptap-markdown";
import { Link } from "@tiptap/extension-link";
import { watch } from "vue";
import "remixicon/fonts/remixicon.css";

const props = defineProps({
    modelValue: "",
});

const emit = defineEmits(["update:modelValue"]);

const editor = useEditor({
    extensions: [
        StarterKit.configure({
            heading: {
                levels: [2, 3, 4],
            },
            code: false,
            codeBlock: false,
        }),
        Link,
        Markdown,
    ],
    editorProps: {
        attributes: {
            class: "min-h-[512px] prose prose-sm max-w-none w-full py-1.5 px-3",
        },
    },
    onUpdate: () => {
        emit("update:modelValue", editor.value?.storage.markdown.getMarkdown());
    },
});

watch(
    () => props.modelValue,
    (value) => {
        if (value === editor.value?.storage.markdown.getMarkdown()) return;
        editor.value?.commands.setContent(value);
    },
    { immediate: true }
);

const promptUserForLink = () => {
    if (editor.value?.isActive("link")) {
        return editor.value?.chain().focus().unsetLink().run();
    }
    const url = prompt("Enter the URL for the link:");
    if (!url) return;
    return editor.value
        ?.chain()
        .focus()
        .setLink({ href: url, target: "_blank" })
        .run();
};
</script>
