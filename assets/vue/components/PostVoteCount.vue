<template>
    <span>Vote count: {{ voteCount }}</span>
</template>

<script>
    const ApiConfig = require("../api");
    const axios = require("axios").default;
    export default {
        name: "post-vote-count",
        async beforeMount() {
            await this.setVoteCount();
        },
        data() {
            return {
                voteCount: null
            }
        },
        methods: {
            async setVoteCount() {
                if(this.post) {
                    const votes = await axios.get(`/api/upvote/count/${this.post}`, ApiConfig);
                    if(votes.status === 200) {
                        this.voteCount = votes.data.upvote - votes.data.downvote
                    }
                }
            }
        },
        props: {
            post: Number
        }
    }
</script>

<style scoped>

</style>