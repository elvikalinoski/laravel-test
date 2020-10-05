<template>
    <MainLayout title="Customers" :breadcrumb="breadcrumb">
        <table class="table is-bordered is-hoverable is-fullwidth">
            <thead>
                <th>Name</th>
                <th>Document</th>
                <th>Status</th>
                <th>Numbers</th>
                <th>Actions</th>
            </thead>
            <tbody>
                <tr v-for="customer in customers" :key="customer.id">
                    <td>{{ customer.name }}</td>
                    <td>{{ customer.document }}</td>
                    <td>{{ customer.status }}</td>
                    <td>{{ customer.numbers_amount }}</td>
                    <td>
                        <a class="button is-info is-small" :href="customer.show_url">Details</a>
                    </td>
                </tr>
            </tbody>
        </table>
        <a class="button is-info" :href="create_url">Add a new customer</a>
    </MainLayout>
</template>

<script>
import MainLayout from '../../Layouts/MainLayout.vue';

export default {
    components: {
        MainLayout
    },
    props: {
        customers: {
            type: Array,
            default: function() { return []; }
        },
        create_url: {
            type: String,
            required: true
        },
        breadcrumb: {
            type: Array,
            default: null
        }
    },
    methods: {
        onDeleteClick: async function(destroy_url) {
            let response = await this.$inertia.delete(destroy_url);
        }
    }
}
</script>

<style>

</style>
