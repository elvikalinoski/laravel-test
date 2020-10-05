<template>
    <div>
        <MainLayout title="Edit customer" :breadcrumb="breadcrumb">
            <form @submit.prevent="submitForm()" :action="update_url" method="POST">

                <!-- NAME -->
                <div class="field">
                    <label class="label" for="name">Name</label>
                    <div class="control">
                        <input
                            class="input"
                            :class="{'is-danger': !!error_messages['name']}"
                            type="text"
                            name="name" v-model="customerFields.name">
                    </div>
                    <p class="help is-danger" v-if="error_messages['name']">{{ error_messages['name'][0] }}</p>
                </div>

                <!-- DOCUMENT -->
                <div class="field">
                    <label class="label" for="document">Document</label>
                    <div class="control">
                        <input class="input" :class="{'is-danger': !!error_messages['document']}" type="text" name="document" v-model="customerFields.document">
                    </div>
                    <p class="help is-danger" v-if="error_messages['document']">{{ error_messages['document'][0] }}</p>
                </div>

                <!-- STATUS -->
                <div class="field">
                    <label class="label" for="status">Status</label>
                    <div class="control">
                        <div class="select" :class="{'is-danger': !!error_messages['status']}">
                            <select name="status" id="" v-model="customerFields.status">
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
                        <button type="submit" class="button is-info">Update</button>
                        <a class="button is-info is-light" :href="back_url">Go back</a>
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
        update_url: {
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
            customerFields: {
                name: this.customer.name,
                document: this.customer.document,
                status: this.customer.status
            }
        };
    },
    beforeMount() {
        // identify customer status
        this.customerFields.status = Object.keys(this.status_options).find((key) => {
            if(this.status_options[key] === this.customer.status) {
                return true;
            }
            return false;
        });
    },
    methods: {
        submitForm: async function() {
            let response = await this.$inertia.put(this.update_url, this.customerFields);
        }
    }
}
</script>

<style>

</style>
