<script setup>
import { ref } from 'vue'
import { storeToRefs } from 'pinia'

const stats = ref([
  { title: 'Total Students', value: '1,284', icon: 'mdi-school', color: 'text-teal-600', bg: 'bg-teal-50', change: '+12%', isUp: true },
  { title: 'Active Courses', value: '42', icon: 'mdi-book-open-page-variant', color: 'text-purple-600', bg: 'bg-purple-50', change: '0%', isUp: true },
])

const headers = [
  { title: 'User', key: 'name' },
  { title: 'Role', key: 'role' },
  { title: 'Status', key: 'status' },
  { title: 'Joined Date', key: 'joined' }
]

const recentUsers = ref([
  { name: 'Alex Johnson', email: 'alex@example.com', role: 'Student', status: 'Active', joined: 'Sep 01, 2026' },
  { name: 'Sarah Smith', email: 'sarah@example.com', role: 'Teacher', status: 'Active', joined: 'Aug 28, 2026' },
  { name: 'Michael Brown', email: 'm.brown@example.com', role: 'Student', status: 'Pending', joined: 'Aug 25, 2026' },
  { name: 'Emily Davis', email: 'emily@example.com', role: 'Student', status: 'Active', joined: 'Aug 20, 2026' }
])
</script>

<template>
  <v-layout class="bg-slate-50 min-h-screen">
    <v-main class="p-6">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
          <h1 class="text-2xl font-bold text-slate-900">Overview</h1>
          <p class="text-sm text-slate-500">Welcome back teacher! Here is what is happening today.</p>
        </div>
      </div>

      <div class="mb-3">
        <v-row>
          <v-col cols="6" v-for="stat in stats" :key="stat.title">
            <v-card flat class="p-4 border border-slate-200 rounded-2xl">
              <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-medium text-slate-500">{{ stat.title }}</span>
                <div :class="[stat.bg, stat.color, 'p-2 rounded-xl flex items-center justify-center']">
                  <v-icon :icon="stat.icon" size="20"></v-icon>
                </div>
              </div>
              <div class="flex items-baseline justify-between">
                <h2 class="text-2xl font-bold text-slate-900">{{ stat.value }}</h2>
                <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">
                  {{ stat.change }}
                </span>
              </div>
            </v-card>
          </v-col>
        </v-row>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <v-card flat class="lg:col-span-2 border border-slate-200 rounded-2xl p-4">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-bold text-slate-800">Recent Enrolments</h3>
            <v-btn variant="text" size="small" color="teal-darken-2" class="capitalize">View All</v-btn>
          </div>

          <v-data-table
            :headers="headers"
            :items="recentUsers"
            density="comfortable"
            class="bg-transparent"
          >
            <template #item.name="{ item }">
              <div>
                <p class="font-medium text-slate-900 text-sm">{{ item.name }}</p>
                <p class="text-xs text-slate-500">{{ item.email }}</p>
              </div>
            </template>

            <template #item.status="{ item }">
              <span
                :class="[
                  'px-2 py-1 text-xs font-medium rounded-full',
                  item.status === 'Active' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700'
                ]"
              >
                {{ item.status }}
              </span>
            </template>
          </v-data-table>
        </v-card>

        <v-card flat class="border border-slate-200 rounded-2xl p-4">
          <h3 class="text-base font-bold text-slate-800 mb-4">Quick Notice</h3>
          <div class="space-y-4">
            <div class="p-3 bg-slate-100 rounded-xl">
              <p class="text-sm font-semibold text-slate-800">System Maintenance</p>
              <p class="text-xs text-slate-500 mt-1">Scheduled database maintenance will occur this Saturday at 12:00 AM UTC.</p>
            </div>
            <div class="p-3 bg-teal-50 text-teal-900 rounded-xl">
              <p class="text-sm font-semibold">New Term Registration</p>
              <p class="text-xs text-teal-700 mt-1">Teacher applications for the upcoming term are now open.</p>
            </div>
          </div>
        </v-card>
      </div>
    </v-main>
  </v-layout>
</template>