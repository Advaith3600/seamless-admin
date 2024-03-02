<template>
    <div>
        <div class="flex justify-between items-center mt-4 mb-2">
            <form class="flex gap-2" @submit.prevent="onSearch">
                <div class="search">
                    <input
                        type="text"
                        :placeholder="'Search in ' + fillable.join(', ')"
                        class="input"
                        v-model="search"
                    />
                    <div class="icon">
                        <vue-feather type="search"></vue-feather>
                    </div>
                </div>

                <select
                    name="perPage"
                    id="perPage"
                    class="input"
                    v-model="perPage"
                    @change="onSearch"
                >
                    <option v-for="page in [5, 10, 20, 50, 100]" :value="page">{{ page }}</option>
                </select>
            </form>

            <a
                v-if="canDelete && selected.size > 0"
                :href="routes.delete + '?' + massDeleteURI()"
                class="btn red"
            >
                <vue-feather type="trash-2"></vue-feather>
                Delete
            </a>
        </div>

        <div class="overflow-auto">
            <table class="table">
                <thead>
                <tr>
                    <th>
                        <input
                            type="checkbox"
                            :checked="selected.size === data.length"
                            @click="checkAll"
                        />
                    </th>

                    <th v-for="item in fillable">
                        <div
                            class="flex justify-between items-center sort gap-2"
                            :class="item === by && (order === 'asc' ? 'up' : 'down')"
                            @click="sort(item)"
                        >
                            <div v-text="ucfirst(item)"></div>
                            <div class="flex flex-col">
                                <div class="up flex" :class.light="![keyName, item].includes(by)">
                                    <vue-feather type="chevron-up" strokeWidth="3"></vue-feather>
                                </div>
                                <div class="down flex" :class.light="![keyName, item].includes(by)">
                                    <vue-feather type="chevron-down" strokeWidth="3"></vue-feather>
                                </div>
                            </div>
                        </div>
                    </th>

                    <th></th>
                </tr>
                </thead>

                <tbody>
                <tr
                    v-for="row in data"
                    :class="{ selected: selected.has(row[keyName]) }"
                    @click="redirect(routes.show.replace('%key%', row[keyName]))"
                >
                    <td>
                        <input
                            type="checkbox"
                            :checked="selected.has(row[keyName])"
                            @change="checkIndividual(row[keyName])"
                            @click.stop
                        />
                    </td>
                    <td v-for="f in fillable">{{ row[f] }}</td>
                    <td>
                        <div class="flex gap-3">
                            <a
                                v-if="canEdit"
                                :href="routes.edit.replace('%key%', row[keyName])"
                                class="btn yellow small link"
                                @click.stop
                            >
                                <vue-feather type="edit"></vue-feather>
                                Edit
                            </a>
                            <a
                                v-if="canDelete"
                                :href="deleteURL(row[keyName])"
                                class="btn red small link"
                                @click.stop
                            >
                                <vue-feather type="trash-2"></vue-feather>
                                Delete
                            </a>
                        </div>
                    </td>
                </tr>
                <tr v-if="data.length === 0">
                    <td :colspan="fillable.length + 2" class="text-center">No data found</td>
                </tr>
                <tr v-if="loading" class="loader">
                    <vue-feather type="loader" animation="spin" stroke="white" size="38"></vue-feather>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-4" v-if="Object.keys(meta).length">
            <pagination
                :total-pages="meta.last_page"
                :total="meta.total"
                :per-page="meta.per_page"
                :current-page="meta.current_page"
                :has-more-pages="meta.current_page < meta.last_page"
                :from="meta.from"
                :to="meta.to"
                @pagechanged="changePage($event)"
            ></pagination>
        </div>
    </div>
</template>

<script>
import VueFeather from './VueFeather.vue';
import Pagination from './Pagination.vue';

export default {
    components: { VueFeather, Pagination },
    props: [
        'dataFetchUrl',
        'canEdit',
        'canDelete',
        'keyName',
        'fillable',
        'routes'
    ],
    data() {
        return {
            selected: new Set,

            page: 1,
            search: '',
            perPage: 10,
            by: this.keyName,
            order: 'desc',

            loading: true,
            data: [],
            meta: {}
        }
    },
    methods: {
        ucfirst(value) {
            return value.charAt(0).toUpperCase() + value.slice(1);
        },
        checkIndividual(id) {
            if (this.selected.has(id)) this.selected.delete(id);
            else this.selected.add(id);
        },
        checkAll() {
            if (this.selected.size === this.data.length) this.selected = new Set;
            else this.selected = new Set(this.data.map(u => u[this.keyName]))
        },
        massDeleteURI() {
            return encodeURI([...this.selected].map((u, i) => `ids[${i}]=${u}`).join('&'));
        },
        deleteURL(key) {
            return encodeURI(`${this.routes.delete}?ids[0]=${key}`);
        },
        redirect(url) {
            location.href = url;
        },
        encodeQueryString() {
            const url = new URL(window.location);
            ['page', 'search', 'perPage', 'by', 'order'].forEach(item =>
                url.searchParams.set(item, this[item]))
            return url;
        },
        sort(by) {
            if (by === this.by && this.order === 'desc') {
                this.by = this.keyName;
                this.order = 'desc';
            } else {
                if (this.by === by) this.order = 'desc';
                else this.order = 'asc';
                this.by = by;
            }

            this.updateState();
        },
        changePage(page) {
            this.page = page;
            this.updateState();
        },
        onSearch() {
            this.page = 1;
            this.updateState();
        },
        updateState() {
            window.history.pushState({}, '', this.encodeQueryString());
            this.fetchData();
        },
        fetchData() {
            const apiConfig = {
                headers: {
                    Accept: 'application/json'
                }
            };

            this.loading = true;
            fetch(this.dataFetchUrl + this.encodeQueryString().search, apiConfig)
                .then(res => res.json())
                .then(response => {
                    this.meta = response;
                    this.data = response.data;
                    this.loading = false;
                })
        }
    },
    created() {
        const url = new URL(window.location);
        ['search', 'perPage', 'order', 'by', 'page'].forEach(item => {
            if (url.searchParams.has(item))
                this[item] = url.searchParams.get(item);
        });

        this.fetchData();
    },
}
</script>
