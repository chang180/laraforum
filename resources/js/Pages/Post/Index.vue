<template>
    <AppLayout>
        <Container>
            <ul class="devide-y">
                <li v-for="post in posts.data" :key="post.id" class="px-2 py-4">
                    <span class="text-lg font-bold">{{ post.title }}</span>
                </li>
            </ul>
        </Container>
        <Pagination :meta="posts.meta" :links="posts.links" />
    </AppLayout>
</template>
<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Container from "@/Components/Container.vue";
import Pagination from "@/Components/Pagination.vue";

defineProps(["posts"]);
</script>
