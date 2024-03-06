<style>
button[data-placeholder] > span {
    @apply text-muted-foreground;
}
</style>

<script setup>
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectLabel,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select'

const props = defineProps([
    'modelValue',
    'class',
    'items',
    'required',
    'name',
    'defaultValue',
    'placeholder'
]);
</script>

<template>
  <Select
    :name="props.name"
    :required="props.required ?? false"
    :model-value="props.modelValue"
    :default-value="props.defaultValue"
    @update:model-value="$emit('update:modelValue', $event)"
  >
    <SelectTrigger :class="props.class">
      <SelectValue :placeholder="props.placeholder" />
    </SelectTrigger>
    <SelectContent>
      <SelectGroup>
        <SelectItem v-for="item in props.items" :value="item.value">{{ item.label }}</SelectItem>
      </SelectGroup>
    </SelectContent>
  </Select>
</template>
