<template>
    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
        <NavLink
            v-for="item in $page.props.navigation"
            :key="item.route"
            :href="route(item.route)"
            :active="item.active.some(pattern => route().current(pattern))"
        >
            {{ item.name }}
        </NavLink>
    </div>
</template>

<script setup>
import NavLink from '@/Components/NavLink.vue';
</script>