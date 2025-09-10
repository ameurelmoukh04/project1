<template>
  <div class="min-h-screen bg-gray-100 p-6">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg p-6">
      <h1 class="text-2xl font-bold mb-4 text-gray-800">Notifications</h1>

      <div v-if="notifications.length === 0" class="text-gray-500 text-center py-8">
        No notifications yet.
      </div>

      <ul class="space-y-3">
        <li
          v-for="(notif, index) in notifications"
          :key="index"
          class="p-4 border border-gray-200 rounded-lg flex justify-between items-center hover:bg-gray-50 transition"
        >
          <div>
            <p class="font-medium text-gray-700">""new Task""</p>
            <p class="text-sm text-gray-500">{{ notif.message }}</p>
          </div>
          <span class="text-xs text-gray-400">{{ notif.time }}</span>
          <button
              @click="deleteNotif(notif.id)"
              class="text-red-500 hover:text-red-600 font-semibold transition"
            >
              Delete
            </button>
        </li>

      </ul>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from "vue";
import axios from "axios"; // use your axios instance
import api from '../api/axios';

import Echo from "laravel-echo";
import Pusher from "pusher-js";

export default {
  setup() {
    const notifications = ref([]);
    const user = JSON.parse(localStorage.getItem("user"));
    const token = localStorage.getItem("token");

    // Fetch all notifications from backend
    const loadNotifications = async () => {
      try {
        const res = await axios.get("http://localhost:8000/api/notifications", {
          headers: { Authorization: `Bearer ${token}` },
        });
        notifications.value = res.data.notifications.map(n => ({
          ...n,
          time: new Date(n.created_at).toLocaleTimeString(),
        }));
      } catch (err) {
        console.error("Error fetching notifications:", err);
      }
    };
    const deleteNotif = async (id) => {
      try {
        const notifToDelete = notifications.value.find((t) => t.id === id);
        await api.delete(`/notifications/${id}`, {
          headers: { Authorization: `Bearer ${localStorage.getItem('token')}` },
        });
        notifications.value = notifications.value.filter((t) => t.id !== id);
        //showNotification(' Deleted', `Task "${notifToDelete?.title}" deleted.`);
      } catch (err) {
        console.error(err);
      }
    };
    // Initialize Echo + Pusher
    window.Pusher = Pusher;
    window.Echo = new Echo({
      broadcaster: "pusher",
      key: "1b891a822f15667a8d89",
      cluster: "eu",
      forceTLS: true,
      authEndpoint: "http://localhost:8000/api/broadcasting/auth",
      auth: {
        headers: { Authorization: `Bearer ${token}` },
      },
    });

    onMounted(() => {
      loadNotifications();

      if (user?.id) {
        window.Echo.private(`tasks.${user.id}`)
          .listen("TaskCreated", (e) => {
            notifications.value.unshift({
              title: "New Task Created",
              message: e.task.title,
              time: new Date().toLocaleTimeString(),
            });
          })
          .subscription.bind("pusher:subscription_succeeded", () => {
          });
      }
    });

    return { notifications,deleteNotif };
  },
};
</script>

<style scoped>
li {
  transition: all 0.2s ease-in-out;
}
</style>
