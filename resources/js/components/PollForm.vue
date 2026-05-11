<script setup>
  // Utilisation de la Composition API
  import { computed, ref } from 'vue';
  import { usePollStore } from '@/stores/usePollStore';

  // CHANGEMENT :
  // Le formulaire peut maintenant recevoir un sondage existant.
  // Si poll vaut null → création
  // Si poll contient un objet → édition
  const props = defineProps({
    poll: { type: Object, default: null },
  });

  // CHANGEMENT :
  // On récupère aussi updatePoll pour pouvoir modifier un sondage.
  const { createPoll, updatePoll, loading, error, clearError } = usePollStore();

  // CHANGEMENT :
  // Permet de savoir si le formulaire est en mode édition.
  const isEditing = computed(() => props.poll !== null);

  // CHANGEMENT :
  // Les champs sont préremplis si on édite un sondage.
  const title = ref(props.poll?.title || '');
  const question = ref(props.poll?.question || '');
  const options = ref(
    props.poll?.options?.length
      ? props.poll.options.map(option => ({
          id: option.id,
          label: option.label,
        }))
      : [
          { label: '' },
          { label: '' },
        ]
  );

  const isDraft = ref(props.poll?.is_draft ?? true);
  const allowMultipleChoices = ref(props.poll?.allow_multiple_choices ?? false);
  const resultsPublic = ref(props.poll?.results_public ?? false);
  const duration = ref(props.poll?.duration || '');

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
      allow_vote_change: false,
      results_public: resultsPublic.value,
      duration: duration.value ? Number(duration.value) : null,
    };

    // CHANGEMENT :
    // Si on édite, on appelle PATCH avec updatePoll.
    // Sinon, on appelle POST avec createPoll.
    const result = isEditing.value
      ? await updatePoll(props.poll.id, payload)
      : await createPoll(payload);

    if (result) {
      // CHANGEMENT :
      // Après modification ou création, on retourne au dashboard.
      window.location.href = '/polls/dashboard';
    }
  }
</script>

<template>
  <form class="poll-form" @submit.prevent="submitForm">
    <!-- CHANGEMENT :
         Le titre change selon le mode du formulaire. -->
    <h2>{{ isEditing ? 'Modifier le sondage' : 'Créer un sondage' }}</h2>

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
        :key="option.id || index"
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
      <label for="duration">Durée de disponibilité</label>
      <input
        id="duration"
        v-model="duration"
        type="number"
        min="1"
        placeholder="Laisser vide pour aucune limite"
      >
    </div>

    <!-- CHANGEMENT :
         Le texte du bouton change selon le mode création / édition. -->
    <button class="submit-button" type="submit" :disabled="loading">
      <span v-if="loading">
        {{ isEditing ? 'Modification...' : 'Création...' }}
      </span>

      <span v-else>
        {{ isEditing ? 'Modifier le sondage' : 'Créer le sondage' }}
      </span>
    </button>
  </form>
</template>

<style scoped>
  /* CHANGEMENT :
     Formulaire centré et limité en largeur pour éviter qu’il prenne toute la page. */
  .poll-form {
    max-width: 700px;
    margin: 0 auto 2rem;
    padding: 1.5rem;
    border: 1px solid #ddd;
    border-radius: 0.75rem;
    background-color: white;
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

  .submit-button {
    display: inline-block;
    padding: 0.75rem 1rem;
    background-color: #2563eb;
    color: white;
    border: none;
    border-radius: 0.5rem;
    font-weight: 700;
    cursor: pointer;
  }

  .submit-button:hover {
    background-color: #1d4ed8;
  }

  .submit-button:disabled {
    opacity: 0.6;
    cursor: not-allowed;
  }
</style>