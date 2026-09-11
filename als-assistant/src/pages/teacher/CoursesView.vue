<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useCourseStore } from '@/stores/courses'
import { storeToRefs } from 'pinia'
import { capitalize } from 'lodash'

const courseStore = useCourseStore()

const { allCourses, isLoading } = storeToRefs(courseStore)

const searchQuery = ref('')
const selectedStatusFilter = ref(null)
const isAddDialogOpen = ref(false)

const newCourse = ref({
  title: '',
  description: '',
  tags: [],
})

const status = ['active', 'inactive', 'draft']
const availableTags = ref(['PHP', 'Laravel', 'Vue 3', 'Tailwind', 'Backend'])

const filteredCourses = computed(() => {
  return allCourses.value.filter((course) => {
    const matchesLevel = selectedStatusFilter.value
      ? course.level === selectedStatusFilter.value
      : true
    const matchesSearch =
      course.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      course.code.toLowerCase().includes(searchQuery.value.toLowerCase())
    return matchesLevel && matchesSearch
  })
})

const totalEnrolled = computed(() =>
  allCourses.value.reduce((acc, c) => acc + c.enrolledStudents, 0)
)

const handleCreateCourse = () => {
  if (!newCourse.value.title) return

  allCourses.value.unshift({
    id: Date.now(),
    ...newCourse.value,
    enrolledStudents: 0,
  })

  newCourse.value = { code: '', title: '', description: '', tags: [] }
  isAddDialogOpen.value = false
}

const getLevelBadgeColor = (level: Course['level']) => {
  switch (level) {
    case 'active':
      return 'bg-blue-50 text-blue-700 border-blue-200'
    case 'draft':
      return 'bg-gray-50 text-gray-700 border-gray-200'
    case 'inactive':
      return 'bg-emerald-50 text-emerald-700 border-emerald-200'
  }
}

onMounted(async () => {
  if (!allCourses.value.length) await courseStore.fetchCourses();
})
</script>

<template>
  <v-layout class="min-h-screen">
    <v-main class="p-6">
      <div class="flex flex-col sm:flex-row sm:items-end justify-end gap-4 mb-6">
        <v-btn
          color="primary"
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
            <p class="text-2xl font-bold text-primary mt-1">{{ allCourses.length }}</p>
          </v-card>
        </v-col>
        <v-col cols="12" md="6">
          <v-card flat class="p-4 border border-slate-200 rounded-2xl bg-white">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Active Enrollees</p>
            <p class="text-2xl font-bold text-primary mt-1">{{ totalEnrolled || 0 }}</p>
          </v-card>
        </v-col>
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
            v-model="selectedStatusFilter"
            :items="status"
            label="Filter by Level"
            variant="outlined"
            density="compact"
            hide-details
            clearable
            rounded="lg"
          >
            <template #selection="{ item }">
              <span class="text-capitalize">{{ capitalize(item) }}</span>
            </template>

            <template #item="{ item, props }">
              <v-list-item v-bind="props" :title="capitalize(item)" class="text-capitalize" />
            </template>
          </v-select>
        </div>
      </v-card>

      <v-row>
        <v-col
          cols="12"
          md="6"
          v-for="course in filteredCourses"
          :key="course.id"
        >
          <v-card
            flat
            class="border border-slate-200 rounded-2xl bg-white flex flex-col justify-between hover:border-slate-300 transition-all"
          >
            <div class="p-5">
              <div class="flex items-center justify-between gap-2 mb-3">
                <v-container class="flex p-0 gap-1">
                  <v-chip 
                    v-for="tag in course.course_tags" 
                    size="small" 
                    class="bg-muted text-muted-foreground px-4 font-medium"
                  >
                    {{ tag }}
                  </v-chip>
                </v-container>
                  <span
                  :class="[
                    'px-2.5 py-0.5 text-xs font-semibold rounded-full border',
                    getLevelBadgeColor(course.status || 'draft')
                  ]"
                >
                  {{ capitalize(course.status) || 'Draft' }}
                </span>
              </div>

              <h2 class="text-lg font-bold text-slate-900 mb-2">{{ course.title }}</h2>
              <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed">
                {{ course.description }}
              </p>
            </div>

            <div class="px-5 py-3 border-t border-slate-100 flex items-center justify-between rounded-b-2xl">
              <div class="flex items-center gap-1.5 text-xs text-slate-600">
                <v-icon icon="mdi-account-group-outline" size="small" class="text-slate-400"></v-icon>
                <span><strong class="text-slate-900 font-semibold">{{ course.enrolledStudents }}</strong> Enrolled</span>
              </div>

              <v-btn icon="mdi-dots-vertical" variant="text" size="small" color="slate-500"></v-btn>
            </div>
          </v-card>
        </v-col>
      </v-row>

      <v-dialog v-model="isAddDialogOpen" max-width="520px">
        <v-card class="rounded-2xl p-2">
          <v-card-title class="text-lg font-bold text-slate-900 pt-4 px-4">
            Add New Course
          </v-card-title>

          <v-card-text class="space-y-4 px-4 py-2">
            <v-text-field
              v-model="newCourse.title"
              label="Course Title"
              variant="outlined"
              density="comfortable"
              rounded="lg"
            ></v-text-field>

            <v-textarea
              v-model="newCourse.description"
              label="Course Description"
              variant="outlined"
              density="comfortable"
              rows="3"
              rounded="lg"
            ></v-textarea>

            <v-combobox
              v-model="newCourse.tags"
              :items="availableTags"
              variant="outlined"
              chips
              multiple
              clearable
              label="Course Tags"
              density="comfortable"
              rounded="lg"
              hint="Type a tag and press Enter to add"
              persistent-hint
            >
              <template #chip="{ props, item }">
                <v-chip
                  v-bind="props"
                  size="small"
                  class="text-capitalize font-medium"
                  closable
                >
                  {{ item.raw }}
                </v-chip>
              </template>
            </v-combobox>
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