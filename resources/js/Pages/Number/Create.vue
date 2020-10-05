<template>
    <div>
        <MainLayout title="Add number" :breadcrumb="breadcrumb">
            <form @submit.prevent="submitForm()" method="POST">

                <!-- CUSTOMER -->
                <div class="field">
                    <label class="label" for="customer_name">Customer</label>
                    <div class="control">
                        <input
                            class="input"
                            type="text"
                            name="customer_name"
                            :value="customer.name"
                            disabled>
                    </div>
                </div>

                <input type="hidden" name="consumer_id" :value="customer.id">

                <!-- NUMBER -->
                <div class="field">
                    <label class="label" for="number">Number</label>
                    <div class="control">
                        <input
                            class="input"
                            :class="{'is-danger': !!error_messages['number']}"
                            type="text"
                            name="number"
                            v-model="numberFields.number">
                    </div>
                    <p class="help is-danger" v-if="error_messages['number']">{{ error_messages['number'][0] }}</p>
                </div>

                <!-- STATUS -->
                <div class="field">
                    <label class="label" for="status">Status</label>
                    <div class="control">
                        <div class="select" :class="{'is-danger': !!error_messages['status']}">
                            <select name="status" id="" v-model="numberFields.status">
                                <option
                                    v-for="(status, value) in status_options"
                                    :key="value"
                                    :value="value"
                                    >{{ status }}</option>
                            </select>
                        </div>
                    </div>
                    <p class="help is-danger" v-if="error_messages['status']">{{ error_messages['status'][0] }}</p>
                </div>

                <!-- BUTTONS -->
                <div class="field is-grouped">
                    <div class="control">
                        <button type="submit" class="button is-info">Create</button>
                        <a class="button is-link is-light" :href="back_url">Go back</a>
                    </div>
                </div>
            </form>
        </MainLayout>
    </div>
</template>

<script>
import MainLayout from '../../Layouts/MainLayout.vue';

export default {
    components: {
        MainLayout
    },
    props: {
        customer: {
            type: Object,
            required: true
        },
        store_url: {
            type: String,
            required: true
        },
        back_url: {
            type: String,
            required: true
        },
        status_options: {
            type: Object,
            required: true
        },
        error_messages: {
            type: Object,
            default: function() { return {}; }
        },
        breadcrumb: {
            type: Array,
            default: null
        }
    },
    data: function() {
        return {
            numberFields: {
                number: '',
                status: 0,
            }
        };
    },
    methods: {
        submitForm: async function() {
            let response = await this.$inertia.post(this.store_url, {
                'customer_id': this.customer.id,
                ...this.numberFields
            });
        }
    }
}
</script>

<style>

</style>
