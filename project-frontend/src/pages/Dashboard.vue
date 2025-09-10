<template>
  <div class="w-full p-6">
    <h1 class="text-2xl font-bold mb-4">Dashboard</h1>
    <TaskList :tasks="tasks" />
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import api from '../api/axios';
import TaskList from '../components/TaskList.vue';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

export default {
  components: { TaskList },
  setup() {
    const tasks = ref([]);

    // Initialize Echo and Pusher
    const initializeEcho = () => {
      const token = localStorage.getItem('token');
      if (!token) return;

      window.Pusher = Pusher;
      window.Echo = new Echo({
        broadcaster: 'pusher',
        key: '1b891a822f15667a8d89', // your Pusher key
        cluster: 'eu',
        forceTLS: true,
        authEndpoint: 'http://localhost:8000/api/broadcasting/auth',
        auth: {
          headers: { Authorization: `Bearer ${token}` }
        }
      });
    };

    const fetchTasks = async () => {
      try {
        const res = await api.get('/tasks');
        tasks.value = res.data.tasks;
      } catch (err) {
        console.error('Failed to fetch tasks:', err);
      }
    };

    onMounted(() => {
      initializeEcho();
      fetchTasks();

      const userId = JSON.parse(localStorage.getItem('user'))?.id;
      if (userId && window.Echo) {
        window.Echo.private(`tasks.${userId}`)
          .listen('TaskCreated', (event) => {
            console.log('New task received:', event);
            // Make sure to push the actual task object
            tasks.value.unshift(event.task);
          })
          .subscription.bind('pusher:subscription_succeeded', () => {
          });
      }
    });

    return { tasks };
  }
};
</script>

<style scoped>
/* Optional styling */
h1 {
  color: #1f2937; /* dark gray */
}
</style>
