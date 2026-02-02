<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: String,
    active: Boolean,
    class: String,
});

const classes = computed(() => {
    if (props.class) {
        return props.class;
    }

    return props.active
        ? 'nav-link active'
        : 'nav-link';
});
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
