import { createApp } from 'vue';

document.querySelectorAll('[data-link]').forEach(elem => {
    elem.addEventListener('click', event => {
        event.preventDefault();
        location.href = elem.dataset.link;
    });
});

document.querySelectorAll('[data-link] a').forEach(elem => {
    elem.addEventListener('click', event => {
        event.stopPropagation();
    });
});

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
        }
    }
}).mount('#app')
