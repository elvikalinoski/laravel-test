<template>
    <MainLayout ref="layout" title="Home page">
        <div class="columns py-4">
            <div class="column is-half">
                <div class="box">
                    <p class="is-size-4">
                        Logged in as {{ user.name }}
                    </p>
                    <button class="button is-info mt-4" @click="logout()">Logout</button>
                </div>
            </div>
            <div class="column is-half">
                <div class="box">
                    <p class="is-size-4">
                        You have {{ customer_amount }} customer{{ customer_amount === 1 ? '' : 's' }}
                    </p>
                    <a class="button is-info mt-4" :href="customer_index_url">Go to customers list</a>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<script>
import MainLayout from '../Layouts/MainLayout.vue';

export default {
    components: {
        MainLayout
    },
    props: {
        user: {
            type: Object,
            required: true
        },
        customer_amount: {
            type: Number,
            required: true
        },
        customer_index_url: {
            type: String,
            required: true
        }
    },
    methods: {
        logout: function() {
            this.$refs['layout'].logout();
        }
    }
}
</script>

<style>

</style>
