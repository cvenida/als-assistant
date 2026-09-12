/**
 * plugins/vuetify.ts
 *
 * Framework documentation: https://vuetifyjs.com
 */

import { createVuetify } from 'vuetify'
import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/styles'

const light = {
  dark: false,
  colors: {
    background: '#FFFFFF',        
    surface: '#FFFFFF',           
    'surface-bright': '#FFFFFF',
    'surface-light': '#F8FAFC',
    'surface-variant': '#F1F5F9',
    'on-surface-variant': '#334155',
    primary: '#165c35',           
    'on-primary': '#FFFFFF',
    secondary: '#06d463',       
    'on-secondary': '#FFFFFF',
    error: '#DC2626',             
    info: '#0284C7',              
    success: '#16A34A',           
    warning: '#D97706', 
  },     
  variables: {
    'border-color': '#64748b',
    'border-opacity': 0.16,
    'high-emphasis-opacity': 0.87,
    'medium-emphasis-opacity': 0.60,
    'disabled-opacity': 0.38,
    'hover-opacity': 0.05,
    'focus-opacity': 0.12,
  }
}

const dark = {
  dark: true,
  colors: {
    background: '#181818',
    'on-background': '#F5F5F5',
    surface: '#181818',
    'on-surface': '#F5F5F5',
    'surface-light': '#2A2A2A',
    'surface-bright': '#3A3A3A',
    'surface-variant': '#2A2A2A',
    'on-surface-variant': '#A8A8A8',

    primary: '#06D463',
    'primary-darken-1': '#04A84E',
    'primary-lighten-1': '#3FDD7D',
    'on-primary': '#000000',

    secondary: '#2A2A2A',
    'secondary-darken-1': '#202020',
    'on-secondary': '#F5F5F5',

    error: '#ED4956',
    'on-error': '#FFFFFF',
    info: '#0095F6',
    'on-info': '#FFFFFF',
    success: '#22C55E',
    'on-success': '#000000',
    warning: '#F59E0B',
    'on-warning': '#000000',
  },
  variables: {
    'border-color': '#3A3A3A',
    'border-opacity': 0.7,
    'high-emphasis-opacity': 0.95,
    'medium-emphasis-opacity': 0.65,
    'disabled-opacity': 0.3,
    'detail-emphasis-opacity': 0.45,
    'hover-opacity': 0.05,
    'focus-opacity': 0.1,
    'activated-opacity': 0.1,
    'pressed-opacity': 0.15,
    'border-radius': '12px',
    'code-background': '#2A2A2A',
  },
}


export default createVuetify({
  theme: {
    defaultTheme: 'light',
    themes: {
      light,
      dark,
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