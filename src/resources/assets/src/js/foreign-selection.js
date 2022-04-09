import { createApp } from 'vue';

import ForeignSelection from './Components/ForeignSelection';

const app = createApp({});

// component registration
app.component('foreign-selection', ForeignSelection);

app.mount('#app');

