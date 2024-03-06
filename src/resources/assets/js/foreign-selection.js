import { createApp } from 'vue';

import commonComponents from './common-components';
import ForeignSelection from './Components/ForeignSelection.vue';

createApp({
    components: {
        'foreign-selection': ForeignSelection,
        ...commonComponents,
    }
}).mount('#app')

