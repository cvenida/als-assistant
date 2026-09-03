<script lang="ts" setup>
import { ref, computed } from 'vue'
import { useDisplay } from 'vuetify'
import { useAuthStore } from '@/stores/auth'

const authStore = useAuthStore()

const { mobile } = useDisplay()

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
])

const drawer = ref(true)

const userInitials = computed(() => {
  const name = authStore.user?.name || 'Admin User'
  return name
    .split(' ')
    .map((n) => n[0])
    .join('')
    .substring(0, 2)
    .toUpperCase()
})
</script>

<template>
  <v-app-bar flat border class="px-3 border-b border-slate-200 blurred-app-bar">
    <v-app-bar-nav-icon
      v-if="!mobile"
      class="text-emerald-700"
      @click="drawer = !drawer"
    ></v-app-bar-nav-icon>

    <v-app-bar-title class="font-bold text-teal-700 text-slate-800">ALS Assistant</v-app-bar-title>

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

    <!-- <v-btn icon="mdi-bell-outline" variant="text" class="text-slate-600"></v-btn> -->

    <v-menu location="bottom end">
      <template #activator="{ props }">
        <v-avatar color="teal-lighten-4" class="ml-2 cursor-pointer" v-bind="props">
          <span class="text-teal-800 font-bold text-xs">{{ userInitials }}</span>
        </v-avatar>
      </template>
      <v-list v-if="mobile" density="compact" class="rounded-xl border border-slate-200 mt-2">
        <v-list-item
          prepend-icon="mdi-logout"
          title="Logout"
          class="text-red-600"
          @click="authStore.logout()"
        ></v-list-item>
      </v-list>
    </v-menu>
  </v-app-bar>

  <v-navigation-drawer
    v-if="!mobile"
    v-model="drawer"
    border
    class="border-r border-slate-200"
  >
    <v-list nav density="compact" class="p-2">
      <v-list-item
        v-for="item in navItems"
        :key="item.value"
        :prepend-icon="item.icon"
        :title="item.title"
        :value="item.value"
        :to="item.to"
        active-color="emerald-700"
        rounded="lg"
      ></v-list-item>
    </v-list>

    <template #append>
      <div class="p-3 border-t border-slate-200">
        <v-list-item
          @click="authStore.logout()"
          prepend-icon="mdi-logout"
          title="Logout"
          value="logout"
          class="text-red-600"
          rounded="lg"
        ></v-list-item>
      </div>
    </template>
  </v-navigation-drawer>

  <v-bottom-navigation
    v-else
    grow
    class="border-t border-slate-200"
  >
    <v-btn
      v-for="item in navItems"
      :key="item.value"
      :to="item.to"
      active-color="emerald-700"
      :value="item.value"
    >
      <v-icon>{{ item.icon }}</v-icon>
      <span>{{ item.title }}</span>
    </v-btn>
  </v-bottom-navigation>
</template>