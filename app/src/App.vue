<template>
  <v-app class="min-h-screen">
    <NavBar v-if="authStore.isAuthenticated" />
    <v-main>
      <router-view />
    </v-main>
  </v-app>
</template>

<script lang="ts" setup>
import NavBar from '@/components/NavBar.vue'
import { useAuthStore } from '@/stores/auth'
import { onMounted } from 'vue'
import { applyTheme } from '@/shared/constants'

const authStore = useAuthStore()

onMounted(() => {
  const savedTheme = localStorage.getItem('user-theme') || 'system'
  applyTheme(savedTheme)

  window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
    applyTheme('system')
  })
})
</script>