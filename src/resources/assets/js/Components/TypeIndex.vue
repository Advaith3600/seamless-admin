<script setup lang="ts">
import {
  FlexRender,
  useVueTable,
  getCoreRowModel,
} from '@tanstack/vue-table'
import { ChevronDown, SearchIcon, Loader2, Trash2Icon } from 'lucide-vue-next'
import TableSortingIcon from './TableSortingIcon.vue'
import TableActionDropdown from './TableActionDropdown.vue'

import { useDebounceFn } from '@vueuse/core'
import { h, ref } from 'vue'
import { Button } from '@/components/ui/button'
import { Checkbox } from '@/components/ui/checkbox'
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectLabel,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select'
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

const props = defineProps([
  'dataFetchUrl',
  'canEdit',
  'canDelete',
  'keyName',
  'fillable',
  'routes'
]);

const redirect = (id) => window.location.href = props.routes.show.replace('%key%', id)

const columns = [];

if (props.canDelete) {
  columns.push({
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
        'on:click': (e) => e.stopPropagation(),
      }
    )),
    enableSorting: false,
    enableHiding: false,
  });
}

const loading = ref(true);
const data = ref([]);
const meta = ref({});
const filters = ref({
  page: 1,
  search: '',
  perPage: '10',
  order: 'desc',
  by: props.keyName
});

props.fillable.forEach((column) => {
  columns.push({
    id: column,
    header: ({ column }) => {
      return h(
        Button,
        {
          variant: 'ghost',
          class: 'h-auto p-0 hover:bg-transparent',
          onClick: () =>{
            if (column.getNextSortingOrder()) column.toggleSorting();
            else column.clearSorting();
          },
        },
        [ column.id, h(TableSortingIcon, { filters: filters.value, column: column.id }) ]
      );
    },
    cell: ({ row }) => h('div', {}, row.original[column]),
  })
})

if (props.canDelete || props.canEdit) {
  columns.push({
    id: 'actions',
    enableHiding: false,
    cell: ({ row }) => {
      return h('div', { class: 'relative' }, h(TableActionDropdown, {
        canEdit: props.canEdit,
        canDelete: props.canDelete,
        keyVal: row.original[props.keyName],
        routes: props.routes
      }))
    },
  })
}

const fetchData = useDebounceFn(async (initial = false) => {
  if (!initial && loading.value) return;
  loading.value = true;

  const params = new URLSearchParams();
  for (const [key, value] of Object.entries(filters.value))
    params.set(key, value);

  const newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?' + params.toString();
  window.history.pushState({ path: newurl }, '', newurl);

  try {
    const res = await fetch(props.dataFetchUrl + '?' + params.toString(), {
      headers: {
        Accept: 'application/json'
      }
    })
    const json = await res.json();
    meta.value = json;
    data.value = json.data;
  } catch (e) { }

  loading.value = false;
}, 200);

const url = new URL(window.location);
url.searchParams.forEach((value, key) => filters.value[key] = value);
fetchData(true);

const table = useVueTable({
  get data() { return data.value },
  getCoreRowModel: getCoreRowModel(),
  columns,
  manualPagination: true,
  manualSorting: true,
  manualFiltering: true,
  onGlobalFilterChange: (search) => {
    filters.value.search = search;
    filters.value.page = 1;
    table.resetRowSelection();
    fetchData();
  },
  onSortingChange: (item) => {
    const value = item();
    if (value.length) {
      filters.value.by = value[0].id;
      filters.value.order = value[0].desc ? 'desc' : 'asc';
    } else {
      filters.value.by = props.keyName;
      filters.value.order = 'desc';
    }
    table.resetRowSelection();
    fetchData();
  },
  state: {
    get sorting() { return [{ id: filters.value.by, desc: filters.value.order === 'desc' }] }
  }
})

const updatePage = (i) => {
  filters.value.page = i;
  table.resetRowSelection();
  fetchData();
}

const updatePerPage = (i) => {
  filters.value.page = 1;
  filters.value.perPage = i;
  table.resetRowSelection();
  fetchData();
}

const deleteSelected = () => {
  const params = new URLSearchParams();
  table
    .getSelectedRowModel()
    .rows
    .map(row => row.original[props.keyName])
    .forEach((id, i) => params.set(`ids[${i}]`, id));

  window.location.href = props.routes.delete + '?' + params.toString();
}

const isMobile = ref(window.innerWidth <= 768);
window.addEventListener('resize', () => isMobile.value = window.innerWidth <= 768);
</script>

<template>
  <div class="w-full">
    <div
      class="fixed top-0 inset-x-0 bg-white shadow-lg flex items-center justify-end p-4 z-20"
      v-if="table.getIsSomeRowsSelected() || table.getIsAllPageRowsSelected()"
    >
      <Button variant="destructive" @click="deleteSelected" size="sm">
        <Trash2Icon />
        Delete selected
      </Button>
    </div>

    <div class="flex gap-2 items-center py-4">
      <div class="flex gap-2">
        <div class="relative w-full max-w-sm items-center">
          <Input
            class="max-w-sm pl-10"
            :placeholder="'Search in ' + props.fillable.join(', ')"
            :model-value="filters.search"
            @update:model-value="table.setGlobalFilter($event)"
          />
          <span class="absolute start-0 inset-y-0 flex items-center justify-center px-3">
            <SearchIcon class="text-muted-foreground" :size="18" />
          </span>
        </div>

        <Select :model-value="filters.perPage" @update:model-value="updatePerPage">
          <SelectTrigger class="w-24">
            <SelectValue />
          </SelectTrigger>
          <SelectContent>
            <SelectGroup>
              <SelectItem v-for="page in [5, 10, 20, 50, 100]" :value="page.toString()">{{ page }}</SelectItem>
            </SelectGroup>
          </SelectContent>
        </Select>
      </div>
      <DropdownMenu>
        <DropdownMenuTrigger as-child>
          <Button variant="outline" class="ml-auto">
            Columns <ChevronDown class="h-4 w-4" />
          </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end">
          <DropdownMenuCheckboxItem
            v-for="column in table.getAllColumns().filter((column) => column.getCanHide())"
            :key="column.id"
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
    <div class="rounded-md border bg-white relative overflow-hidden">
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
              :key="row.original[props.keyName]"
              :data-state="row.getIsSelected() && 'selected'"
              class="cursor-pointer"
              @click="redirect(row.original[props.keyName])"
            >
              <TableCell v-for="cell in row.getVisibleCells()" :key="cell.id">
                <FlexRender :render="cell.column.columnDef.cell" :props="cell.getContext()" />
              </TableCell>
            </TableRow>
          </template>

          <TableRow v-else>
            <TableCell
              :colSpan="columns.length"
              class="h-24 text-center"
            >
              No results.
            </TableCell>
          </TableRow>
        </TableBody>
      </Table>

      <div v-if="loading" class="absolute inset-0 flex items-center justify-center bg-transparent backdrop-blur">
        <Loader2 class="animate-spin" :size="36"/>
      </div>
    </div>

    <div class="flex items-center justify-end gap-2 py-4 sm:flex-row flex-col flex-wrap">
      <div class="flex-1 text-sm text-muted-foreground">
        Showing {{ meta.from }} to {{ meta.to }} of {{ meta.total }} results
      </div>
      <div class="max-w-full">
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
