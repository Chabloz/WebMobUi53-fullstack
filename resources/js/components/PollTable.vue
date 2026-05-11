<script setup>
  import { usePollStore } from '@/stores/usePollStore';

  const { polls, deletePoll } = usePollStore();

  async function delPoll(id) {
    console.log('delete Poll ID:', id);
    await deletePoll(id);
  }
</script>

<template>
  <p v-if="polls.length === 0">Aucun sondage.</p>

  <table v-else class="w-full border-collapse text-left">
    <thead>
      <tr>
        <th class="border px-3 py-2">Actions</th>
        <th class="border px-3 py-2">ID</th>
        <th class="border px-3 py-2">Titre</th>
        <th class="border px-3 py-2">Question</th>
        <th class="border px-3 py-2">Brouillon</th>
        <th class="border px-3 py-2">Debut</th>
        <th class="border px-3 py-2">Fin</th>
      </tr>
    </thead>

    <tbody>
      <tr v-for="poll in polls" :key="poll.id">
        <!-- CHANGEMENT :
             La colonne Actions contient maintenant deux actions :
             - Modifier : lien vers la page d’édition
             - Supp. : bouton de suppression déjà existant -->
        <td class="border px-3 py-2 actions">
          <!-- CHANGEMENT :
               Ce lien construit une URL dynamique avec l'id du sondage.
               Exemple : /polls/dashboard/3/edit -->
          <a
            class="edit-link"
            :href="'/polls/dashboard/' + poll.id + '/edit'"
          >
            Modifier
          </a>

          <!-- Bouton de suppression existant -->
          <button @click="delPoll(poll.id)">
            Supp.
          </button>
        </td>

        <td class="border px-3 py-2">{{ poll.id }}</td>
        <td class="border px-3 py-2">{{ poll.title || '-' }}</td>
        <td class="border px-3 py-2">{{ poll.question }}</td>
        <td class="border px-3 py-2">{{ poll.is_draft ? 'Oui' : 'Non' }}</td>
        <td class="border px-3 py-2">{{ poll.started_at || '-' }}</td>
        <td class="border px-3 py-2">{{ poll.ends_at || '-' }}</td>
      </tr>
    </tbody>
  </table>
</template>

<style scoped>
  button {
    background-color: #e3342f;
    color: white;
    padding: 0.25rem 0.5rem;
    border: none;
    border-radius: 0.25rem;
    cursor: pointer;
  }

  /* CHANGEMENT :
     Permet d’afficher les actions côte à côte
     avec un petit espace entre Modifier et Supp. */
  .actions {
    display: flex;
    gap: 0.5rem;
  }

  /* CHANGEMENT :
     Style du lien Modifier.
     On lui donne l’apparence d’un bouton bleu. */
  .edit-link {
    display: inline-block;
    background-color: #2563eb;
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    text-decoration: none;
  }
</style>