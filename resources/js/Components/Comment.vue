<template>
    <div class="sm:flex">
        <div class="flex-shrink-0 mb-4 sm:mb-0 sm:mr-4">
            <img
                :src="comment.user.profile_photo_url"
                class="w-10 h-10 rounded-full"
            />
        </div>
        <div class="flex-1">
            <div
                class="mt-1 prose-sm prose max-w-none"
                v-html="comment.html"
            ></div>
            <span
                class="block pt-1 text-xs text-gray-600 first-letter:uppercase"
                >By {{ comment.user.name }}
                {{ relativeDate(comment.created_at) }} |
                <span class="text-pink-500"
                    >{{ comment.likes_count }} like(s)</span
                >
            </span>
            <div class="flex justify-end mt-2 space-x-3 empty:hidden">
                <div v-if="$page.props.auth.user">
                    <Link
                        v-if="comment.can.like"
                        :href="route('likes.store', ['comment', comment.id])"
                        class="inline-block text-gray-700 transition-colors preserve-scroll hover:text-pink-500"
                        method="post"
                    >
                        <HandThumbUpIcon class="inline-block mr-1 size-4" />
                        <span class="sr-only">Like Comment</span>
                    </Link>
                    <Link
                        v-else
                        :href="route('likes.destroy', ['comment', comment.id])"
                        class="inline-block text-gray-700 transition-colors preserve-scroll hover:text-pink-500"
                        method="delete"
                    >
                        <HandThumbDownIcon class="inline-block mr-1 size-4" />
                        <span class="sr-only">Unlike Comment</span>
                    </Link>
                </div>
                <div v-if="comment.can?.update" class="flex space-x-2">
                    <form @submit.prevent="$emit('edit', comment.id)">
                        <button
                            class="font-mono text-xs text-blue-700 hover:font-semibold"
                        >
                            Edit
                        </button>
                    </form>
                    <form
                        @submit.prevent="$emit('delete', comment.id)"
                        v-if="comment.can?.delete"
                    >
                        <button
                            class="font-mono text-xs text-red-700 hover:font-semibold"
                        >
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { relativeDate } from "@/Utilities/date.js";
import { defineProps, computed } from "vue";
import { Link } from "@inertiajs/vue3";
import { HandThumbUpIcon, HandThumbDownIcon } from "@heroicons/vue/20/solid";

const props = defineProps(["comment"]);

const emit = defineEmits(["delete", "edit"]);
</script>
