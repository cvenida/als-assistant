<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useCourseStore } from '@/stores/courses'
import {
  BookOpen,
  Users,
  ClipboardCheck,
  TrendingUp,
  Search,
  LayoutDashboard,
  GraduationCap,
  CalendarDays,
  MessageSquare,
  Settings,
  Bell,
  Clock,
  MapPin,
  CheckCircle2,
  Circle,
  ChevronRight,
  PenLine,
  CalendarClock,
  Megaphone,
  FileText,
  User,
  Paperclip,
} from 'lucide-vue-next'
import { storeToRefs } from 'pinia'

const courseStore = useCourseStore()

const { allCourses, isLoading } = storeToRefs(courseStore)

const courseList = ref([]);

const appHeaders = [
  { title: 'Applicant', key: 'name' },
  { title: 'Requested Course', key: 'requestedCourseId' },
  { title: 'Applied Date', key: 'date' },
  { title: 'Actions', key: 'actions', sortable: false, align: 'end' as const },
]

const query = ref('')
const courseFilter = ref('All courses')
const statusFilter = ref('All statuses')


// Pending applications
const isAcceptDialogOpen = ref(false)
const selectedApp = ref(null)
const assignedCourseId = ref(null)

const openAcceptModal = (app: any) => {
  selectedApp.value = app
  assignedCourseId.value = app.requestedCourseId
  isAcceptDialogOpen.value = true
}

const applications = ref([
  { id: 101, name: 'John Doe', email: 'john@example.com', requestedCourseId: 1, date: 'Sep 02, 2026', status: 'Pending' },
  { id: 102, name: 'Maria Santos', email: 'maria@example.com', requestedCourseId: 3, date: 'Sep 01, 2026', status: 'Pending' },
  { id: 103, name: 'Robert Lee', email: 'robert@example.com', requestedCourseId: 2, date: 'Aug 30, 2026', status: 'Pending' },
])

const confirmAccept = () => {
  if (!selectedApp.value || !assignedCourseId.value) return

  // Add to enrolled students list


  // Remove from pending applications
  applications.value = applications.value.filter(a => a.id !== selectedApp.value.id)

  isAcceptDialogOpen.value = false
  selectedApp.value = null
}

const declineApplication = (appId) => {
  applications.value = applications.value.filter(a => a.id !== appId)
}

const getCourseTitle = (courseId) => {
  return allCourses.value.find(c => c.id === courseId)?.title || 'Unassigned'
}

function getInitials(name: string) {
  return name.split(' ').map((n) => n[0]).join('')
}


// Recent Activity Feed Data
const activities = ref([
  {
    id: 1,
    title: 'New assignment submitted',
    description: 'John Doe submitted Activity 2 in Laravel Advanced',
    time: '10m ago',
    icon: FileText,
    color: 'text-blue-600 bg-blue-50',
  },
  {
    id: 2,
    title: 'Course application received',
    description: 'Maria Santos requested to join ALS Module 1',
    time: '1h ago',
    icon: User,
    color: 'text-emerald-600 bg-emerald-50',
  },
  {
    id: 3,
    title: 'Quiz completed',
    description: 'Robert Lee completed Pre-Assessment Quiz',
    time: '3h ago',
    icon: CheckCircle2,
    color: 'text-amber-600 bg-amber-50',
  },
])

onMounted(async () => {
  courseList.value = await courseStore.fetchCourses();
})
</script>

<template>
  <v-container fluid class="space-y-8 p-4 my-4 sm:p-6">
    <v-row>
      <v-col cols="6" xl="3">
        <v-card flat rounded="xl" class="bg-card p-4 sm:p-5 border border-gray-200">
          <v-container class="flex items-center justify-between p-0">
            <span class="text-xs font-medium text-muted-foreground sm:text-sm text-gray-600">Courses</span>
            <v-avatar color="primary" variant="tonal" size="32" rounded="lg">
              <BookOpen class="size-4 text-primary" />
            </v-avatar>
          </v-container>
          <p v-if='!isLoading' class="mt-2 text-2xl font-bold tracking-tight text-foreground sm:text-3xl">{{ allCourses.length }}</p>
          <v-progress-circular
            v-else
            color="primary"
            class="mt-4"
            indeterminate
          ></v-progress-circular>
        </v-card>
      </v-col>

      <v-col cols="6" xl="3">
        <v-card flat rounded="xl" class="bg-card p-4 sm:p-5 border border-gray-200">
          <v-container class="flex items-center justify-between p-0">
            <span class="text-xs font-medium text-muted-foreground sm:text-sm text-gray-600">Total students</span>
            <v-avatar color="primary" variant="tonal" size="32" rounded="lg">
              <Users class="size-4 text-primary" />
            </v-avatar>
          </v-container>
          <div v-if='!isLoading'>
            <p class="mt-2 text-2xl font-bold tracking-tight text-foreground sm:text-3xl">0</p>
            <p class="mt-1 text-xs text-muted-foreground">Across 6 courses</p>
          </div>
          <v-progress-circular
            v-else
            color="primary"
            class="mt-4"
            indeterminate
          ></v-progress-circular>
        </v-card>
      </v-col>
    </v-row>

    <div>
      <div class=" mb-4 flex items-center justify-between p-0">
        <h2 class="text-base font-semibold text-foreground">Recent courses</h2>
        <v-btn variant="text" color="primary" class="text-none font-medium text-sm">
          View all <ChevronRight class="size-4 ml-1" />
        </v-btn>
      </div>
      <v-row v-if="!isLoading">
        <v-col v-for="course in allCourses" :key="course.id" cols="12" sm="6" xl="4">
          <v-card flat rounded="xl" class="cursor-pointer group bg-card p-5 transition-shadow hover:shadow-md border border-gray-200">
            <v-container class="flex items-center p-0 gap-2">
              <span :class="['size-2.5 rounded-full', course.color || 'bg-emerald-500']" />
              <h3 class="font-semibold text-foreground group-hover:text-primary">{{ course.title }}</h3>
            </v-container>
            <v-container class="mt-2 space-y-1.5 p-0 text-sm text-muted-foreground text-gray-600">
              <p class="flex items-center gap-2 text-muted-foreground">
                <User class="size-3.5" /> {{ course.students || 0 }} students
              </p>
              <p class="flex items-center gap-2 text-muted-foreground">
                <Paperclip class="size-3.5" /> {{ course.activities || 0 }} activities
              </p>
            </v-container>

            <v-container class="flex p-0 gap-2 mt-3">
              <v-chip 
                v-for="tag in course.course_tags" 
                size="small" 
                class="bg-muted text-muted-foreground px-4 font-medium"
              >
                {{ tag }}
              </v-chip>
            </v-container>
          </v-card>
        </v-col>
      </v-row>
      <v-row v-else>
        <v-col v-for="x in 2" :key="x" cols="12" sm="6" xl="4">
          <v-skeleton-loader type="article" class="rounded-lg border border-gray-200"></v-skeleton-loader>
        </v-col>
      </v-row>
    </div>


    <v-row class="mt-4">
      <v-col cols="12" lg="7" xl="8">
        <v-card flat class="border border-gray-200 rounded-2xl p-4 sm:p-5">
          <div class="mb-4 flex items-center justify-between">
            <div>
              <h2 class="text-base font-semibold text-foreground">Pending Applications</h2>
              <p class="text-xs text-gray-500">Review student enrollment requests</p>
            </div>
            <v-chip size="small" class="font-medium bg-amber-50 text-amber-700">
              {{ applications.length }} Pending
            </v-chip>
          </div>

          <div class="w-full max-w-full overflow-x-auto">
            <v-data-table
              :headers="appHeaders"
              :items="applications"
              density="comfortable"
              class="bg-transparent"
            >
              <template #item.name="{ item }">
                <div>
                  <p class="font-medium text-slate-900 text-sm">{{ item.name }}</p>
                  <p class="text-xs text-slate-500">{{ item.email }}</p>
                </div>
              </template>

              <template #item.requestedCourseId="{ item }">
                <span class="text-sm font-medium text-slate-700">
                  {{ getCourseTitle(item.requestedCourseId) }}
                </span>
              </template>

              <template #item.actions="{ item }">
                <div class="flex items-center justify-end gap-2">
                  <v-btn
                    variant="flat"
                    size="small"
                    rounded="lg"
                    class="bg-red-50 text-red-700 font-semibold text-none"
                    @click="declineApplication(item.id)"
                  >
                    Decline
                  </v-btn>
                  <v-btn
                    variant="flat"
                    size="small"
                    rounded="lg"
                    class="bg-emerald-50 text-emerald-700 font-semibold text-none"
                    @click="openAcceptModal(item)"
                  >
                    Accept
                  </v-btn>
                </div>
              </template>
            </v-data-table>
          </div>
        </v-card>
      </v-col>

      <!-- Right Column: Recent Activity Feed -->
      <v-col cols="12" lg="5" xl="4">
        <v-card flat class="border border-gray-200 rounded-2xl p-4 sm:p-5 h-full">
          <div class="mb-4 flex items-center justify-between">
            <h2 class="text-base font-semibold text-foreground">Recent Activity</h2>
            <span class="text-xs text-muted-foreground text-gray-500">Live feed</span>
          </div>

          <div class="space-y-4">
            <div 
              v-for="activity in activities" 
              :key="activity.id"
              class="flex items-start gap-3 p-2.5 rounded-xl transition-colors hover:bg-slate-50"
            >
              <!-- Activity Icon Badge -->
              <div :class="['p-2 rounded-lg flex-shrink-0', activity.color]">
                <component :is="activity.icon" class="size-4" />
              </div>

              <!-- Activity Details -->
              <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between gap-2">
                  <p class="text-sm font-medium text-slate-900 truncate">
                    {{ activity.title }}
                  </p>
                  <span class="text-[10px] text-slate-400 whitespace-nowrap">
                    {{ activity.time }}
                  </span>
                </div>
                <p class="text-xs text-slate-500 line-clamp-2 mt-0.5">
                  {{ activity.description }}
                </p>
              </div>
            </div>
          </div>
        </v-card>
      </v-col>
    </v-row>

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
              :items="allCourses"
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
            <v-btn variant="elevated" rounded="lg" class="bg-emerald-700 text-white" @click="confirmAccept">
              Confirm & Enroll
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
  </v-container>
</template>