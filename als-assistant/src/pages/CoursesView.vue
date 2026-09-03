<script setup lang="ts">
import { ref, computed } from 'vue'

// Types
interface Course {
  id: number
  code: string
  title: string
  description: string
  level: 'BLP' | 'Elementary A&E' | 'Secondary A&E'
  enrolledStudents: number
  status: 'Active' | 'Inactive'
}

// State
const searchQuery = ref('')
const selectedLevelFilter = ref<string | null>(null)
const isAddDialogOpen = ref(false)

// Sample Courses Data
const courses = ref<Course[]>([
  {
    id: 1,
    code: 'ALS-BLP-01',
    title: 'Basic Literacy Program',
    description: 'Focuses on basic reading, writing, and numeracy skills for out-of-school youth and adults.',
    level: 'BLP',
    enrolledStudents: 34,
    status: 'Active',
  },
  {
    id: 2,
    code: 'ALS-AE-ELEM',
    title: 'Elementary Accreditation & Equivalency',
    description: 'Designed for learners seeking elementary level accreditation through standardized modular assessments.',
    level: 'Elementary A&E',
    enrolledStudents: 52,
    status: 'Active',
  },
  {
    id: 3,
    code: 'ALS-AE-SEC',
    title: 'Secondary Accreditation & Equivalency',
    description: 'Prepares high school level learners for secondary equivalency certification and life skill application.',
    level: 'Secondary A&E',
    enrolledStudents: 89,
    status: 'Active',
  },
])

const newCourse = ref<Omit<Course, 'id' | 'enrolledStudents'>>({
  code: '',
  title: '',
  description: '',
  level: 'BLP',
  status: 'Active',
})

const levels = ['BLP', 'Elementary A&E', 'Secondary A&E']

// Filtered Courses Computed Property
const filteredCourses = computed(() => {
  return courses.value.filter((course) => {
    const matchesLevel = selectedLevelFilter.value
      ? course.level === selectedLevelFilter.value
      : true
    const matchesSearch =
      course.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      course.code.toLowerCase().includes(searchQuery.value.toLowerCase())
    return matchesLevel && matchesSearch
  })
})

const totalEnrolled = computed(() =>
  courses.value.reduce((acc, c) => acc + c.enrolledStudents, 0)
)

const handleCreateCourse = () => {
  if (!newCourse.value.title || !newCourse.value.code) return

  courses.value.unshift({
    id: Date.now(),
    ...newCourse.value,
    enrolledStudents: 0,
  })

  newCourse.value = { code: '', title: '', description: '', level: 'BLP', status: 'Active' }
  isAddDialogOpen.value = false
}

const getLevelBadgeColor = (level: Course['level']) => {
  switch (level) {
    case 'BLP':
      return 'bg-blue-50 text-blue-700 border-blue-200'
    case 'Elementary A&E':
      return 'bg-amber-50 text-amber-700 border-amber-200'
    case 'Secondary A&E':
      return 'bg-emerald-50 text-emerald-700 border-emerald-200'
  }
}
</script>

<template>
  <v-layout class="bg-slate-50 min-h-screen">
    <v-main class="p-6">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
          <h1 class="text-2xl font-bold text-slate-900">Courses</h1>
          <p class="text-sm text-slate-500">Manage learning programs and track student enrollments.</p>
        </div>

        <v-btn
          color="emerald-700"
          prepend-icon="mdi-plus"
          rounded="lg"
          class="capitalize text-white font-medium"
          @click="isAddDialogOpen = true"
        >
          Add New Course
        </v-btn>
      </div>

      <v-row class="mb-3">
        <v-col cols="12" md="6">
          <v-card flat class="p-4 border border-slate-200 rounded-2xl bg-white">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Courses</p>
            <p class="text-2xl font-bold text-slate-900 mt-1">{{ courses.length }}</p>
          </v-card>
        </v-col>
        <v-col cols="12" md="6">
          <v-card flat class="p-4 border border-slate-200 rounded-2xl bg-white">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Active Enrollees</p>
            <p class="text-2xl font-bold text-emerald-700 mt-1">{{ totalEnrolled }}</p>
          </v-card>
        </v-col>
        <!-- <v-card flat class="p-4 border border-slate-200 rounded-2xl bg-white">
          <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Programs Offered</p>
          <p class="text-2xl font-bold text-slate-900 mt-1">3 ALS Levels</p>
        </v-card> -->
      </v-row>

      <v-card flat class="p-4 border border-slate-200 rounded-2xl mb-6 bg-white">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
          <v-text-field
            v-model="searchQuery"
            placeholder="Search by code or title..."
            prepend-inner-icon="mdi-magnify"
            variant="outlined"
            density="compact"
            hide-details
            clearable
            rounded="lg"
            class="sm:col-span-2"
          ></v-text-field>

          <v-select
            v-model="selectedLevelFilter"
            :items="levels"
            label="Filter by Level"
            variant="outlined"
            density="compact"
            hide-details
            clearable
            rounded="lg"
          ></v-select>
        </div>
      </v-card>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <v-card
          v-for="course in filteredCourses"
          :key="course.id"
          flat
          class="border border-slate-200 rounded-2xl bg-white flex flex-col justify-between hover:border-slate-300 transition-all"
        >
          <div class="p-5">
            <div class="flex items-center justify-between gap-2 mb-3">
              <span class="text-xs font-mono font-bold text-slate-500 uppercase">
                {{ course.code }}
              </span>
              <span
                :class="[
                  'px-2.5 py-0.5 text-xs font-semibold rounded-full border',
                  getLevelBadgeColor(course.level)
                ]"
              >
                {{ course.level }}
              </span>
            </div>

            <h2 class="text-lg font-bold text-slate-900 mb-2">{{ course.title }}</h2>
            <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed">
              {{ course.description }}
            </p>
          </div>

          <div class="px-5 py-3 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between rounded-b-2xl">
            <div class="flex items-center gap-1.5 text-xs text-slate-600">
              <v-icon icon="mdi-account-group-outline" size="small" class="text-slate-400"></v-icon>
              <span><strong class="text-slate-900 font-semibold">{{ course.enrolledStudents }}</strong> Enrolled</span>
            </div>

            <v-btn icon="mdi-dots-vertical" variant="text" size="small" color="slate-500"></v-btn>
          </div>
        </v-card>
      </div>

      <v-dialog v-model="isAddDialogOpen" max-width="520px">
        <v-card class="rounded-2xl p-2">
          <v-card-title class="text-lg font-bold text-slate-900 pt-4 px-4">
            Add New Course
          </v-card-title>

          <v-card-text class="space-y-4 px-4 py-2">
            <v-text-field
              v-model="newCourse.code"
              label="Course Code (e.g., ALS-BLP-02)"
              variant="outlined"
              density="comfortable"
              rounded="lg"
            ></v-text-field>

            <v-text-field
              v-model="newCourse.title"
              label="Course Title"
              variant="outlined"
              density="comfortable"
              rounded="lg"
            ></v-text-field>

            <v-select
              v-model="newCourse.level"
              :items="levels"
              label="ALS Program Level"
              variant="outlined"
              density="comfortable"
              rounded="lg"
            ></v-select>

            <v-textarea
              v-model="newCourse.description"
              label="Course Description"
              variant="outlined"
              density="comfortable"
              rows="3"
              rounded="lg"
            ></v-textarea>
          </v-card-text>

          <v-card-actions class="p-4 flex justify-end gap-2">
            <v-btn variant="text" rounded="lg" @click="isAddDialogOpen = false">Cancel</v-btn>
            <v-btn
              color="emerald-700"
              variant="elevated"
              rounded="lg"
              class="text-white capitalize"
              @click="handleCreateCourse"
            >
              Create Course
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-main>
  </v-layout>
</template>