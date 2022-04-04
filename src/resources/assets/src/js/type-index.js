import { createApp } from 'vue';

createApp({
    data() {
        return {
            selected: new Set
        }
    },
    methods: {
        checkIndividual(id) {
            if (this.selected.has(id)) this.selected.delete(id);
            else this.selected.add(id);
        },
        checkAll(ids) {
            if (this.selected.size === ids.length) this.selected = new Set;
            else this.selected = new Set(ids)
        },
        massDeleteURI() {
            return encodeURI([...this.selected].map((u, i) => `ids[${i}]=${u}`).join('&'));
        },
        redirect(url) {
            location.href = url;
        },
        sort(by, order) {
            const query = {};
            decodeURIComponent(location.search)
                .slice(1)
                .split('&')
                .forEach(item => {
                    if (item === '') return;
                    const _query = item.split('=');
                    query[_query[0]] = _query[1];
                });

            if (by === query['by'] && order === 'desc') {
                delete query['by'];
                delete query['order'];
            } else {
                if (query['by'] === by) query['order'] = order === 'asc' ? 'desc' : 'asc';
                else query['order'] = 'asc';
                query['by'] = by;
            }

            const array = [];
            for (const key of Object.keys(query))
                array.push(`${key}=${query[key]}`);

            location.search = `?${array.join('&')}`;
        }
    }
}).mount('#app')
