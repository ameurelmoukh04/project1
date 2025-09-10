<template>
  <nav class="w-full bg-white shadow-md px-6 py-4 flex justify-between items-center">
    <!-- Logo -->
    <div class="flex items-center space-x-2">
      <span class="text-2xl font-bold text-blue-600 cursor-pointer hover:text-blue-700 transition">
        Project1
      </span>
    </div>

        <div class="flex items-center space-x-2">
      <router-link
        v-if="isAuth"
        to="/dashboard"
        class="relative"
      >
        Tasks
      </router-link>
    </div>

    <div class="flex items-center space-x-4">
      <router-link
        v-if="isAuth"
        to="/notifications"
        class="relative"
      >
        Notifications
        <span class="absolute top-0 right-0 w-3 h-3 bg-red-500 rounded-full border-2 border-white animate-pulse"></span>
      </router-link>

      <button
        v-if="isAuth"
        @click="logout"
        class="flex items-center px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition"
      >
        Logout
      </button>
    </div>
  </nav>
</template>

<script>
import { ref, onMounted } from 'vue';
import api from '../api/axios';

export default {
  setup() {
    const isAuth = ref(false);

    onMounted(() => {
      const token = localStorage.getItem('token');
      isAuth.value = !!token; // true if token exists
    });

    const logout = async () => {
      try {
        const token = localStorage.getItem('token');
        if (!token) return;

        await api.post(
          '/logout',
          {},
          {
            headers: { Authorization: `Bearer ${token}` },
          }
        );

        localStorage.removeItem('token');
        localStorage.removeItem('user');
        window.location.href = '/login';
      } catch (err) {
        console.error('Logout failed:', err.response?.data || err.message);
      }
    };

    return { isAuth, logout };
  },
};
</script>
