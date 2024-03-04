<script setup>
import { MoreHorizontal, Trash2Icon, EditIcon } from 'lucide-vue-next'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'
import { Button } from '@/components/ui/button'

const props = defineProps([ 'canEdit', 'canDelete', 'routes', 'keyVal' ]);

const redirectEdit = () => window.location.href = props.routes.edit.replace('%key%', props.keyVal);
const redirectDelete = () => {
  const params = new URLSearchParams();
  params.set('ids[0]', props.keyVal);
  window.location.href = props.routes.delete + '?' + params.toString();
}
</script>

<template>
  <div @click.stop>
    <DropdownMenu>
      <DropdownMenuTrigger as-child>
        <Button variant="ghost" class="w-8 h-8 p-0">
          <span class="sr-only">Open menu</span>
          <MoreHorizontal class="w-4 h-4" />
        </Button>
      </DropdownMenuTrigger>
      <DropdownMenuContent align="end">
        <DropdownMenuItem as-child>
          <Button variant="ghost" class="cursor-pointer w-full h-auto justify-start" @click="redirectEdit">
            <EditIcon :size="16" />
            Edit entry
          </Button>
        </DropdownMenuItem>

        <DropdownMenuItem as-child>
          <Button
            @click="redirectDelete"
            variant="ghost"
            class="text-destructive hover:text-destructive/90 cursor-pointer w-full h-auto justify-start"
          >
            <Trash2Icon :size="16" />
            Delete entry
          </Button>
        </DropdownMenuItem>
      </DropdownMenuContent>
    </DropdownMenu>
  </div>
</template>
