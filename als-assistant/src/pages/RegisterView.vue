<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { USER_TYPE } from '@/shared/constants'

const router = useRouter()
const authStore = useAuthStore()

const isFormValid = ref(false)
const firstName = ref('')
const lastName = ref('')
const email = ref('')
const password = ref('')
const confirmPassword = ref('')
const errorMessage = ref('')
const userType = ref(USER_TYPE.STUDENT)

const rules = {
  required: (v) => !!v || 'This field is required',
  email: (v) => /.+@.+\..+/.test(v) || 'Must be a valid email',
  matchPassword: (v) => v === password.value || 'Passwords do not match',
}

const handleSignup = async () => {
  if (!isFormValid.value) return
  errorMessage.value = ''

  const success = await authStore.signup({
    firstName: firstName.value,
    lastName: lastName.value,
    email: email.value,
    type: userType.value,
    password: password.value,
  })

  if (success) {
    router.push('/dashboard')
  }
}
</script>

<template>
  <div class="w-full flex h-full">
    <v-container class="hidden md:flex w-full md:w-5/12 bg-emerald-500/10 p-8 flex-col justify-center items-center text-center border-r border-zinc-200 dark:border-zinc-800">
      <div class="mb-6 text-primary">
        <v-icon size="100px">mdi-account-plus</v-icon>
      </div>
    </v-container>

    <div class="w-full md:w-7/12 p-8 md:p-12 flex flex-col justify-center bg-surface">
      <h1 class="text-3xl font-semibold text-center text-primary mb-1">
        Create an account
      </h1>
      <p class="text-xs text-zinc-500 dark:text-zinc-400 text-center font-medium mb-6">
        Fill in your details below to get started
      </p>

      <v-form v-model="isFormValid" class="flex flex-col gap-3" @submit.prevent="handleSignup">
        <v-radio-group
          v-model="userType"
          hide-details
          density="compact"
          class="m-0 p-3"
        >
          <div class="bg-zinc-100 dark:bg-zinc-800 p-2 w-full rounded-2xl flex justify-center border border-zinc-200 dark:border-zinc-700">
            <div class="flex items-center mr-3 cursor-pointer" @click="userType = USER_TYPE.STUDENT">
              <v-radio
                :value="USER_TYPE.STUDENT"
                density="compact"
                color="primary"
                class="flex-1 justify-center rounded-xl transition-all"
              ></v-radio>
              <p class="text-sm text-zinc-700 dark:text-zinc-300 font-medium select-none">As a student</p>
            </div>
            <div class="flex items-center mr-3 cursor-pointer" @click="userType = USER_TYPE.TEACHER">
              <v-radio
                :value="USER_TYPE.TEACHER"
                density="compact"
                color="primary"
                class="flex-1 justify-center rounded-xl transition-all"
              ></v-radio>
              <p class="text-sm text-zinc-700 dark:text-zinc-300 font-medium select-none">As a teacher</p>
            </div>
          </div>
        </v-radio-group>

        <v-row>
          <v-col cols="12" md="6">
            <v-text-field
              v-model="firstName"
              placeholder="First Name"
              prepend-inner-icon="mdi-account-outline"
              variant="outlined"
              flat
              hide-details
              rounded="lg"
              density="comfortable"
              class="mb-2"
              :rules="[rules.required]"
            ></v-text-field>
          </v-col>
          <v-col cols="12" md="6">
            <v-text-field
              v-model="lastName"
              placeholder="Last Name"
              prepend-inner-icon="mdi-account-outline"
              variant="outlined"
              flat
              hide-details
              rounded="lg"
              density="comfortable"
              class="mb-2"
              :rules="[rules.required]"
            ></v-text-field>
          </v-col>
        </v-row>

        <v-text-field
          v-model="email"
          type="email"
          placeholder="Email"
          prepend-inner-icon="mdi-email-outline"
          hide-details
          variant="outlined"
          flat
          rounded="lg"
          density="comfortable"
          class="mb-2"
          :rules="[rules.required, rules.email]"
        ></v-text-field>

        <v-text-field
          v-model="password"
          type="password"
          placeholder="Password"
          prepend-inner-icon="mdi-lock-outline"
          variant="outlined"
          hide-details
          flat
          rounded="lg"
          density="comfortable"
          class="mb-2"
          :rules="[rules.required]"
        ></v-text-field>

        <v-text-field
          v-model="confirmPassword"
          type="password"
          placeholder="Confirm Password"
          prepend-inner-icon="mdi-lock-check-outline"
          variant="outlined"
          hide-details
          flat
          rounded="lg"
          density="comfortable"
          class="mb-4"
          :rules="[rules.required, rules.matchPassword]"
        ></v-text-field>

        <v-btn
          type="submit"
          block
          size="large"
          color="primary"
          rounded="lg"
          class="text-none font-weight-bold shadow-sm"
          :loading="authStore.isLoading"
          :disabled="!isFormValid"
        >
          Create Account
        </v-btn>
      </v-form>

      <p class="text-xs text-center text-zinc-500 dark:text-zinc-400 mt-6">
        Already have an account? 
        <router-link to="/login" class="text-primary font-bold hover:underline">
          Log in
        </router-link>
      </p>
    </div>
  </div>
</template>