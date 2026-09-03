<template>
  <v-app class="bg-slate-50 min-h-screen">
    <NavBar v-if="authStore.isAuthenticated" />

    <v-main>
      <router-view />
    </v-main>
  </v-app>
</template>

<script lang="ts" setup>
import NavBar from '@/components/NavBar.vue'
import { useAuthStore } from '@/stores/auth'

const authStore = useAuthStore()
</script>