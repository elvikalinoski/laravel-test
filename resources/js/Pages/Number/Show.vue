<template>
    <div>
        <MainLayout title="Number details" :breadcrumb="breadcrumb">
            <h2 class="is-size-4 pb-2">Number information</h2>
            <table class="table is-bordered is-hoverable is-fullwidth">
                <tbody>
                    <tr>
                        <th>Id</th>
                        <td>{{ number.id }}</td>
                    </tr>
                    <tr>
                        <th>Customer</th>
                        <td>{{ customer.name }}</td>
                    </tr>
                    <tr>
                        <th>Number</th>
                        <td>{{ number.number }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{{ number.status }}</td>
                    </tr>
                </tbody>
            </table>
            <div class="field is-grouped">
                <div class="control">
                    <a class="button is-info" :href="edit_url">Edit</a>
                    <button class="button is-danger" @click="onDeleteNumberClick()">Delete</button>
                </div>
            </div>
            <hr>
            <h2 class="is-size-4 pb-2">Number preferences</h2>
            <table class="table is-bordered is-hoverable is-fullwidth">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Value</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="number_preference in number_preferences" :key="number_preference.id">
                        <td>{{ number_preference.name }}</td>
                        <td>{{ number_preference.value }}</td>
                        <td>
                            <a class="button is-info is-small" :href="number_preference.edit_url">Edit</a>
                            <button class="button is-danger is-small" @click.prevent="onDeleteNumberPreferenceClick(number_preference.destroy_url)">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="field is-grouped">
                <div class="control">
                    <a class="button is-info" :href="create_number_preference_url + '?number_id=' + number.id">Add a new preference</a>
                </div>
            </div>
            <hr>
            <a class="button is-info is-light" :href="back_url">Go back</a>
        </MainLayout>
        <ModalYesNo
            :show="showDeleteNumberModal" danger
            @yes="onDeleteNumberConfirm()"
            @no="onDeleteNumberCancel()">
            <template v-slot:title>Are you sure?</template>
        </ModalYesNo>
        <ModalYesNo
            :show="showDeleteNumberPreferenceModal"
            danger
            @yes="onDeleteNumberPreferenceConfirm()"
            @no="onDeleteNumberPreferenceCancel()">
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
        number: {
            type: Object,
            required: true
        },
        number_preferences: {
            type: Array,
            required: true
        },
        create_number_preference_url: {
            type: String,
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
            showDeleteNumberModal: false,
            showDeleteNumberPreferenceModal: false,
            numberPrefenceDeletionUrl: null,
        };
    },
    methods: {
        onDeleteNumberClick: function() {
            this.showDeleteNumberModal = true;
        },
        onDeleteNumberCancel: function() {
            this.showDeleteNumberModal = false;
        },
        onDeleteNumberConfirm: async function() {
            let response = await this.$inertia.delete(this.destroy_url);
        },
        onDeleteNumberPreferenceClick: function(destroy_number_preference_url) {
            this.numberPrefenceDeletionUrl = destroy_number_preference_url;
            this.showDeleteNumberPreferenceModal = true;
        },
        onDeleteNumberPreferenceCancel: function() {
            this.numberPrefenceDeletionUrl = null;
            this.showDeleteNumberPreferenceModal = false;
        },
        onDeleteNumberPreferenceConfirm: async function() {
            let response = await this.$inertia.delete(this.numberPrefenceDeletionUrl);
        },
    }
}
</script>

<style>

</style>
