<template>
    <AppLayout>
        <Container>
            <div>
                <PageHeading
                    v-text="selectedTopic ? selectedTopic.name : 'All Posts'"
                />
                <p v-if="selectedTopic" class="mt-2 text-sm text-gray-600">
                    {{ selectedTopic.description }}
                </p>
                <menu class="flex pt-1 pb-2 mt-3 space-x-1 overflow-x-auto">
                    <li>
                        <Pill :href="route('posts.index')" :filled="!selectedTopic">All Posts</Pill>
                    </li>
                    <li v-for="topic in topics" :key="topic.id">
                        <Pill :href="route('posts.index', { topic: topic.slug })"
                        :filled="topic.slug === selectedTopic?.slug"
                        >
                            {{ topic.name }}244
                        </Pill>
                    </li>
                </menu>
                <form class="mt-4" @submit.prevent="search">
                    <div>
                        <InputLabel for="query">Search</InputLabel>
                        <div class="flex mt-1 space-x-2">
                            <TextInput class="w-full" id="query" v-model="searchForm.query" placeholder="Search posts..." />
                            <SecondaryButton type="smbmit">Search</SecondaryButton>
                        </div>
                    </div>
                </form>
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
                            >{{ formattedDate(post) }} by
                            {{ post.user.name }}</span
                        >
                    </Link>
                    <Pill :href="route('posts.index', { topic: post.topic.slug })">
                        {{ post.topic.name }}
                    </Pill>
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
import { Link, useForm } from "@inertiajs/vue3";
import { formatDistance, parseISO } from "date-fns";
import { relativeDate } from "@/Utilities/date.js";
import PageHeading from "@/Components/pageHeading.vue";
import Pill from "@/Components/Pill.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const props = defineProps(["posts", "topics", "selectedTopic", "query"]);

const formattedDate = (post) => relativeDate(post.created_at);

const searchForm = useForm({
    query: props.query,
});

const search = () => searchForm.get(route('posts.index', { search: searchForm.query }));
</script>
