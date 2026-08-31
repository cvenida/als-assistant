/**
 * plugins/vuetify.ts
 *
 * Framework documentation: https://vuetifyjs.com
 */

import { createVuetify } from 'vuetify'
import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/styles'

const alsAssistantTheme = {
  dark: false,
  colors: {
    background: '#F8FAFC',        
    surface: '#FFFFFF',           
    'surface-bright': '#FFFFFF',
    'surface-light': '#F1F5F9',
    'surface-variant': '#E2E8F0',
    'on-surface-variant': '#334155',
    primary: '#009241',           
    'on-primary': '#FFFFFF',
    secondary: '#06d463',         
    'on-secondary': '#FFFFFF',
    error: '#DC2626',             
    info: '#0284C7',              
    success: '#16A34A',           
    warning: '#D97706', 
  },     
  variables: {
      'border-color': '#CBD5E1',
      'border-opacity': 0.16,
      'high-emphasis-opacity': 0.87,
      'medium-emphasis-opacity': 0.60,
      'disabled-opacity': 0.38,
      'hover-opacity': 0.05,
      'focus-opacity': 0.12,
  }
}

export default createVuetify({
  theme: {
    defaultTheme: 'alsAssistantTheme',
    themes: {
      alsAssistantTheme,
    },
  },
  display: {
    mobileBreakpoint: 'md',
    thresholds: {
      xs: 0,
      sm: 600,
      md: 840,
      lg: 1145,
      xl: 1545,
      xxl: 2138,
    },
  },
})
