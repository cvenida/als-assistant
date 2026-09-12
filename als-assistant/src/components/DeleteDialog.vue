<script setup>
import { AlertTriangle } from 'lucide-vue-next'

const props = defineProps({
  modelValue: {
    type: Boolean,
    required: true
  },
  title: {
    type: String,
    default: 'Confirm Delete'
  },
  itemName: {
    type: String,
    default: ''
  },
  message: {
    type: String,
    default: ''
  },
  isLoading: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['update:modelValue', 'confirm'])

const handleClose = () => {
  if (!props.isLoading) {
    emit('update:modelValue', false)
  }
}

const handleConfirm = () => {
  emit('confirm')
}
</script>

<template>
  <v-dialog
    :model-value="modelValue"
    @update:model-value="emit('update:modelValue', $event)"
    max-width="440px"
    persistent
  >
    <v-card class="rounded-2xl p-4 bg-surface border border-zinc-200 dark:border-zinc-800">
      <div class="flex items-start gap-4 p-2">
        <div class="size-10 rounded-full bg-rose-500/10 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0">
          <AlertTriangle class="size-5" />
        </div>

        <div>
          <h3 class="text-base font-bold text-zinc-900 dark:text-zinc-100">{{ title }}</h3>
          
          <p class="text-xs text-zinc-600 dark:text-zinc-400 font-medium mt-1 leading-relaxed">
            <template v-if="message">
              {{ message }}
            </template>
            <template v-else-if="itemName">
              Are you sure you want to delete <strong class="text-zinc-900 dark:text-zinc-100 font-bold">"{{ itemName }}"</strong>? This action cannot be undone.
            </template>
            <template v-else>
              Are you sure you want to delete this item? This action cannot be undone.
            </template>
          </p>
        </div>
      </div>

      <div class="flex justify-end gap-2 mt-6">
        <v-btn
          variant="outlined"
          rounded="lg"
          class="capitalize font-semibold border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-300"
          :disabled="isLoading"
          @click="handleClose"
        >
          Cancel
        </v-btn>
        <v-btn
          color="error"
          variant="elevated"
          rounded="lg"
          class="capitalize font-semibold text-white"
          :loading="isLoading"
          @click="handleConfirm"
        >
          Delete
        </v-btn>
      </div>
    </v-card>
  </v-dialog>
</template>