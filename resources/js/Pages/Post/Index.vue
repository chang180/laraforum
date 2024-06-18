<template>
    <AppLayout>
        <Container>
            <div>
                <Link :href="route('posts.index')" v-if="selectedTopic">
                    <span
                        class="block mb-2 text-sm text-indigo-500 hover:text-indigo-700"
                        >← Back to all posts</span
                    >
                </Link>
                <PageHeading
                    v-text="selectedTopic ? selectedTopic.name : 'All Posts'"
                />
                <p v-if="selectedTopic" class="mt-2 text-sm text-gray-600">
                    {{ selectedTopic.description }}
                </p>
            </div>
            <ul class="mt-4 devide-y">
                <li
                    v-for="post in posts.data"
                    :key="post.id"
                    class="flex flex-col items-baseline justify-between md:flex-row"
                >
                    <Link
                        :href="post.routes.show"
                        class="block px-2 py-4 group"
                    >
                        <span
                            class="text-lg font-bold group-hover:text-indigo-500"
                            >{{ post.title }}</span
                        >
                        <span class="block text-sm text-gray-600"
                            >{{ formattedDate(post) }} ago by
                            {{ post.user.name }}</span
                        >
                    </Link>
                    <Link
                        :href="route('posts.index', { topic: post.topic.slug })"
                        class="rounded-full py-0.5 px-2 border border-pink-500 text-pink-500 hover:bg-indigo-500 hover:text-indigo-50 mb-2"
                    >
                        {{ post.topic.name }}
                    </Link>
                </li>
            </ul>
        </Container>
        <Pagination :meta="posts.meta" />
    </AppLayout>
</template>
<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Container from "@/Components/Container.vue";
import Pagination from "@/Components/Pagination.vue";
import { Link } from "@inertiajs/vue3";
import { formatDistance, parseISO } from "date-fns";
import { relativeDate } from "@/Utilities/date.js";
import PageHeading from "@/Components/pageHeading.vue";

defineProps(["posts", "selectedTopic"]);

const formattedDate = (post) => relativeDate(post.created_at);
</script>
