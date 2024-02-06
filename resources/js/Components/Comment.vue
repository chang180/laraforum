<template>
    <div class="sm:flex">
        <div class="flex-shrink-0 mb-4 sm:mb-0 sm:mr-4">
            <img
                :src="comment.user.profile_photo_url"
                class="w-10 h-10 rounded-full"
            />
        </div>
        <div class="flex-1">
            <p class="mt-1 break-all">{{ comment.body }}</p>
            <span
                class="block pt-1 text-xs text-gray-600 first-letter:uppercase"
                >By {{ comment.user.name }}
                {{ relativeDate(comment.created_at) }} ago</span
            >
            <div class="mt-2 text-right empty:hidden" v-if="comment.can?.delete">
                <form @submit.prevent="$emit('delete', comment.id)">
                    <button class="font-mono text-xs text-red-700 hover:font-semibold">Delete</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { relativeDate } from "@/Utilities/date.js";
import { router, usePage } from "@inertiajs/vue3";
import { defineProps, computed } from "vue";

const props = defineProps(["comment"]);

const emit = defineEmits(["delete"]);

</script>
