import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
  broadcaster: 'pusher',
  key: "1b891a822f15667a8d89",
  cluster: "eu",
  forceTLS: true,
  authEndpoint: 'http://localhost:8000/api/broadcasting/auth', 
  auth: {
    headers: {
      Authorization: `Bearer ${localStorage.getItem('token')}`
    }
  }
});

const userId = JSON.parse(localStorage.getItem('user'))?.id;

if (userId) {
  window.Echo.private(`tasks.${userId}`)
    .listen("TaskCreated", (e) => {
      console.log("📩 Event received:", e);
    })
    .subscription.bind('pusher:subscription_succeeded', () => {
      console.log(`✅ Subscribed to tasks.${userId}`);
    });
} else {
  console.warn("⚠️ No user ID found in localStorage");
} 