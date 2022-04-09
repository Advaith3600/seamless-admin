<style>
.fade-enter-active, .fade-leave-active {
    transition: opacity .2s;
}

.fade-enter, .fade-leave-to {
    opacity: 0;
}
</style>

<template>
    <div class="relative" @click.stop>
        <input type="hidden" :name="field" :value="value"/>

        <div class="input flex justify-between items-center cursor-default" @click="openDropdown">
            <div>{{ value || 'Selection' }}</div>

            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"
                 class="feather feather-chevron-down down">
                <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
        </div>

        <transition name="fade">
            <div v-if="dropdown" class="absolute top-full left-0 w-full bg-white mt-1 rounded shadow">
                <form class="p-1 search" @submit.prevent="fetchData">
                    <input
                        ref="search_elem"
                        type="text"
                        class="input w-full"
                        v-model="search"
                        :placeholder="`search ${referenced_table_name}...`"
                    />

                    <div class="icon" @click="fetchData">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-search">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </div>
                </form>

                <div class="overflow-auto max-h-48">
                    <div
                        class="p-2 hover:bg-gray-200 transition cursor-pointer"
                        v-for="option in options.values"
                        @click="selectOption(option)"
                    >
                        <span class="mr-2">{{ props.referenced_column_name.toUpperCase() }}: {{ option.key }}</span>
                        <span>{{ option.string }}</span>
                    </div>

                    <div class="p-2 text-center" v-if="options.values.length === 0">
                        No results found.
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script setup>
import { ref, reactive, onUnmounted } from 'vue';

const props = defineProps([
    'type',
    'field',
    'default',
    'column_name',
    'api_endpoint',
    'referenced_table_name',
    'referenced_column_name',
]);

const dropdown = ref(false),
    value = ref(props.default),
    search = ref(''),
    search_elem = ref(null),
    options = reactive([]);

const apiConfig = {
    credentials: 'same-origin',
    headers: {
        Accept: 'application/json'
    }
}

const queryBuilder = query => {
    const array = [];
    for (const key of Object.keys(query))
        array.push(`${key}=${query[key]}`);
    return encodeURI(array.join('&'));
}

const fetchData = () => {
    const query = {
        search: search.value,
        referenced_table_name: props.referenced_table_name,
        referenced_column_name: props.referenced_column_name
    }

    fetch(`${props.api_endpoint}?${queryBuilder(query)}`, apiConfig)
        .then(res => res.json())
        .then(data => options.values = data)
}

const openDropdown = () => {
    dropdown.value = true;
    setTimeout(() => search_elem.value.focus());
}

fetchData();

const selectOption = option => {
    value.value = option.key;
    search.value = '';
    dropdown.value = false;
}

const onBodyClick = () => {
    dropdown.value = false
    search.value = '';
};
document.addEventListener('click', onBodyClick);
onUnmounted(() => document.removeEventListener('click', onBodyClick));
</script>
