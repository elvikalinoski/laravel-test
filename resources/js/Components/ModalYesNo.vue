<template>
    <div class="modal" :class="{'is-active': show}">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">
                    <slot name="title"></slot>
                </p>
                <button class="delete" aria-label="close" @click="$emit('no')"></button>
            </header>
            <section class="modal-card-body" v-if="$slots.body">
                <slot name="body"></slot>
            </section>
            <footer class="modal-card-foot">
                <button class="button" :class="{'is-info': !danger, 'is-danger': danger}" @click="$emit('yes')">Confirm</button>
                <button class="button" @click="$emit('no')">Cancel</button>
            </footer>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        show: {
            type: Boolean,
            required: true
        },
        danger: {
            type: Boolean,
            default: false
        }
    }
}
</script>

<style>

</style>
