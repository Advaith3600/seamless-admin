<script setup lang="ts">
import {
  FlexRender,
  useVueTable,
  getCoreRowModel,
} from '@tanstack/vue-table'
import { ArrowUpDown, ChevronDown } from 'lucide-vue-next'

import { h, ref } from 'vue'
import { Button } from '@/components/ui/button'
import { Checkbox } from '@/components/ui/checkbox'
import {
  Pagination,
  PaginationEllipsis,
  PaginationFirst,
  PaginationLast,
  PaginationList,
  PaginationListItem,
  PaginationNext,
  PaginationPrev,
} from '@/components/ui/pagination';
import {
  DropdownMenu,
  DropdownMenuCheckboxItem,
  DropdownMenuContent,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'
import { Input } from '@/components/ui/input'
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table'
import { valueUpdater } from '@/lib/utils'

const props = defineProps([
  'dataFetchUrl',
  'canEdit',
  'canDelete',
  'keyName',
  'fillable',
  'routes'
]);

const ucfirst = (value) => value.charAt(0).toUpperCase() + value.slice(1);

const columns = [
  {
    id: 'select',
    header: ({ table }) => h(Checkbox, {
      'checked': table.getIsAllPageRowsSelected(),
      'onUpdate:checked': value => table.toggleAllPageRowsSelected(!!value),
      'ariaLabel': 'Select all',
    }),
    cell: ({ row }) => h(
      'div',
      { class: 'flex items-center' },
      h(Checkbox, {
        'checked': row.getIsSelected(),
        'onUpdate:checked': value => row.toggleSelected(!!value),
        'ariaLabel': 'Select row',
      }
    )),
    enableSorting: false,
    enableHiding: false,
  },
]

props.fillable.forEach((column) => {
  columns.push({
    id: column,
    header: ({ table }) => h('div', {}, ucfirst(column)),
    cell: ({ row }) => h('div', {}, row.original[column]),
  })
})

const data = ref([]);
const meta = ref({});
const filters = ref({
  page: 1,
  search: '',
});

const params = new URLSearchParams();
let loading = false;
const fetchData = async () => {
  if (loading) return;
  loading = true;

  for (const [key, value] of Object.entries(filters.value)) {
    params.set(key, value);
  }

  const res = await fetch(props.dataFetchUrl + '?' + params.toString(), {
    headers: {
      Accept: 'application/json'
    }
  })
  const json = await res.json();

  loading = false;
  meta.value = json;
  data.value = json.data;
}

fetchData();

const table = useVueTable({
  get data() { return data.value },
  getCoreRowModel: getCoreRowModel(),
  columns,
  manualFiltering: true,
  onGlobalFilterChange: (search) => {
    filters.value.search = search;
    fetchData();
  },
})

const updatePage = (i) => {
  filters.value.page = i;
  fetchData();
}

const isMobile = window.innerWidth <= 768;
</script>

<template>
  <div class="w-full">
    <div class="flex gap-2 items-center py-4">
      <Input
        class="max-w-sm"
        :placeholder="'Search in ' + props.fillable.join(', ')"
        :model-value="filters.search"
        @update:model-value="table.setGlobalFilter($event)"
      />
      <DropdownMenu>
        <DropdownMenuTrigger as-child>
          <Button variant="outline" class="ml-auto">
            Columns <ChevronDown class="ml-2 h-4 w-4" />
          </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end">
          <DropdownMenuCheckboxItem
            v-for="column in table.getAllColumns().filter((column) => column.getCanHide())"
            :key="column.id"
            class="capitalize"
            :checked="column.getIsVisible()"
            @update:checked="(value) => {
              column.toggleVisibility(!!value)
            }"
          >
            {{ column.id }}
          </DropdownMenuCheckboxItem>
        </DropdownMenuContent>
      </DropdownMenu>
    </div>
    <div class="rounded-md border bg-white">
      <Table>
        <TableHeader>
          <TableRow v-for="headerGroup in table.getHeaderGroups()" :key="headerGroup.id">
            <TableHead v-for="header in headerGroup.headers" :key="header.id">
              <FlexRender v-if="!header.isPlaceholder" :render="header.column.columnDef.header" :props="header.getContext()" />
            </TableHead>
          </TableRow>
        </TableHeader>
        <TableBody>
          <template v-if="table.getRowModel().rows?.length">
            <TableRow
              v-for="row in table.getRowModel().rows"
              :key="row.id"
              :data-state="row.getIsSelected() && 'selected'"
            >
              <TableCell v-for="cell in row.getVisibleCells()" :key="cell.id">
                <FlexRender :render="cell.column.columnDef.cell" :props="cell.getContext()" />
              </TableCell>
            </TableRow>
          </template>

          <TableRow v-else>
            <TableCell
              col-span="{columns.length}"
              class="h-24 text-center"
            >
              No results.
            </TableCell>
          </TableRow>
        </TableBody>
      </Table>
    </div>

    <div class="flex items-center justify-end gap-2 py-4 sm:flex-row flex-col">
      <div class="flex-1 text-sm text-muted-foreground">
        Showing {{ meta.from }} to {{ meta.to }} of {{ meta.total }} results
      </div>
      <div>
        <Pagination
          v-slot="{ page }"
          :total="meta.total"
          :show-edges="!isMobile"
          :page="meta.current_page"
          :items-per-page="meta.per_page"
          :siblingCount="isMobile ? 0 : 1"
          @update:page="updatePage"
        >
          <PaginationList v-slot="{ items }" class="flex items-center gap-1">
            <PaginationFirst />
            <PaginationPrev />

            <template v-for="(item, index) in items">
              <PaginationListItem v-if="item.type === 'page'" :key="index" :value="item.value" as-child>
                <Button class="w-10 h-10 p-0" :variant="item.value === page ? 'default' : 'outline'">
                  {{ item.value }}
                </Button>
              </PaginationListItem>
              <PaginationEllipsis v-else :key="item.type" :index="index" />
            </template>

            <PaginationNext />
            <PaginationLast />
          </PaginationList>
        </Pagination>
      </div>
    </div>
  </div>
</template>
