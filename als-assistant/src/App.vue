<template>
  <v-app>
    <NavBar v-if="authStore.isAuthenticated" />

    <v-main>
      <router-view />
    </v-main>
  </v-app>
</template>

<script lang="ts" setup>
import NavBar from '@/components/NavBar.vue'
import { useAuthStore } from '@/stores/auth'

// Access state from Pinia auth store
const authStore = useAuthStore()
</script>