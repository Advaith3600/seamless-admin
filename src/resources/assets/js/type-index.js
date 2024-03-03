import { createApp } from 'vue';

import TypeIndex from './Components/TypeIndex.vue';
import Button from '@/components/ui/button/Button.vue';

createApp({
    components: {
        'type-index': TypeIndex,
        'sa-button': Button
    }
}).mount('#app')
