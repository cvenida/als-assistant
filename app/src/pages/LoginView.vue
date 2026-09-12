<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const authStore = useAuthStore()

const isFormValid = ref(false)
const email = ref('')
const password = ref('')
const showPassword = ref(false)

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
    <v-container class="hidden md:flex w-full md:w-5/12 bg-emerald-500/10 p-8 flex-col justify-center items-center text-center border-r border-zinc-200 dark:border-zinc-800">
      <div class="mb-6 text-primary">
        <v-icon size="100px">mdi-login</v-icon>
      </div>
    </v-container>

    <div class="w-full md:w-7/12 p-8 md:p-12 flex flex-col justify-center bg-surface">
      <h1 class="text-3xl font-semibold text-center text-primary mb-1">
        Log in
      </h1>
      <p class="text-xs text-zinc-500 dark:text-zinc-400 text-center font-medium mb-6">
        Welcome back! Please enter your details to continue
      </p>

      <v-form v-model="isFormValid" @submit.prevent="handleLogin">
        <v-text-field
          v-model="email"
          type="email"
          placeholder="Email"
          prepend-inner-icon="mdi-email-outline"
          variant="outlined"
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
          variant="outlined"
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
          color="primary"
          rounded="lg"
          class="text-none font-weight-bold shadow-sm"
          :loading="authStore.isLoading"
          :disabled="!isFormValid"
        >
          Let's start!
        </v-btn>
      </v-form>

      <p class="text-xs text-center text-zinc-500 dark:text-zinc-400 mt-6">
        Don't have an account? 
        <router-link to="/register" class="text-primary font-bold hover:underline">
          Sign up
        </router-link>
      </p>
    </div>
  </div>
</template>