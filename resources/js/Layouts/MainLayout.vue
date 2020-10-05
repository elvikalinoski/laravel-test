<template>
    <div class="lt-layout">
        <nav class="navbar">
            <div class="container">
                <div class="navbar-brand">
                    <a
                        role="button"
                        class="navbar-burger"
                        :class="{'is-active': isNavbarActive}"
                        @click.prevent="toggleNavbar()"
                        aria-label="menu"
                        aria-expanded="false">
                        <span aria-hidden="true"></span>
                        <span aria-hidden="true"></span>
                        <span aria-hidden="true"></span>
                    </a>
                </div>
                <div class="navbar-menu" :class="{'is-active': isNavbarActive}">
                    <div class="navbar-start">
                        <a class="navbar-item" href="/">Home</a>
                        <a class="navbar-item" href="/customer">Customers</a>
                    </div>
                    <div class="navbar-end">
                        <a class="navbar-item" @click.prevent="logout()">Logout</a>
                    </div>
                </div>
            </div>
        </nav>
        <section class="hero is-info" v-if="title">
            <div class="hero-body">
                <div class="container">
                    <h1 class="title">{{ title }}</h1>
                    <h2 class="subtitle" v-if="subtitle">{{ subtitle }}</h2>
                </div>
            </div>
        </section>
        <div class="lt-breadcrumb" v-if="breadcrumb">
            <div class="container py-2 px-4">
                <Breadcrumb class="" v-if="breadcrumb" :path="breadcrumb"></Breadcrumb>
            </div>
        </div>
        <section class="container py-4 px-4">
            <slot></slot>
        </section>
    </div>
</template>

<script>
import Breadcrumb from '../Components/Breadcrumb.vue';

export default {
    components: {
        Breadcrumb
    },
    props: {
        title: {
            type: String
        },
        subtitle: {
            type: String
        },
        breadcrumb: {
            type: Array,
            default: null
        },
    },
    data: function() {
        return {
            isNavbarActive: false
        };
    },
    methods: {
        logout: function() {
            axios.post('/logout').then(() => {
                window.location = '/';
            });
        },
        toggleNavbar: function() {
            this.isNavbarActive = !this.isNavbarActive;
        }
    },
}
</script>

<style>
.lt-layout {
    padding: 0;
    margin: 0;
    min-height: 100vh;
    background-color: rgb(237, 237, 237);
}

.lt-breadcrumb {
    background-color: #fff;
}
</style>
