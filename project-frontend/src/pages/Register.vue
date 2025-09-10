<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
      <h1 class="text-2xl font-bold mb-6 text-center">Register</h1>
      <form @submit.prevent="register" class="space-y-4">
        <input
          type="text"
          v-model="full_name"
          placeholder="Full Name"
          required
          class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
        />

        <input
          type="email"
          v-model="email"
          placeholder="Email"
          required
          class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
        />

        <input
          type="password"
          v-model="password"
          placeholder="Password"
          required
          class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
        />

        <input
          type="text"
          v-model="phone_number"
          placeholder="Phone Number"
          class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
        />

        <input
          type="text"
          v-model="address"
          placeholder="Address"
          class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
        />

        <input
          type="file"
          @change="handleFileUpload"
          accept="image/*"
          class="w-full"
        />

        <button
          type="submit"
          class="w-full bg-blue-500 text-white p-3 rounded hover:bg-blue-600 transition"
        >
          Register
        </button>
      </form>

      <p class="mt-4 text-center text-gray-600">
        Already have an account?
        <router-link to="/login" class="text-blue-500 hover:underline">Login</router-link>
      </p>
    </div>
  </div>
</template>

<script>
import api from '../api/axios';
import { ref } from 'vue';

export default {
  setup() {
    const full_name = ref('');
    const email = ref('');
    const password = ref('');
    const phone_number = ref('');
    const address = ref('');
    const profile_picture = ref(null);

    const handleFileUpload = (event) => {
      profile_picture.value = event.target.files[0];
    };

    const register = async () => {
      try {
        const formData = new FormData();
        formData.append('full_name', full_name.value);
        formData.append('email', email.value);
        formData.append('password', password.value);
        formData.append('phone_number', phone_number.value);
        formData.append('address', address.value);
        if (profile_picture.value) {
          formData.append('profile_picture', profile_picture.value);
        }

        const res = await api.post('/register', formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
          },
        });

        // const token = res.data.Authorization.token;
        // const user = res.data.user;

        // localStorage.setItem('token', token);
        // localStorage.setItem('user', JSON.stringify(user));

        window.location.href = '/login'; // redirect
      } catch (err) {
        alert(err.response?.data?.message || 'Registration failed');
      }
    };

    return {
      full_name,
      email,
      password,
      phone_number,
      address,
      profile_picture,
      handleFileUpload,
      register,
    };
  },
};
</script>
