<script setup lang="ts">
import { ref, computed } from 'vue'

// Active Tab state ('enrolled' or 'applications')
const currentTab = ref('applications')

// Courses Data
const courses = ref([
  { id: 1, title: 'Basic Literacy Program (BLP)' },
  { id: 2, title: 'Elementary Accreditation & Equivalency (A&E)' },
  { id: 3, title: 'Secondary Accreditation & Equivalency (A&E)' },
])

// Student Applications Data
const applications = ref([
  { id: 101, name: 'John Doe', email: 'john@example.com', requestedCourseId: 1, date: 'Sep 02, 2026', status: 'Pending' },
  { id: 102, name: 'Maria Santos', email: 'maria@example.com', requestedCourseId: 3, date: 'Sep 01, 2026', status: 'Pending' },
  { id: 103, name: 'Robert Lee', email: 'robert@example.com', requestedCourseId: 2, date: 'Aug 30, 2026', status: 'Pending' },
])

// Enrolled Students Data
const enrolledStudents = ref([
  { id: 1, name: 'Alex Johnson', email: 'alex@example.com', courseId: 1, joined: 'Sep 01, 2026' },
  { id: 2, name: 'Michael Brown', email: 'm.brown@example.com', courseId: 2, joined: 'Aug 25, 2026' },
])

// Accept Modal & Selected Student State
const isAcceptDialogOpen = ref(false)
const selectedApp = ref<any>(null)
const assignedCourseId = ref<number | null>(null)

// Application Action Handlers
const openAcceptModal = (app: any) => {
  selectedApp.value = app
  assignedCourseId.value = app.requestedCourseId
  isAcceptDialogOpen.value = true
}

const confirmAccept = () => {
  if (!selectedApp.value || !assignedCourseId.value) return

  // Add to enrolled students list
  enrolledStudents.value.unshift({
    id: selectedApp.value.id,
    name: selectedApp.value.name,
    email: selectedApp.value.email,
    courseId: assignedCourseId.value,
    joined: new Date().toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' })
  })

  // Remove from pending applications
  applications.value = applications.value.filter(a => a.id !== selectedApp.value.id)

  isAcceptDialogOpen.value = false
  selectedApp.value = null
}

const declineApplication = (appId: number) => {
  applications.value = applications.value.filter(a => a.id !== appId)
}

// Helpers
const getCourseTitle = (courseId: number) => {
  return courses.value.find(c => c.id === courseId)?.title || 'Unassigned'
}

// Table Headers
const appHeaders = [
  { title: 'Applicant', key: 'name' },
  { title: 'Requested Course', key: 'requestedCourseId' },
  { title: 'Applied Date', key: 'date' },
  { title: 'Actions', key: 'actions', sortable: false, align: 'end' as const },
]

const enrolledHeaders = [
  { title: 'Student', key: 'name' },
  { title: 'Enrolled Course', key: 'courseId' },
  { title: 'Enrolled Date', key: 'joined' },
]
</script>

<template>
  <v-layout class="bg-slate-50 min-h-screen">
    <v-main class="p-6">
      <!-- Header -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
          <h1 class="text-2xl font-bold text-slate-900">Student Admissions</h1>
          <p class="text-sm text-slate-500">Review pending applications and manage active student enrollments.</p>
        </div>
      </div>

      <!-- Navigation Tabs -->
      <div class="border-b border-slate-200 mb-6">
        <v-tabs v-model="currentTab" color="emerald-700" align-tabs="start">
          <v-tab value="applications" class="capitalize font-semibold">
            Pending Applications
            <v-chip v-if="applications.length" size="x-small" color="emerald-700" class="ml-2 font-bold">
              {{ applications.length }}
            </v-chip>
          </v-tab>
          <v-tab value="enrolled" class="capitalize font-semibold">
            Enrolled Students ({{ enrolledStudents.length }})
          </v-tab>
        </v-tabs>
      </div>

      <!-- TAB 1: PENDING APPLICATIONS -->
      <v-window v-model="currentTab">
        <v-window-item value="applications">
          <v-card flat class="border border-slate-200 rounded-2xl p-4">
            <v-data-table
              :headers="appHeaders"
              :items="applications"
              density="comfortable"
              class="bg-transparent"
            >
              <!-- Applicant Name & Email -->
              <template #item.name="{ item }">
                <div>
                  <p class="font-medium text-slate-900 text-sm">{{ item.name }}</p>
                  <p class="text-xs text-slate-500">{{ item.email }}</p>
                </div>
              </template>

              <!-- Requested Course -->
              <template #item.requestedCourseId="{ item }">
                <span class="text-sm font-medium text-slate-700">
                  {{ getCourseTitle(item.requestedCourseId) }}
                </span>
              </template>

              <!-- Actions (Accept / Decline) -->
              <template #item.actions="{ item }">
                <div class="flex items-center justify-end gap-2">
                  <v-btn
                    color="red-lighten-5"
                    variant="flat"
                    size="small"
                    rounded="lg"
                    class="text-red-700 font-semibold capitalize"
                    @click="declineApplication(item.id)"
                  >
                    Decline
                  </v-btn>
                  <v-btn
                    color="emerald-700"
                    variant="flat"
                    size="small"
                    rounded="lg"
                    class="text-white font-semibold capitalize"
                    @click="openAcceptModal(item)"
                  >
                    Accept
                  </v-btn>
                </div>
              </template>
            </v-data-table>
          </v-card>
        </v-window-item>

        <!-- TAB 2: ENROLLED STUDENTS -->
        <v-window-item value="enrolled">
          <v-card flat class="border border-slate-200 rounded-2xl p-4">
            <v-data-table
              :headers="enrolledHeaders"
              :items="enrolledStudents"
              density="comfortable"
              class="bg-transparent"
            >
              <template #item.name="{ item }">
                <div>
                  <p class="font-medium text-slate-900 text-sm">{{ item.name }}</p>
                  <p class="text-xs text-slate-500">{{ item.email }}</p>
                </div>
              </template>

              <template #item.courseId="{ item }">
                <span class="text-sm font-medium text-slate-700">
                  {{ getCourseTitle(item.courseId) }}
                </span>
              </template>
            </v-data-table>
          </v-card>
        </v-window-item>
      </v-window>

      <!-- ACCEPT CONFIRMATION DIALOG -->
      <v-dialog v-model="isAcceptDialogOpen" max-width="480px">
        <v-card class="rounded-2xl p-2">
          <v-card-title class="text-lg font-bold text-slate-900 pt-4 px-4">
            Accept Application
          </v-card-title>

          <v-card-text class="px-4 py-2 space-y-4">
            <p class="text-sm text-slate-600">
              Confirm enrollment for <strong class="text-slate-900">{{ selectedApp?.name }}</strong>.
            </p>

            <v-select
              v-model="assignedCourseId"
              :items="courses"
              item-title="title"
              item-value="id"
              label="Assign Course"
              variant="outlined"
              density="comfortable"
              rounded="lg"
              hide-details
            ></v-select>
          </v-card-text>

          <v-card-actions class="p-4 flex justify-end gap-2">
            <v-btn variant="text" rounded="lg" @click="isAcceptDialogOpen = false">Cancel</v-btn>
            <v-btn color="emerald-700" variant="elevated" rounded="lg" class="text-white" @click="confirmAccept">
              Confirm & Enroll
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-main>
  </v-layout>
</template>