<script setup>
  import PollTable from './components/PollTable.vue'

  const { polls } = defineProps({
    polls: {
      type: Object,
      required: true
    }
  })
</script>

<template>
  Hello world
  <PollTable :polls="polls" />
</template>

<style scoped>

</style>