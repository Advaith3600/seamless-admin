<script setup>
import { ChevronsUpDown, ChevronUp, ChevronDown } from 'lucide-vue-next';

const props = defineProps([ 'filters', 'column' ]);
</script>

<template>
  <ChevronDown v-if="props.filters.by == props.column && props.filters.order === 'desc'" class="h-4 w-4" />
  <ChevronUp v-else-if="props.filters.by == props.column && props.filters.order === 'asc'" class="h-4 w-4" />
  <ChevronsUpDown v-else class="h-4 w-4" />
</template>
