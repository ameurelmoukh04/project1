<template>
  <div class="min-h-screen flex flex-col">
    <Navbar />
    <main class="flex-1 w-full px-4 md:px-8 py-6 bg-gray-50">
       <TaskNotif />
      <router-view />
    </main>
  </div>
</template>

<script>
import Navbar from './components/Navbar.vue';

export default {
  components: { Navbar }
}
</script>
