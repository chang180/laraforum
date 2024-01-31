<template>
    <div
        class="flex items-center justify-between px-4 py-3 border-t border-gray-200 sm:px-6"
    >
        <div class="flex justify-between flex-1 sm:hidden">
            <Link
                :href="previousUrl"
                class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                >Previous</Link
            >
            <Link
                :href="nextUrl"
                class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                >Next</Link
            >
        </div>
        <div
            class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between"
        >
            <div>
                <p class="text-sm text-gray-700">
                    Showing
                    {{ " " }}
                    <span class="font-medium">{{ meta.from }}</span>
                    {{ " " }}
                    to
                    {{ " " }}
                    <span class="font-medium">{{ meta.to }}</span>
                    {{ " " }}
                    of
                    {{ " " }}
                    <span class="font-medium">{{ meta.total }}</span>
                    {{ " " }}
                    results
                </p>
            </div>
            <div>
                <nav
                    class="inline-flex -space-x-px bg-white rounded-md shadow-sm isolate"
                    aria-label="Pagination"
                >
                    <Link
                        :href="link.url"
                        v-for="link in meta.links"
                        class="relative inline-flex items-center px-3 py-2"
                        :class="{
                            'z-10 bg-indigo-600 text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600':
                                link.active,
                            'text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:outline-offset-0':
                                !link.active,
                        }"
                        v-html="link.label"
                    >
                    </Link>
                </nav>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ChevronLeftIcon, ChevronRightIcon } from "@heroicons/vue/solid";
import { Link } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps(['meta']);

const previousUrl = computed(() => props.meta.links[0].url);

const nextUrl = computed(() => [...props.meta.links].reverse()[0].url);
</script>
