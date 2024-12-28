<script setup>
</script>

<template>
<div v-if="$page.props.flash.message && $page.props.flash.status == 'alert'" class="p-2 text-ml bg-red-400 text-white">
    {{ $page.props.flash.message }}
</div>
<div v-if="$page.props.flash.message && $page.props.flash.status == 'info'" class="p-2 text-ml bg-blue-300 text-white">
    {{ $page.props.flash.message }}
</div>
</template>
