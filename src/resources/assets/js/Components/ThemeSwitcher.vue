<script setup>
import {
  DropdownMenu,
  DropdownMenuItem,
  DropdownMenuCheckboxItem,
  DropdownMenuContent,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'
import Button from '@/components/ui/button/Button.vue'
import { Sun, Moon } from 'lucide-vue-next'

const onChange = (to) => {
  if (to === 'dark') document.documentElement.classList.add('dark');
  else if (to === 'system') {
    if (window.matchMedia('(prefers-color-scheme: dark)').matches)
      document.documentElement.classList.add('dark')
     else document.documentElement.classList.remove('dark')
  }
  else document.documentElement.classList.remove('dark')

  localStorage.setItem('theme', to)
}
</script>

<template>
  <DropdownMenu>
    <DropdownMenuTrigger asChild>
      <Button variant="outline" size="icon" class="rounded-full">
        <Sun class="h-[1.2rem] w-[1.2rem] rotate-0 scale-100 transition-all dark:-rotate-90 dark:scale-0" />
        <Moon class="absolute h-[1.2rem] w-[1.2rem] rotate-90 scale-0 transition-all dark:rotate-0 dark:scale-100" />
        <span class="sr-only">Toggle theme</span>
      </Button>
    </DropdownMenuTrigger>
    <DropdownMenuContent align="end">
      <DropdownMenuItem @click="onChange('light')">
        Light
      </DropdownMenuItem>
      <DropdownMenuItem @click="onChange('dark')">
        Dark
      </DropdownMenuItem>
      <DropdownMenuItem @click="onChange('system')">
        System
      </DropdownMenuItem>
    </DropdownMenuContent>
  </DropdownMenu>
</template>
