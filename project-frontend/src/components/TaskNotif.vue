<template>
  <div class="toast-container">
    <div
      v-for="(notif, index) in toasts"
      :key="index"
      class="toast"
    >
      {{ notif.message }}
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from "vue";
import Echo from "laravel-echo";
import Pusher from "pusher-js";

export default {
  setup() {
    const toasts = ref([]);
    const user = JSON.parse(localStorage.getItem("user"));
    const token = localStorage.getItem("token");

    // Initialize Pusher
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
      if (user?.id) {
        window.Echo.private(`tasks.${user.id}`).listen("TaskCreated", (e) => {
          const toast = { message: `New task: ${e.task.title}` };
          toasts.value.push(toast);

          // Remove toast after 3 seconds
          setTimeout(() => {
            toasts.value.splice(toasts.value.indexOf(toast), 1);
          }, 3000);
        });
      }
    });

    return { toasts };
  },
};
</script>

<style scoped>
.toast-container {
  position: fixed;
  top: 16px;
  right: 16px;
  z-index: 50;
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.toast {
  background-color: #3b82f6; /* blue */
  color: white;
  padding: 8px 16px;
  border-radius: 8px;
  box-shadow: 0 4px 6px rgba(0,0,0,0.1);
  animation: slideIn 0.3s ease-out;
}

/* Slide-in animation */
@keyframes slideIn {
  0% {
    transform: translateX(100%);
    opacity: 0;
  }
  100% {
    transform: translateX(0);
    opacity: 1;
  }
}
</style>
