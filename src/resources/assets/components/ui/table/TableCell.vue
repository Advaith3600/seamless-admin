<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '/src/resources/assets/lib/utils'

const props = defineProps<{
  class?: HTMLAttributes['class']
}>()
</script>

<template>
  <td
    :class="
      cn(
        'px-4 py-2 align-middle [&:has([role=checkbox])]:pr-0',
        props.class,
      )
    "
  >
    <slot />
  </td>
</template>
