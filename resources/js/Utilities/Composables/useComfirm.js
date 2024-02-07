import { reactive, readonly } from "vue";

const globalState = reactive({
    show: false,
    title: "",
    message: "",
    resolver: null,
});

export function useConfirm() {
    const restModal = () => {
        globalState.show = false;
        globalState.title = "";
        globalState.message = "";
        globalState.resolver = null;
    }
    return {
        state: readonly(globalState),
        confirmation: (message, title = 'Please Confirm') => {
            globalState.title = title;
            globalState.show = true;
            globalState.message = message;

            return new Promise((resolver) => {
                globalState.resolver = resolver;
            });
        },
        confirm: () => {
            if(globalState.resolver){
                globalState.resolver(true);
            }
            restModal();
        },
        cancel: () => {
            if(globalState.resolver){
                globalState.resolver(false);
            }
            restModal();
        },
    };
}
