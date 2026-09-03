<script lang="ts" setup>
import { ref } from 'vue'
import { useAuthStore } from '@/stores/auth'

const authStore = useAuthStore()

const navItems = ref([
  {
    title: 'Dashboard',
    value: 'dashboard',
    icon: 'mdi-view-dashboard',
    to: '/dashboard',
  },
  {
    title: 'Students',
    value: 'students',
    icon: 'mdi-school',
    to: '/students',
  },
  {
    title: 'Courses',
    value: 'courses',
    icon: 'mdi-book-open-variant',
    to: '/courses',
  },
]);

const drawer = ref(true)
</script>
<template>
  <v-app-bar flat border class="px-3 bg-emerald-700 border-b border-slate-200">
    <v-app-bar-nav-icon class="text-white" @click="drawer = !drawer"></v-app-bar-nav-icon>
      <v-app-bar-title class="font-bold text-white text-slate-800">Dashboard</v-app-bar-title>

      <v-spacer></v-spacer>

      <v-text-field
        placeholder="Search..."
        prepend-inner-icon="mdi-magnify"
        variant="solo-filled"
        flat
        hide-details
        density="compact"
        rounded="lg"
        class="max-w-xs hidden sm:block mr-4"
      ></v-text-field>

      <v-btn icon="mdi-bell-outline" variant="text" class="text-white text-slate-600"></v-btn>
      <v-avatar color="teal-lighten-4" class="ml-2 cursor-pointer">
        <span class="text-teal-800 font-bold">AB</span>
      </v-avatar>
    </v-app-bar>
    <v-navigation-drawer v-model="drawer" border class="border-r border-slate-200">
      <v-list nav density="compact" class="p-2">
        <v-list-item
          v-for="item in navItems"
          :key="item.value"
          :prepend-icon="item.icon"
          :title="item.title"
          :value="item.value"
          :to="item.to"
          active-color="teal-darken-2"
          rounded="lg"
        ></v-list-item>
      </v-list>

      <template #append>
        <div class="p-3 border-t border-slate-200">
          <!-- <v-list-item prepend-icon="mdi-cog-outline" title="Settings" value="settings" rounded="lg"></v-list-item> -->
          <v-list-item @click="authStore.logout()" prepend-icon="mdi-logout" title="Logout" value="logout" class="text-red-600" rounded="lg"></v-list-item>
        </div>
      </template>
    </v-navigation-drawer>
</template>