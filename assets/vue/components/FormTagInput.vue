<template>
    <v-combobox
            v-model="select"
            :items="items"
            :search-input.sync="search"
            label="Select a favorite activity or create a new one"
            multiple
            chips
            deletable-chips
            hide-selected
            persistent-hint
            :menu-props="{'nudge-top': 145, 'nudge-left': 355}">
        <template v-slot:no-data>
            <v-list-item>
                <v-list-item-content>
                    <v-list-item-title>
                        No results matching "<strong>{{ search }}</strong>". Press <kbd>enter</kbd> to create a new one
                    </v-list-item-title>
                </v-list-item-content>
            </v-list-item>
        </template>
        <input type="text" id="post_tag" name="post[tag]" style="display: none" :value="computedSelect">
    </v-combobox>
</template>

<script>
    const ApiConfig = require("../api");
    const axios = require("axios").default;
    const $ = require("jquery");
    export default {
        name: "form-tag-input",
        beforeMount() {
            this.getTags();
        },
        data () {
            return {
                select: [],
                items: [],
                search: null
            }
        },
        computed: {
            computedSelect: function () {
                return this.select.join(",");
            }
        },
        methods: {
            async getTags() {
                const ldJSONConfig = axios.create({
                    baseURL: "http://" + document.location.host + "/api",
                    withCredentials: false,
                    headers: {
                        'Accept': 'application/ld+json',
                        'Content-Type': 'application/ld+json'
                    }
                });
                const ldTags = await axios.get(`/api/tags?page=1`, ldJSONConfig);
                if(ldTags.status === 200) {
                    const total = ldTags.data["hydra:totalItems"];
                    const itemPerPage = 30;
                    const pages = Math.ceil(total / itemPerPage);
                    for(let i = 1; i <= pages; i++) {
                        const page = await axios.get("http://" + document.location.host + `/api/tags?page=${i}`, {
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        });
                        if(page.status === 200) {
                            for(let j = 0; j < page.data.length; j++) {
                                this.items.push(page.data[j].name);
                            }
                        }
                    }
                }
            }
        }
    }
</script>

<style scoped>

</style>