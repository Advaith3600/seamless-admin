<template>
    <div v-if="totalPages > 1">
        <div class="flex md:justify-between items-center gap-2">
            <div class="hidden md:block">
                Showing {{ from }} to {{ to }} of {{ total }} results
            </div>

            <ul class="pagination bg-white shadow-sm rounded-lg whitespace-nowrap flex items-center md:w-auto w-full">
                <li class="pagination-item">
                    <span
                        class="flex justify-center items-center border-gray-200 px-3 py-2 cursor-not-allowed no-underline text-gray-600 h-full opacity-50"
                        v-if="isInFirstPage"
                    >
                        <vue-feather type="chevrons-left" size="18"></vue-feather>
                    </span>
                    <a
                        v-else
                        @click.prevent="onClickFirstPage"
                        class="flex justify-center items-center border-l border-gray-200 px-3 py-2 text-gray-600 hover:bg-gray-100 no-underline h-full"
                        href="#"
                        role="button"
                        rel="prev"
                    >
                        <vue-feather type="chevrons-left" size="18"></vue-feather>
                    </a>
                </li>

                <li class="pagination-item">
                    <button
                        type="button"
                        @click="onClickPreviousPage"
                        :disabled="isInFirstPage"
                        aria-label="Go to previous page"
                        class="border-l md:border-r-0 border-r border-gray-200 px-3 py-2 text-gray-600 no-underline text-sm h-full"
                        :class="isInFirstPage ? 'cursor-not-allowed opacity-50' : 'hover:bg-gray-100'"
                    >
                        Previous
                    </button>
                </li>

                <li
                    v-for="page in pages"
                    class="hidden md:inline-block"
                    :key="page.name"
                >
                    <span
                        class="flex active px-3 py-2"
                        v-if="isPageActive(page.name)"
                    >
                        {{ page.name }}
                    </span>
                    <a
                        class="flex border-l border-gray-200 px-3 py-2 hover:bg-gray-100 text-gray-600 no-underline"
                        href="#"
                        v-else
                        @click.prevent="onClickPage(page.name)"
                        role="button"
                    >
                        {{ page.name }}
                    </a>
                </li>

                <li class="md:hidden block ml-auto mr-auto">
                    <span class="flex text-gray-600 no-underline text-sm">Page {{ currentPage }}</span>
                </li>

                <li class="pagination-item">
                    <button
                        type="button"
                        @click="onClickNextPage"
                        :disabled="isInLastPage"
                        aria-label="Go to next page"
                        class="border-l border-gray-200 px-3 py-2 text-gray-600 no-underline text-sm h-full"
                        :class="isInLastPage ? 'cursor-not-allowed opacity-50' : 'hover:bg-gray-100'"
                    >
                        Next
                    </button>
                </li>

                <li class="pagination-item">
                    <a
                        class="flex justify-center items-center border-l border-gray-200 px-3 py-2 hover:bg-gray-100 text-gray-600 no-underline h-full"
                        href="#"
                        @click.prevent="onClickLastPage"
                        rel="next"
                        role="button"
                        v-if="hasMorePages"
                    >
                        <vue-feather type="chevrons-right" size="18"></vue-feather>
                    </a>
                    <span
                        class="flex justify-center items-center border-l border-gray-200 px-3 py-2 text-gray-600 no-underline cursor-not-allowed h-full opacity-50"
                        v-else
                    >
                        <vue-feather type="chevrons-right" size="18"></vue-feather>
                    </span>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
import VueFeather from "./VueFeather.vue";

export default {
    components: { VueFeather },
    props: {
        maxVisibleButtons: {
            type: Number,
            required: false,
            default: 4
        },
        totalPages: {
            type: Number,
            required: true
        },
        total: {
            type: Number,
            required: true
        },
        perPage: {
            type: Number,
            required: true
        },
        currentPage: {
            type: Number,
            required: true
        },
        hasMorePages: {
            type: Boolean,
            required: true
        },
        from: {
            type: Number,
            required: true
        },
        to: {
            type: Number,
            required: true
        }
    },
    computed: {
        startPage() {
            if (this.currentPage === 1)
                return 1;

            if (this.currentPage === this.totalPages)
                return Math.max(1, this.totalPages - this.maxVisibleButtons + 1);

            return this.currentPage - 1;
        },
        endPage() {
            return Math.min(
                this.startPage + this.maxVisibleButtons - 1,
                this.totalPages
            );
        },
        pages() {
            const range = [];

            for (let i = this.startPage; i <= this.endPage; i += 1) {
                range.push({
                    name: i,
                    isDisabled: i === this.currentPage
                });
            }

            return range;
        },
        isInFirstPage() {
            return this.currentPage === 1;
        },
        isInLastPage() {
            return this.currentPage === this.totalPages;
        }
    },
    methods: {
        onClickFirstPage() {
            this.$emit("pagechanged", 1);
        },
        onClickPreviousPage() {
            this.$emit("pagechanged", this.currentPage - 1);
        },
        onClickPage(page) {
            this.$emit("pagechanged", page);
        },
        onClickNextPage() {
            this.$emit("pagechanged", this.currentPage + 1);
        },
        onClickLastPage() {
            this.$emit("pagechanged", this.totalPages);
        },
        isPageActive(page) {
            return this.currentPage === page;
        }
    }
}
</script>
