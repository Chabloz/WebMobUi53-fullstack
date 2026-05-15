import { ref } from 'vue';
import { useFetchApi } from '@/composables/useFetchApi';

const polls = ref([]);

export function usePollStore() {
  const { fetchApi } = useFetchApi();

  function setPolls(data) {
    polls.value = data;
  }

  async function updatePolls() {
    try {
      const data = await fetchApi({ url: 'polls', method: 'GET' });
      polls.value = data;
    } catch (error) {
      console.error(error);
    }

  }

  async function deletePoll(id) {
    const result = await fetchApi({ url: 'polls/' + id, method: 'DELETE' });
    if (result) {
      polls.value = polls.value.filter(p => p.id !== id);
    }
  }

  return { polls, setPolls, updatePolls, deletePoll };
}
