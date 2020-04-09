<template>
    <v-app class="app-fix">
        <v-row>
            <v-btn
                   :color="(voteEnabled && (isLoadingUp || voteValue === 1)) ? 'red' : ''"
                   :loading="isLoadingUp"
                   class="mx-2"
                   :class="enabled ? '' : 'disabled-button'"
                   fab
                   small
                   @click="vote(true)">
                <v-icon :color="enabled ? (voteEnabled && (isLoadingUp || voteValue === 1)) ? 'white' : 'red' : 'grey'">
                    mdi-thumb-up-outline
                </v-icon>
            </v-btn>
            <v-btn
                    class="mx-2"
                    :class="enabled ? '' : 'disabled-button'"
                    :color="(voteEnabled && (isLoadingDown || voteValue === 0)) ? 'blue' : ''"
                    fab
                    small
                    @click="vote(false)" :loading="isLoadingDown">
                <v-icon :color="enabled ? (voteEnabled && (isLoadingDown || voteValue === 0)) ? 'white' : 'blue' : 'grey'">
                    mdi-thumb-down-outline
                </v-icon>
            </v-btn>

            <post-vote-count :post="post" ref="voteCount"></post-vote-count>

            <v-snackbar v-model="snack">
                Please sign in to vote
                <v-btn color="red" text @click="snack = false">
                    Close
                </v-btn>
            </v-snackbar>
        </v-row>
    </v-app>
</template>

<script>
    import PostVoteCount from "./PostVoteCount";
    const ApiConfig = require("../api");
    const axios = require("axios").default;
    export default {
        name: "post-like-button",
        components: {PostVoteCount},
        async beforeMount() {
            if(this.enabled) {
                this.isLoadingUp = true;
                this.isLoadingDown = true;
            }
            if(this.enabled) {
                await this.getOwnVote();
                this.isLoadingUp = false;
                this.isLoadingDown = false;
                this.voteEnabled = true;
            }
        },
        data() {
            return {
                isLoadingUp: false,
                isLoadingDown: false,
                voteValue: null,
                voteEnabled: false,

                snack: false,
            }
        },
        methods: {
            async vote(direction) {
                if(!this.enabled) {
                    this.snack = true;
                    return;
                }

                this.voteEnabled = false;
                if(direction) {
                    this.isLoadingUp = true;
                } else {
                    this.isLoadingDown = true;
                }
                //this.voteValue = null;
                const result = await axios.post(`/api/upvote/${this.post}/${direction ? 1 : 0}`, {}, ApiConfig);
                if(result.status === 200) {
                    await this.$refs.voteCount.setVoteCount();
                    if(direction) {
                        this.isLoadingUp = false;
                        this.voteValue = 1;
                    } else {
                        this.isLoadingDown = false;
                        this.voteValue = 0;
                    }
                    this.voteEnabled = true;
                }

            },
            async getOwnVote() {
                const vote = await axios.get(`/api/upvote/own/${this.post}`, ApiConfig);
                if(vote.status === 200) {
                    if(vote.data.direction === true) {
                        this.voteValue = 1;
                    } else if(vote.data.direction === false) {
                        this.voteValue = 0;
                    }
                }
            }
        },
        props: {
            post: Number,
            enabled: Boolean
        }
    }
</script>


<style scoped>
    .disabled-button {
        background-color: rgba(0, 0, 0, 0.12) !important
    }

    .disabled-icon {
        background-color: rgba(0, 0, 0, 0.26) !important
    }
</style>