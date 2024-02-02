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
        </Container>
    </AppLayout>
</template>
<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Container from "@/Components/Container.vue";
import { computed } from 'vue';
import { formatDistance, parseISO } from 'date-fns';

const props = defineProps(['post']);

const formattedDate = computed(() => formatDistance(parseISO(props.post.created_at), new Date()));
</script>
