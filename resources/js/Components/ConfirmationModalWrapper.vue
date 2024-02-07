<template>
    <ConfirmationModal :show="state.show">
        <template #title>
            {{ state.title }}
        </template>

        <template #content>
            <p>{{ state.message }}</p>
        </template>

        <template #footer>
            <SecondaryButton ref="cancelButtonRef" class="mr-2" @click="cancel">Cancel</SecondaryButton>
            <PrimaryButton @click="confirm">Confirm</PrimaryButton>
        </template>
    </ConfirmationModal>
</template>

<script setup>
import { useConfirm } from '@/Utilities/Composables/useComfirm';
import ConfirmationModal from './ConfirmationModal.vue';
import PrimaryButton from './PrimaryButton.vue';
import SecondaryButton from './SecondaryButton.vue';
import { ref, watchEffect, nextTick} from 'vue';

const { state, confirm, cancel } = useConfirm();

const cancelButtonRef = ref(null);

watchEffect(async () => {
    if (state.show) {
        await nextTick();
        cancelButtonRef.value?.$el.focus();
    }

});
</script>
