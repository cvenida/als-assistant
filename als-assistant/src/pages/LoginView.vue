<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const authStore = useAuthStore()

const isFormValid = ref(false)
const email = ref('')
const password = ref('')
const showPassword = ref(false);

const rules = {
  required: (v) => !!v || 'This field is required',
  email: (v) => /.+@.+\..+/.test(v) || 'Must be a valid email',
}

const handleLogin = async () => {
  if (!isFormValid.value) return

  const success = await authStore.login({
    email: email.value,
    password: password.value,
  })

  if (success) {
    router.push('/dashboard')
  }
}
</script>

<template>
  <div class="w-full flex h-full">
    <v-container class="hidden md:flex w-full md:w-6/12 bg-amber-100/50 p-8 flex-col justify-center items-center text-center border-amber-100">
      <div class="mb-6 text-teal-700">
        <v-icon size="100px">mdi-login</v-icon>
      </div>
    </v-container>

    <div class="w-full md:w-7/12 p-8 md:p-12 flex flex-col justify-center">
      <h1 class="text-3xl font-semibold text-center text-teal-700 mb-1">
        Log in
      </h1>
      <p class="text-xs text-slate-500 text-center font-medium mb-6">
        Welcome back! Please enter your details to continue
      </p>

      <v-form v-model="isFormValid" @submit.prevent="handleLogin">
        <v-text-field
          v-model="email"
          type="email"
          placeholder="Email"
          prepend-inner-icon="mdi-email-outline"
          variant="solo-filled"
          flat
          rounded="lg"
          density="comfortable"
          class="mb-2"
          :rules="[rules.required, rules.email]"
        ></v-text-field>

        <v-text-field
          v-model="password"
          :type="showPassword ? 'text' : 'password'"
          placeholder="Password"
          prepend-inner-icon="mdi-lock-outline"
          :append-inner-icon="showPassword ? 'mdi-eye-off-outline' : 'mdi-eye-outline'"
          @click:append-inner="showPassword = !showPassword"
          variant="solo-filled"
          flat
          rounded="lg"
          density="comfortable"
          class="mb-4"
          :rules="[rules.required]"
        ></v-text-field>

        <v-btn
          type="submit"
          block
          size="large"
          rounded="lg"
          class="text-none bg-emerald-800 hover:bg-emerald-900 text-white font-weight-bold shadow-sm"
          :loading="authStore.isLoading"
          :disabled="!isFormValid"
        >
          Let's start!
        </v-btn>
      </v-form>

      <p class="text-xs text-center text-slate-500 mt-6">
        Don't have an account? 
        <router-link to="/register" class="text-teal-700 font-bold hover:underline">
          Sign up
        </router-link>
      </p>
    </div>
  </div>
</template>