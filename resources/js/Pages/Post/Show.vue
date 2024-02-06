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

                <form v-if="$page.props.auth.user" @submit.prevent="addComment" class="mt-4">
                    <div>
                        <InputLabel for="body" class="sr-only">Comment</InputLabel>
                        <TextareaInput v-model="commentForm.body" id="body" rows="4" placeholder="Put in some words..."/>
                        <InputError :message="commentForm.errors.body" class="mt-2" />
                    </div>
                    <PrimaryButton class="mt-4" :disabled="commentForm.processing">Add Comment</PrimaryButton>
                </form>
                <ul class="mt-4 divide-y">
                    <li v-for="comment in comments.data" :key="comment.id" class="px-2 py-4">
                        <Comment @delete="deleteComment" :comment="comment" />
                    </li>
                </ul>
                <Pagination :meta="comments.meta" :only="['comments']" />
            </div>
        </Container>
    </AppLayout>
</template>
<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";

import { computed } from 'vue';
// import { formatDistance, parseISO } from 'date-fns';
import {relativeDate} from "@/Utilities/date.js";
import { useForm, router } from "@inertiajs/vue3";

import Container from "@/Components/Container.vue";
import Pagination from "@/Components/Pagination.vue";
import Comment from "@/Components/Comment.vue";
import TextareaInput from "@/Components/TextareaInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";

const props = defineProps(['post', 'comments']);

const formattedDate = computed(() => relativeDate(props.post.created_at));

const commentForm = useForm({
    body: ''
});

const addComment = () => commentForm.post(route('posts.comments.store', props.post.id),{
    preserveScroll: true,
    onSuccess: () => commentForm.reset()
});

const deleteComment = (commentID) => {
    if (confirm("Are you sure you want to delete this comment?")) {
        router.delete(route("comments.destroy", { comment: commentID, page: props.comments.meta.current_page}), {
            preserveScroll: true,
        });
    }
};
</script>
