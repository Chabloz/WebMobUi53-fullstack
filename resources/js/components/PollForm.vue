<script setup> // utilisation de composition API 
  import { ref } from 'vue';
  import { usePollStore } from '@/stores/usePollStore';

  const { createPoll, loading, error, clearError } = usePollStore();

  // valeurs réactives : 
  const title = ref('');
  const question = ref('');
  const options = ref([
    { label: '' },
    { label: '' },
  ]);
  const isDraft = ref(true);
  const allowMultipleChoices = ref(false);
  const resultsPublic = ref(false);
  const duration = ref('');

  function addOption() {
    options.value.push({ label: '' });
  }

  function removeOption(index) {
    if (options.value.length <= 2) return;
    options.value.splice(index, 1);
  }

  async function submitForm() {
    clearError();

    const payload = {
      title: title.value || null,
      question: question.value,
      options: options.value.filter(option => option.label.trim() !== ''),
      is_draft: isDraft.value,
      allow_multiple_choices: allowMultipleChoices.value,
      results_public: resultsPublic.value,
      duration: duration.value ? Number(duration.value) : null,
    };

    const result = await createPoll(payload);

    // reset du formulaire : pour permettre de créer un nouveau sondage 
    if (result) {
      title.value = '';
      question.value = '';
      options.value = [
        { label: '' },
        { label: '' },
      ];
      isDraft.value = true;
      allowMultipleChoices.value = false;
      resultsPublic.value = false;
      duration.value = '';
    }
  }
</script>

<template>
  <form class="poll-form" @submit.prevent="submitForm">
    <h2>Créer un sondage</h2>

    <p v-if="error" class="error">{{ error }}</p>

    <div>
      <label for="title">Titre</label>
      <input id="title" v-model="title" type="text">
    </div>

    <div>
      <label for="question">Question *</label>
      <input id="question" v-model="question" type="text" required>
    </div>

    <div>
      <label>Options *</label>

      <div
        v-for="(option, index) in options"
        :key="index"
        class="option-row"
      >
        <input
          v-model="option.label"
          type="text"
          :placeholder="'Option ' + (index + 1)"
          required
        >

        <button
          type="button"
          @click="removeOption(index)"
          :disabled="options.length <= 2"
        >
          Retirer
        </button>
      </div>

      <button type="button" @click="addOption">
        Ajouter une option
      </button>
    </div>

    <div class="settings">
      <label>
        <input v-model="isDraft" type="checkbox">
        Brouillon
      </label>

      <label>
        <input v-model="allowMultipleChoices" type="checkbox">
        Choix multiple
      </label>

      <label>
        <input v-model="resultsPublic" type="checkbox">
        Résultats publics
      </label>
    </div>

    <div>
      <label for="duration">Durée en secondes</label>
      <input id="duration" v-model="duration" type="number" min="1">
    </div>

    <button type="submit" :disabled="loading">
      {{ loading ? 'Création...' : 'Créer le sondage' }}
    </button>
  </form>
</template>

<style scoped>
  .poll-form {
    margin-bottom: 2rem;
    padding: 1rem;
    border: 1px solid #ddd;
    border-radius: 0.5rem;
  }

  .poll-form div {
    margin-bottom: 1rem;
  }

  label {
    display: block;
    margin-bottom: 0.25rem;
    font-weight: bold;
  }

  input[type="text"],
  input[type="number"] {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #ccc;
    border-radius: 0.25rem;
  }

  .option-row {
    display: flex;
    gap: 0.5rem;
  }

  .settings label {
    font-weight: normal;
  }

  button {
    padding: 0.5rem 0.75rem;
    border: none;
    border-radius: 0.25rem;
    cursor: pointer;
  }

  .error {
    padding: 0.75rem;
    color: #842029;
    background-color: #f8d7da;
    border-radius: 0.25rem;
  }
</style>