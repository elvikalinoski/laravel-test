<template>
    <div class="breadcrumb pt-2 pb-2" aria-label="breadcrumbs">
        <ul>
            <li
                v-for="(item, index) in path" :key="index"
                :class="{'is-active': !item.href || index === (path.length-1)}">
                <a :href="item.href || '#'">{{ item.name }}</a>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    props: {
        path: {
            type: Array,
            required: true
        }
    }
}
</script>

<style>

</style>
