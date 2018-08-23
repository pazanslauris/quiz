<template>
    <div v-show="show" class="confirmation__overlay">
        <div class="confirmation">
            <p class="confirmation__text"> {{ text }}</p>
            <button class="confirmation__btn confirmation__btn--deny" @click="onDecline()">No</button>
            <button class="confirmation__btn confirmation__btn--accept" @click="onAccept()">Yes</button>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'ConfirmationDialog',
        props: {
            onAccept: {
                type: Function,
                required: true
            },
            onDecline: {
                type: Function,
                default: Function()
            },
            text: {
                type: String,
                required: true
            },
            show: false,
        }
    }
</script>