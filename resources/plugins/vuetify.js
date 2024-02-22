import { createVuetify } from "vuetify";
import * as components from "vuetify/components";
import * as directives from "vuetify/directives";

import "@mdi/font/css/materialdesignicons.css";

const Lighttheme = {
  variables: {},
  colors: {
    background: '#FFFFFF',
    surface: '#FFFFFF',
    primary: '#137CBD',
    'primary-darken-1': '#3700B3',
    secondary: '#607D8B',
    indigo: '#3D5AFE',
    dark_indigo: '#1A237E',
    yellow:'#FDD835',
    light: '#CFD8DC',
    red:'#F44336',
    'secondary-darken-1': '#018786',
    error: '#B00020',
    info: '#2196F3',
    success: '#4CAF50',
    warning: '#FB8C00',
    blue_grey: '#263238'
  }
};

export default createVuetify({
  components,
  directives,
  theme: {
    themes: {
      light: Lighttheme,
    },
  },
  defaults: {
    VBtn: {
      color: "primary",
      rounded: "md",
      flat: true,
      fontWeight: "400",
      letterSpacing: "0",
    },
    VCard: {
      flat: true,
      elevation: 10,
    },
  },
});