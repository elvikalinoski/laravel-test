<template>
    <div>
        <MainLayout title="Add number preference" :breadcrumb="breadcrumb">
            <form @submit.prevent="submitForm()" method="POST">

                <!-- NUMBER -->
                <div class="field">
                    <label class="label" for="number">Number</label>
                    <div class="control">
                        <input
                            class="input"
                            type="text"
                            name="number"
                            :value="number.number"
                            disabled>
                    </div>
                </div>

                <!-- NAME -->
                <div class="field">
                    <label class="label" for="name">Name</label>
                    <div class="control">
                        <input
                            class="input"
                            :class="{'is-danger': !!error_messages['name']}"
                            type="text"
                            name="name"
                            v-model="numberPreferenceFields.name">
                    </div>
                    <p class="help is-danger" v-if="error_messages['name']">{{ error_messages['name'][0] }}</p>
                </div>

                <!-- VALUE -->
                <div class="field">
                    <label class="label" for="value">Value</label>
                    <div class="control">
                        <input
                            class="input"
                            :class="{'is-danger': !!error_messages['value']}"
                            type="text"
                            name="value"
                            v-model="numberPreferenceFields.value">
                    </div>
                    <p class="help is-danger" v-if="error_messages['value']">{{ error_messages['value'][0] }}</p>
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
        number: {
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
            numberPreferenceFields: {
                name: '',
                value: ''
            }
        };
    },
    methods: {
        submitForm: async function() {
            let response = await this.$inertia.post(this.store_url, {
                'number_id': this.number.id,
                ...this.numberPreferenceFields
            });
        }
    }
}
</script>

<style>

</style>
