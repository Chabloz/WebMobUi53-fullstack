<script setup>
  import PollForm from './components/PollForm.vue';
  import PollTable from './components/PollTable.vue';
  import { usePollStore } from '@/stores/usePollStore';

  const props = defineProps({
    polls: { type: Array, default: () => [] },
    mode: { type: String, default: 'index' },
    pollToEdit: { type: Object, default: null },
    loginUrl: { type: String, default: null },
    username: { type: String, default: null },
  });

  const { setPolls } = usePollStore();
  setPolls(props.polls);
</script>

<template>
  <p>
    <a v-if="mode !== 'create'" href="/polls/dashboard/create">
      Créer un sondage
    </a>

    <a v-else href="/polls/dashboard">
      Retour au dashboard
    </a>
  </p>

  <PollForm v-if="mode === 'create'" />

  <PollTable />
</template>