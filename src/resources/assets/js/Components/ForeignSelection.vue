<script setup lang="ts">
import { Check, ChevronsUpDown } from 'lucide-vue-next'

import { ref } from 'vue'
import { cn } from '@/lib/utils'
import { useDebounceFn } from '@vueuse/core'
import { Button } from '@/components/ui/button'
import {
  Command,
  CommandEmpty,
  CommandGroup,
  CommandInput,
  CommandItem,
  CommandList
} from '@/components/ui/command'
import {
  Popover,
  PopoverContent,
  PopoverTrigger,
} from '@/components/ui/popover'

const props = defineProps([
    'type',
    'field',
    'default',
    'column_name',
    'api_endpoint',
    'referenced_table_name',
    'referenced_column_name',
]);

const options = ref([]);
const open = ref(false)
const value = ref(props.default ? { key: props.default } : null);

const fetchData = useDebounceFn((search = '') => {
  const params = new URLSearchParams();
  params.set('search', search);
  params.set('referenced_column_name', props.referenced_column_name);
  params.set('referenced_table_name', props.referenced_table_name);

  fetch(`${props.api_endpoint}?${params.toString()}`, {
    headers: {
      Accept: 'application/json'
    }
  })
    .then(res => res.json())
    .then(data => options.value = data)
}, 200);

fetchData();

const onFilter = val => val;
</script>

<template>
  <div>
    <input type="hidden" :name="props.field" :value="value?.key"/>
    <Popover v-model:open="open">
      <PopoverTrigger as-child>
        <Button
          variant="outline"
          role="combobox"
          :aria-expanded="open"
          class="justify-between w-full"
        >
          <span>
            <span class="mr-2" v-if="value">{{ props.referenced_column_name }}: {{ value.key }}</span>
            <span v-if="value">{{ value.string }}</span>
            <span v-else>Select relation</span>
          </span>

          <ChevronsUpDown class="ml-2 h-4 w-4 shrink-0 opacity-50" />
        </Button>
      </PopoverTrigger>
      <PopoverContent class="p-0" align="start">
        <Command v-model="value" @update:searchTerm="fetchData" :filterFunction="onFilter">
          <CommandInput :placeholder="`search ${referenced_table_name}...`" />
          <CommandEmpty>Select relation</CommandEmpty>
          <CommandList>
            <CommandGroup>
              <CommandItem
                v-for="option in options"
                :key="option.key"
                :value="option"
                @select="open = false"
              >
                <Check
                  :class="cn(
                    'mr-2 h-4 w-4',
                    value && value.key == option.key ? 'opacity-100' : 'opacity-0',
                  )"
                />
                <span class="mr-2">{{ props.referenced_column_name }}: {{ option.key }}</span>
                {{ option.string }}
              </CommandItem>
            </CommandGroup>
          </CommandList>
        </Command>
      </PopoverContent>
    </Popover>
  </div>
</template>
