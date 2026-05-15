<script setup>
  import PollTable from './components/PollTable.vue';
  import { usePollStore } from '@/stores/usePollStore';
  import { useHashRoute } from './composables/useHashRoute';
  import PollDashboardPage from './pages/PollDashboardPage.vue';
  import CreatePollPage from './pages/CreatePollPage.vue';

  const props = defineProps({
    polls: { type: Array, default: () => [] },
    loginUrl: { type: String, default: null },
    username: { type: String, default: null },
  });

  const { setPolls } = usePollStore();
  setPolls(props.polls);

  const routes = [
    { hash: '#dashboard', component: PollDashboardPage },
    { hash: '#create', component: CreatePollPage },
    // { hash: '#edit', component: EditPollPage }
  ]

  const { currentComponent, currentRoute, navigateTo } = useHashRoute(routes);
</script>

<template>
  <component :is="currentComponent"></component>
</template>
