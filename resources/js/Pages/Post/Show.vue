<template>
    <AppLayout :title="post.title">
        <Container>
            <Pill :href="route('posts.index', { topic: post.topic.slug })"
                >{{ post.topic.name }}</Pill>
            <PageHeading class="mt-2">{{ post.title }}</PageHeading>
            <span class="block text-sm text-gray-600"
                >{{ formattedDate }} ago by {{ post.user.name }}</span
            >
            <article class="mt-6 prose-sm prose max-w-none" v-html="post.html"></article>
            <div class="mt-8">
                <h2 class="text-xl font-bold">Comments</h2>

                <form
                    v-if="$page.props.auth.user"
                    @submit.prevent="
                        () =>
                            commentBeingEdit ? updateComment() : addComment()
                    "
                    class="mt-4"
                >
                    <div>
                        <InputLabel for="body" class="sr-only"
                            >Comment</InputLabel
                        >
                        <MarkdownEditor
                            ref="commentTextareaRef"
                            v-model="commentForm.body"
                            id="body"
                            placeholder="Put in some words..."
                            editorClass="min-h-[160px]"
                        />
                        <InputError
                            :message="commentForm.errors.body"
                            class="mt-2"
                        />
                    </div>
                    <PrimaryButton
                        class="mt-4"
                        :disabled="commentForm.processing"
                        v-text="
                            commentIdBeingEdited
                                ? 'Update Coment'
                                : 'Add Comment'
                        "
                    ></PrimaryButton>
                    <SecondaryButton
                        v-if="commentIdBeingEdited"
                        @click="cancelEditComment"
                        class="m-4"
                        >Cancel</SecondaryButton
                    >
                </form>
                <ul class="mt-4 divide-y">
                    <li
                        v-for="comment in comments.data"
                        :key="comment.id"
                        class="px-2 py-4"
                    >
                        <Comment
                            @delete="deleteComment"
                            @edit="editComment"
                            :comment="comment"
                        />
                    </li>
                </ul>
                <Pagination :meta="comments.meta" :only="['comments']" />
            </div>
        </Container>
    </AppLayout>
</template>
<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";

import { computed, ref } from "vue";
// import { formatDistance, parseISO } from 'date-fns';
import { relativeDate } from "@/Utilities/date.js";
import { useForm, router } from "@inertiajs/vue3";

import Container from "@/Components/Container.vue";
import Pagination from "@/Components/Pagination.vue";
import Comment from "@/Components/Comment.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { useConfirm } from "@/Utilities/Composables/useComfirm";
import MarkdownEditor from "@/Components/MarkdownEditor.vue";
import PageHeading from "@/Components/pageHeading.vue";
import Pill from "@/Components/Pill.vue";

const props = defineProps(["post", "comments"]);

const formattedDate = computed(() => relativeDate(props.post.created_at));

const commentForm = useForm({
    body: "",
});

const commentIdBeingEdited = ref(null);

const commentTextareaRef = ref(null);

const commentBeingEdit = computed(() =>
    props.comments.data.find(
        (comment) => comment.id === commentIdBeingEdited.value
    )
);

const editComment = (commentID) => {
    commentIdBeingEdited.value = commentID;
    commentForm.body = commentBeingEdit.value?.body;
    commentTextareaRef.value?.focus();
};

const cancelEditComment = () => {
    commentIdBeingEdited.value = null;
    commentForm.reset();
};

const addComment = () =>
    commentForm.post(route("posts.comments.store", props.post.id), {
        preserveScroll: true,
        onSuccess: () => commentForm.reset(),
    });

const { confirmation } = useConfirm();

const updateComment = async () => {
    if (
        !(await confirmation("Are you sure you want to update this comment?"))
    ) {
        commentTextareaRef.value?.focus();
        return;
    }

    commentForm.put(
        route("comments.update", {
            comment: commentBeingEdit.value.id,
            page: props.comments.meta.current_page,
        }),
        {
            preserveScroll: true,
            onSuccess: cancelEditComment,
        }
    );
};

const deleteComment = async (commentID) => {
    if (
        !(await confirmation("Are you sure you want to delete this comment?"))
    ) {
        return;
    }
    router.delete(
        route("comments.destroy", {
            comment: commentID,
            page: props.comments.meta.current_page,
        }),
        {
            preserveScroll: true,
        }
    );
};
</script>
