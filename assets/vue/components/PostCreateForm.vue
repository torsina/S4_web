<template>
    <v-app class="app-fix">
        <v-form v-model="valid">
            <v-card>
                <v-card-title>
                    Create a post
                </v-card-title>
                <v-card-text>
                    <v-row>
                        <v-text-field v-model="formData.title" :rules="rules.title" label="Title" required filled>
                        </v-text-field>
                    </v-row>
                    <v-row>
                        <v-textarea v-model="formData.description" label="Description" required filled>
                        </v-textarea>
                    </v-row>
                    <v-row>
                        <v-textarea v-model="formData.content" label="Content" counter required filled>
                        </v-textarea>
                    </v-row>
                    <v-row>
                        <form-tag-input ref="tagInput"></form-tag-input>
                    </v-row>
                    <v-row>
                        <v-file-input v-model="formData.image" label="Thumbnail" required filled></v-file-input>
                    </v-row>
                    <v-row>
                        <v-btn @click="submit">Submit</v-btn>
                    </v-row>
                </v-card-text>
            </v-card>
        </v-form>
    </v-app>
</template>

<script>
    import FormTagInput from "./FormTagInput";
    const axios = require("axios").default;
    export default {
        components: {FormTagInput},
        computed: {
            computedFormData() {
                const formData = new FormData();
                formData.append('title', this.formData.title);
                formData.append('description', this.formData.description);
                formData.append('content', this.formData.content);
                if(this.$refs.tagInput) {
                    formData.append('tags', this.$refs.tagInput.select);
                }
                if(this.formData.image) {
                    formData.append('image', this.formData.image, this.formData.image.name);
                }
                return formData;
            }
        },
        data: () => ({
            valid: false,
            formData: {
                title: '',
                description: '',
                content: '',
                tags: '',
                image: null
            },
            rules: {
                title: [
                    v => !!v || 'Title is required',
                    v => v.length > 10 || 'Name must be more than 10 characters',
                ]
            },
        }),
        methods: {
            submit: async function () {
                const upload = await axios.post(document.location.href, this.computedFormData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });
                if(upload.status === 200) {
                    const id = upload.data.id;
                    document.location.href = document.location.origin + "/post/" + id;
                }
            }
        }
    }
</script>


<style scoped>

</style>