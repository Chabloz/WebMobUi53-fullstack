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
    <!-- CHANGEMENT :
         Sur le dashboard principal, on affiche le lien vers la création. -->
    <a v-if="mode === 'index'" href="/polls/dashboard/create">
      Créer un sondage
    </a>

    <!-- CHANGEMENT :
         Sur les pages create et edit, on affiche un retour vers le dashboard. -->
    <a v-else href="/polls/dashboard">
      Retour au dashboard
    </a>
  </p>

  <!-- CHANGEMENT :
       On affiche le formulaire en mode création ET en mode édition.
       En mode édition, on lui transmet le sondage à modifier. -->
  <PollForm
    v-if="mode === 'create' || mode === 'edit'"
    :poll="pollToEdit"
  />

  <!-- CHANGEMENT :
       Le tableau s’affiche uniquement sur la page principale. -->
  <PollTable v-else />
</template>