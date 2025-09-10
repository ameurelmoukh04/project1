<template>
  <div class="min-h-screen bg-gray-100 py-10">
    <div class="max-w-3xl mx-auto px-4">
      <!-- Header -->
      <h2 class="text-3xl font-bold text-center mb-8 text-gray-800">
        My Tasks
      </h2>

      <!-- Add Task Form -->
      <form @submit.prevent="addTask" class="flex flex-col sm:flex-row gap-4 mb-8">
        <input
          type="text"
          v-model="newTaskTitle"
          placeholder="Enter a new task..."
          class="flex-1 p-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400 shadow-sm transition"
        />
        <button
          type="submit"
          class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-6 py-3 rounded-lg shadow hover:scale-105 transform transition"
          
          >
          Add Task
        </button>
            <notifications />
      </form>

      <!-- Task List -->
      <ul class="space-y-4">
        <li
          v-for="task in tasks"
          :key="task.id"
          class="flex justify-between items-center bg-white p-4 rounded-lg shadow hover:shadow-lg transition"
        >
          <div class="flex items-center gap-3">

            <span
              :class="{
                'line-through text-gray-400': task.completed,
                'text-gray-800': !task.completed
              }"
              class="text-lg font-medium"
            >
              {{ task.title }}
            </span>
          </div>

          <div class="flex gap-3">
            <button
              @click="editTask(task)"
              class="text-yellow-500 hover:text-yellow-600 font-semibold transition"
            >
              Edit
            </button>
            <button
              @click="deleteTask(task.id)"
              class="text-red-500 hover:text-red-600 font-semibold transition"
            >
              Delete
            </button>
          </div>
        </li>
      </ul>
    </div>

    <!-- Edit Task Modal -->
    <div
      v-if="editingTask"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    >
      <div class="bg-white p-6 rounded-xl shadow-xl w-full max-w-md">
        <h3 class="text-2xl font-bold mb-4 text-gray-800">Edit Task</h3>
        <input
          type="text"
          v-model="editingTask.title"
          class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 mb-4 shadow-sm transition"
        />
        <div class="flex justify-end gap-3">
          <button
            @click="saveTask"
            class="bg-green-500 text-white px-4 py-2 rounded-lg shadow hover:bg-green-600 transition"
          >
            Save
          </button>
          <button
            @click="cancelEdit"
            class="bg-gray-300 px-4 py-2 rounded-lg shadow hover:bg-gray-400 transition"
          >
            Cancel
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import api from '../api/axios';
import { useNotification } from '@kyvg/vue3-notification';

export default {
  name: 'App',
  setup() {
    // --- Tasks State ---
    const tasks = ref([]);
    const newTaskTitle = ref('');
    const editingTask = ref(null);

    // --- Notifications ---
    const { notify } = useNotification();
    const showNotification = (title, text, type = 'success') => {
      notify({
        title,
        text,
        type,
        duration: 5000,
      });
    };

    // --- Fetch Tasks ---
    const fetchTasks = async () => {
      try {
        const res = await api.get('/tasks', {
          headers: { Authorization: `Bearer ${localStorage.getItem('token')}` },
        });
        tasks.value = res.data.tasks;
      } catch (err) {
        console.error(err);
      }
    };

    // --- CRUD Functions ---
    const addTask = async () => {
      if (!newTaskTitle.value) return;
      try {
        const res = await api.post(
          '/tasks',
          { title: newTaskTitle.value },
          { headers: { Authorization: `Bearer ${localStorage.getItem('token')}` } }
        );
        tasks.value.push(res.data.task);
        showNotification('Task Added', `Task "${res.data.task.title}" created successfully.`);
        newTaskTitle.value = '';
      } catch (err) {
        console.error(err);
      }
    };

    const editTask = (task) => {
      editingTask.value = { ...task };
    };

    const saveTask = async () => {
      try {
        await api.put(
          `/tasks/${editingTask.value.id}`,
          { title: editingTask.value.title },
          { headers: { Authorization: `Bearer ${localStorage.getItem('token')}` } }
        );
        const index = tasks.value.findIndex((t) => t.id === editingTask.value.id);
        if (index !== -1) tasks.value[index] = { ...editingTask.value };
        showNotification('Task Updated', `Task "${editingTask.value.title}" updated successfully.`);
        editingTask.value = null;
      } catch (err) {
        console.error(err);
      }
    };

    const cancelEdit = () => {
      editingTask.value = null;
    };

    const deleteTask = async (id) => {
      try {
        const taskToDelete = tasks.value.find((t) => t.id === id);
        await api.delete(`/tasks/${id}`, {
          headers: { Authorization: `Bearer ${localStorage.getItem('token')}` },
        });
        tasks.value = tasks.value.filter((t) => t.id !== id);
        showNotification('Task Deleted', `Task "${taskToDelete?.title}" deleted.`);
      } catch (err) {
        console.error(err);
      }
    };

    // --- Mounting ---
    onMounted(fetchTasks);

    return {
      tasks,
      newTaskTitle,
      addTask,
      editTask,
      editingTask,
      saveTask,
      cancelEdit,
      deleteTask,
      showNotification,
    };
  },
};
</script>

