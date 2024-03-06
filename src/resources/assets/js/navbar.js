import { createApp } from 'vue';

import ThemeSwitcher from './Components/ThemeSwitcher.vue';

createApp({
    components: {
        'theme-switcher': ThemeSwitcher,
    }
}).mount('#navbar')

