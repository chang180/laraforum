<template>
    <AppLayout>
        <Container>
            <h1 class="text-2xl font-bold">{{ post.title }}</h1>
            <span class="block text-sm text-gray-600">{{ formattedDate }} ago by {{ post.user.name }}</span>
            <p class="mt-4">
                <pre class="font-sans whitespace-pre-wrap">
                    {{ post.body }}
                </pre>
            </p>
            <div class="mt-8">
                <h2 class="text-xl font-bold">Comments</h2>
                <ul class="mt-4 divide-y">
                    <li v-for="comment in comments.data" :key="comment.id" class="px-2 py-4">
                        <Comment :comment="comment" />
                    </li>
                </ul>
                <Pagination :meta="comments.meta" />
            </div>
        </Container>
    </AppLayout>
</template>
<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { computed } from 'vue';
// import { formatDistance, parseISO } from 'date-fns';
import {relativeDate} from "@/Utilities/date.js";

import Container from "@/Components/Container.vue";
import Pagination from "@/Components/Pagination.vue";
import Comment from "@/Components/Comment.vue";

const props = defineProps(['post', 'comments']);

const formattedDate = computed(() => relativeDate(props.post.created_at));
</script>
