<template>
    <div>
        <MainLayout title="Customer details" :breadcrumb="breadcrumb">
            <h2 class="is-size-4 pb-2">Customer information</h2>
            <table class="table is-bordered is-hoverable is-fullwidth">
                <tbody>
                    <tr>
                        <th>Id</th>
                        <td>{{ customer.id }}</td>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <td>{{ customer.name }}</td>
                    </tr>
                    <tr>
                        <th>Document</th>
                        <td>{{ customer.document }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{{ customer.status }}</td>
                    </tr>
                </tbody>
            </table>
            <div class="field is-grouped">
                <div class="control">
                    <a class="button is-info" :href="edit_url">Edit</a>
                    <button class="button is-danger" @click="onDeleteClick()">Delete</button>
                </div>
            </div>
            <hr>
            <h2 class="is-size-4 pb-2">Numbers</h2>
            <table class="table is-bordered is-hoverable is-fullwidth">
                <thead>
                    <tr>
                        <th>Number</th>
                        <th>Status</th>
                        <th>Preferences</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="number in numbers" :key="number.id">
                        <td>{{ number.number }}</td>
                        <td>{{ number.status }}</td>
                        <td>{{ number.number_preferences_amount }}</td>
                        <td>
                            <a class="button is-info is-small" :href="number.show_url">Details</a>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="field is-grouped">
                <div class="control">
                    <a class="button is-info" :href="create_number_url + '?customer_id=' + customer.id">Add a new number</a>
                </div>
            </div>
            <hr>
            <a class="button is-info is-light" :href="back_url">Go back</a>
        </MainLayout>
        <ModalYesNo :show="showDeleteModal" danger @yes="onDeleteConfirm()" @no="onDeleteCancel()">
            <template v-slot:title>Are you sure?</template>
        </ModalYesNo>
    </div>
</template>

<script>
import MainLayout from '../../Layouts/MainLayout.vue';
import ModalYesNo from '../../Components/ModalYesNo.vue';

export default {
    components: {
        MainLayout,
        ModalYesNo
    },
    props: {
        customer: {
            type: Object,
            required: true
        },
        numbers: {
            type: Array,
            required: true
        },
        edit_url: {
            type: String,
            required: true
        },
        back_url: {
            type: String,
            required: true
        },
        create_number_url: {
            type: String,
            required: true
        },
        destroy_url: {
            type: String,
            required: true
        },
        breadcrumb: {
            type: Array,
            default: null
        }
    },
    data: function() {
        return {
            showDeleteModal: false,
        };
    },
    methods: {
        onDeleteClick: function() {
            this.showDeleteModal = true;
        },
        onDeleteCancel: function() {
            this.showDeleteModal = false;
        },
        onDeleteConfirm: async function() {
            let response = await this.$inertia.delete(this.destroy_url);
        }
    }
}
</script>

<style>

</style>
