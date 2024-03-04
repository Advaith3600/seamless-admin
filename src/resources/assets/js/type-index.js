import { createApp } from 'vue';

import TypeIndex from './Components/TypeIndex.vue';
import commonComponents from './common-components';

createApp({
    components: {
        'type-index': TypeIndex,
        ...commonComponents,
    }
}).mount('#app')
