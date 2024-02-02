<template>
    <AppLayout>
        <Container>
            <ul class="devide-y">
                <li v-for="post in posts.data" :key="post.id" class="">
                    <Link :href="route('posts.show', post.id)" class="block px-2 py-4 group">
                        <span class="text-lg font-bold group-hover:text-indigo-500">{{ post.title }}</span>
                        <span class="block text-sm text-gray-600">{{ formattedDate(post) }} ago by {{ post.user.name }}</span>
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

defineProps(["posts"]);

const formattedDate = (post) => {
    return formatDistance(parseISO(post.created_at), new Date());
};
</script>
