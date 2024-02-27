<template>
    <AppLayout title="Create a Post">
        <Container>
            <h1 class="text-2xl font-bold">Create a Post</h1>
            <form @submit.prevent="createPost" class="mt-6">
                <div>
                    <InputLabel for="title" class="sr-only">Title</InputLabel>
                    <TextInput
                        v-model="form.title"
                        id="title"
                        type="text"
                        class="w-full"
                        placeholder="Give it a great title..."
                    />
                    <InputError :message="form.errors.title" class="mt-2" />
                </div>
                <div class="my-3">
                    <InputLabel for="body" class="sr-only">Body</InputLabel>
                    <MarkdownEditor v-model="form.body" />
                    <TextareaInput
                        v-model="form.body"
                        id="body"
                        rows="25"
                        class="mt2"
                        placeholder="Put in some words..."
                    />
                    <InputError :message="form.errors.body" class="mt-2" />
                </div>
                <div>
                    <PrimaryButton type="submit">Create Post</PrimaryButton>
                    <SecondaryButton
                        href="{{ route('posts.index') }}"
                        class="ml-4"
                        >Cancel</SecondaryButton
                    >
                </div>
            </form>
        </Container>
    </AppLayout>
</template>
<script setup>
import MarkdownEditor from "@/Components/MarkdownEditor.vue";
import Container from "@/Components/Container.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import TextareaInput from "@/Components/TextareaInput.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm } from "@inertiajs/vue3";

const form = useForm({
    title: "",
    body: "",
});

const createPost = () => form.post(route("posts.store"));
</script>
