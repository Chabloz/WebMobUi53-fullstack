<script setup>
import { ref } from 'vue';
import { usePollStore } from '@/stores/usePollStore';

  const props = defineProps({
    poll: { type: Object, default: null }
  });

  const editMode = props.poll !== null;

  function initForm() {
    const form = editMode
    ? {
        id : props.poll.id,
        title : props.poll.title ?? '',
        question : props.poll.question ?? '',
        options : props.poll.options ?? [{label : ''}, {label : ''}],
        allow_multiple_choices : props.poll.allow_multiple_choices ?? false,
        allow_vote_change : props.poll.allow_vote_change ?? false,
        results_public : props.poll.results_public ?? false,
        duration : props.poll.duration ?? null,
        ends_at : props.poll.ends_at ?? null,
        is_draft : props.poll.is_draft ?? true,
    }
    : {
        title : '',
        question : '',
        options : [{label : ''}, {label : ''}],
        allow_multiple_choices : false,
        allow_vote_change : false,
        results_public : false,
        duration : null,
        ends_at : null,
        is_draft : true,
    }

    return form;

  }

  const form = ref(initForm());
  const errors = ref({});
  const loading = ref(false);

  const method = editMode? 'PUT' : 'POST';
  const action = editMode? `/polls/${form.id}` : `/polls` ;

  function delOption(i) {
    if (form.value.options.length > 2) {
        form.value.options.splice(i, 1);
    }
  }

  function addOption() {
    form.value.options.push({label : ''});
  }

  function submitForm() {

  }

  function saveDraft() {
    form.value.is_draft = true;
    submitForm();
  }

  function publishPoll() {
    form.value.is_draft = false;
    submitForm();
  }


</script>

<template>
    <article class="bg-white dark:bg-slate-800 rounded-lg shadow-md p-6">

        <form @submit.prevent="submitForm">
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Titre (optionnel)
                </label>
                <input id="title" type="text" name="title" v-model="form.title"
                    placeholder="Entrez un titre (optionnel)"
                    class="w-full px-3 py-2 border rounded-md bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:border-transparent border-gray-300 dark:border-gray-600 focus:ring-teal-500 dark:focus:ring-purple-500"
                    :class="{'border-red-500 focus:ring-red-500' : errors.title}">
                    <p v-if="errors.title" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.title }}</p>
            </div>

            <div class="mb-4">
                <label for="question" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Question
                </label>
                <input id="question" type="text" name="question" v-model="form.question"
                    placeholder="Quelle est votre question ?"
                    class="w-full px-3 py-2 border rounded-md bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:border-transparent border-gray-300 dark:border-gray-600 focus:ring-teal-500 dark:focus:ring-purple-500"
                    :class="{'border-red-500 focus:ring-red-500' : errors.question}">
                    <p v-if="errors.question" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.question }}</p>
            </div>

            <div class="mb-4">
                <label for="options" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Options
                </label>
                <div v-for="(option, index) in form.options" :key="index" class="w-full flex gap-2 mb-3 align-items-center">
                    <input id="options" type="text" name="options" v-model="form.options[index].label"
                        :placeholder="`Option ${index+1}`"
                        class="w-full px-3 py-2 border rounded-md bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:border-transparent border-gray-300 dark:border-gray-600 focus:ring-teal-500 dark:focus:ring-purple-500"
                        :class="{'border-red-500 focus:ring-red-500' : errors.options}">
                    <button @click="delOption(index)" :disabled="form.options.length <= 2" 
                        class="ps-2 text-red-600 dark:text-red-400 disabled:opacity-40">✕</button>
                </div>
                <p v-if="errors.options" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors?.options }}</p>
                <button @click="addOption()" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 cursor-pointer">Ajouter une option</button>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Paramètres
                </label>
                <label class="w-full px-3 py-2 flex gap-3 align-items-center text-gray-900 dark:text-white">
                    <input id="allow_multiple_choices" type="checkbox" name="allow_multiple_choices" v-model="form.allow_multiple_choices"
                        class="bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:border-transparent border-gray-300 dark:border-gray-600 focus:ring-teal-500 dark:focus:ring-purple-500">
                    Plusieurs réponses possibles
                </label>

                <label class="w-full px-3 py-2 flex gap-3 align-items-center text-gray-900 dark:text-white">
                    <input id="allow_vote_change" type="checkbox" name="allow_vote_change" v-model="form.allow_vote_change"
                        class="bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:border-transparent border-gray-300 dark:border-gray-600 focus:ring-teal-500 dark:focus:ring-purple-500">
                    Modification du vote possible
                </label>

                <label class="w-full px-3 py-2 flex gap-3 align-items-center text-gray-900 dark:text-white">
                    <input id="results_public" type="checkbox" name="results_public" v-model="form.results_public"
                        class="bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:border-transparent border-gray-300 dark:border-gray-600 focus:ring-teal-500 dark:focus:ring-purple-500">
                    Résultats publics
                </label>
            </div>

            <div class="flex justify-end gap-3">
                <button @click="saveDraft" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 cursor-pointer">Enregistrer le Brouillon</button>
                <button @click="publishPoll" class="px-4 py-2 bg-teal-600 dark:bg-purple-900 text-white rounded-md hover:bg-teal-700 dark:hover:bg-purple-800 cursor-pointer">Publier le formulaire</button>
            </div>
        </form>
    </article>
  
</template>
