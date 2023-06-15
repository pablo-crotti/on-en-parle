<template>
    <div id="dropdown-filter">
        <label for="filter">Trier par</label>
        <select v-model="filterBy" id="filter" @change="handleFilterChange">
            <option value="" disabled selected>Choisir</option>
            <option v-for="category in categories" :value="category.value">
                {{ category.name }}
            </option>
        </select>
    </div>
</template>

<script>
export default {
    components: {},
    data() {
        return {
            filterBy: "",
        };
    },
    props: {
        categories: {
            type: Array,
            required: true,
        },
    },

    methods: {
        handleFilterChange() {
            console.log(this.categories);
            this.$emit("filter-applied", this.filterBy);
        },
    },
};
</script>
