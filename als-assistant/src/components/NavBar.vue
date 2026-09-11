<script lang="ts" setup>
import { ref, computed } from 'vue'
import { useDisplay } from 'vuetify'
import moment from 'moment'
import { useAuthStore } from '@/stores/auth'
import {
  BookOpen,
  LayoutDashboard,
  GraduationCap,
  Settings,
  Bell,
  LucideLogOut,
  Settings2,
} from 'lucide-vue-next'
import { useRoute } from 'vue-router'
import { USER_TYPE } from '@/shared/constants'

const route = useRoute()

const authStore = useAuthStore()

const { mobile } = useDisplay()

const navItems = [
  { icon: LayoutDashboard, label: 'Dashboard', to: '/dashboard', teacherOnly: false },
  { icon: BookOpen, label: 'Courses', to: '/courses', teacherOnly: false },
  { icon: GraduationCap, label: 'Students', to: '/students', teacherOnly: true },
]

const drawer = ref(true)

const pageTitle = computed(() => route.meta.title || `Good morning, ${authStore.currentUser.first_name}`)
const pageDescription = computed(() => route.meta.description || '')

const userFullName = computed(() => `${authStore.currentUser.first_name} ${authStore.currentUser.last_name}`)
const currentDate = computed(() => moment().format('dddd, MMMM D'))
const classCount = computed(() => `0 classes today`)
const isStudent = computed(() => authStore.currentUser.type == USER_TYPE.STUDENT)

const userInitials = computed(() => {
  const name = userFullName.value
  return name
    .split(' ')
    .map((n) => n[0])
    .join('')
    .substring(0, 2)
    .toUpperCase()
})

const filteredNavItems = computed(() => {
  return navItems.filter(item => !(item.teacherOnly && isStudent.value))
})

</script>

<template>
  <v-bottom-navigation
    v-if="mobile"
    grow
    class="border-t border-slate-200"
  >
    <v-btn
      v-for="item in filteredNavItems"
      :key="item.label"
      :to="item.to"
      color="primary"
      :value="item.label"
    >
      <component :is="item.icon" class="size-4 mb-1" />
      <span>{{ item.label }}</span>
    </v-btn>
    <v-btn
      to="/settings"
      color="primary"
      value="settings"
    >
      <component :is="Settings2" class="size-4 mb-1" />
      <span>Settings</span>
    </v-btn>
  </v-bottom-navigation>

  <v-navigation-drawer
    v-else
    v-model="drawer"
    border
    class="border-r border-slate-200"
  >
    <v-list-item class="px-5 py-5 border-b border-gray-200">
      <template #prepend>
        <v-avatar color="primary" size="32" class="text-xs font-semibold text-primary-foreground">
          {{ userInitials }}
        </v-avatar>
      </template>
      <v-list-item-title class="truncate text-sm font-medium text-foreground">{{ userFullName }}</v-list-item-title>
      <!-- <v-list-item-subtitle class="text-xs text-muted-foreground">Dept.</v-list-item-subtitle> -->
    </v-list-item>

    <v-list density="compact" nav class="px-3 py-2 space-y-1">
      <v-list-item
        v-for="item in filteredNavItems"
        :key="item.label"
        link
        :to="item.to"
        color="primary"
        rounded="lg"
        class="hover:bg-gray-100 transition-colors"
      >
        <template #default="{ isActive }">
          <div class="flex items-center w-full">
            <component 
              :is="item.icon" 
              class="size-4 mr-3" 
              :class="isActive ? 'text-primary' : 'text-slate-500'"
            />
            <v-list-item-title 
              class="text-sm font-medium"
              :class="isActive ? 'text-primary' : 'text-slate-500'"
            >
              {{ item.label }}
            </v-list-item-title>
          </div>
        </template>
      </v-list-item>
    </v-list>

    <template #append>
      <v-container class="p-3 border-t border-gray-200">
        <v-btn block variant="text" class="justify-start text-none text-muted-foreground hover:bg-accent" rounded="lg">
          <template #prepend>
            <Settings class="size-4 mr-1" />
          </template>
          Settings
        </v-btn>
        <v-btn block variant="text" @click="authStore.logout" class="justify-start text-error text-muted-foreground hover:bg-accent" rounded="lg">
          <template #prepend>
            <LucideLogOut class="size-4 mr-1" />
          </template>
          Logout
        </v-btn>
        <v-list-item class="mt-2 rounded-lg border border-gray-200">
          <template #prepend>
            <v-avatar color="primary" rounded="lg" size="36">
              <GraduationCap class="size-5 text-primary-foreground" />
            </v-avatar>
          </template>
          
          <v-list-item-title class="text-sm font-semibold text-foreground">ALS Assistant</v-list-item-title>
          <v-list-item-subtitle class="text-xs text-muted-foreground">{{ isStudent ? 'Student' : 'Teacher' }} Portal</v-list-item-subtitle>
        </v-list-item>
      </v-container>
    </template>
  </v-navigation-drawer>

  <v-app-bar flat class="bg-background/80 backdrop-blur px-2 py-2">
    <v-app-bar-title>
      <h1 class="text-2xl font-bold text-primary">{{ pageTitle }}</h1>
      <p class="text-xs text-muted-foreground sm:text-sm text-slate-500">
         {{ pageDescription ? pageDescription : `${currentDate} — ${classCount}` }}
      </p>
    </v-app-bar-title>
    <template #append>
      <v-btn icon variant="plain" rounded="lg" size="small" class="relative text-muted-foreground mr-2 border border-gray-200">
        <Bell class="size-4" />
        <span class="absolute right-1.5 top-1.5 size-2 rounded-full bg-destructive" />
      </v-btn>
    </template>
  </v-app-bar>
</template>