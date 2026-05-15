<script setup>
  import PollTable from '../components/PollTable.vue';
  import { usePollStore } from '@/stores/usePollStore';
import { useHashRoute } from '../composables/useHashRoute';
import PollForm from '../components/PollForm.vue';

  const props = defineProps({
    
  });

</script>

<template>
  <h1 class="text-2xl font-bold dark:text-white">Nouveau sondage</h1>

  <PollForm />
</template>
