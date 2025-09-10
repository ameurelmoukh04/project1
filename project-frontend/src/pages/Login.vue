<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8">
      <h1 class="text-3xl font-bold text-center mb-6 text-gray-800">Login</h1>

      <form @submit.prevent="login" class="space-y-4">
        <input
          type="email"
          v-model="email"
          placeholder="Email"
          required
          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
        <input
          type="password"
          v-model="password"
          placeholder="Password"
          required
          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
        />

        <button
          type="submit"
          class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors"
        >
          Log In
        </button>
      </form>

      <p class="mt-6 text-center text-gray-600">
        Don't have an account?
        <router-link to="/register" class="text-blue-600 hover:underline font-medium">Register</router-link>
      </p>
    </div>
  </div>
</template>

<script>
import api from '../api/axios';
import { ref } from 'vue';

export default {
  setup() {
    const email = ref('');
    const password = ref('');

    const login = async () => {
      try {
        const res = await api.post('/login', {
          email: email.value,
          password: password.value,
        });

        const token = res.data.Authorization.token;
        const user = res.data.user;

        localStorage.setItem('token', token);
        localStorage.setItem('user', JSON.stringify(user));

        window.location.href = '/dashboard'; // redirect after login
      } catch (err) {
        alert(err.response?.data?.message || 'Login failed');
      }
    };

    return { email, password, login };
  },
};
</script>

<style scoped>
/* Optional: subtle page fade-in animation */
.auth-page {
  animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}
</style>
