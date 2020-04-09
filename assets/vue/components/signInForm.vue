<template>
    <v-app>
        <v-row app align="center">
            <v-form app>
                <v-text-field v-model="username" label="Username"></v-text-field>
                <v-text-field
                        v-model="password"
                        :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                        :rules="[rules.required]"
                        :type='passwordType'
                        label="Password"
                        @click:append="showPassword = !showPassword">
                </v-text-field>
                <v-btn :disabled="!canSubmit" @click="submit">Submit</v-btn>
                <v-icon>mdi-eye</v-icon>
            </v-form>
        </v-row>
    </v-app>
</template>


<script>
    const ApiConfig = require("../api");
    const axios = require("axios").default;
	export default {
		name: "sign-in-form",
		data() {
			return {
				username: "",
				password: "",
				passwordType: "Password",
				showPassword: false,
				rules: {
					required: value => !!value || 'Required.',
					emailMatch: () => ('The email and password you entered don\'t match'),
				}
			}
		},
		methods: {
			async submit() {
				console.log(this.username, this.password);
				const token = await axios.post("/api/login_check", {
					username: this.username,
                    password: this.password
                }, ApiConfig);
				if(token.status === 200) {
					console.log(this);
					this.$store.commit('setJWTToken', token.data.token);
                }
				console.log(token);
			}
		},
		computed: {
			canSubmit() {
				return this.username.length > 0 && this.password.length > 0
			},
		}
	}
</script>

<style scoped>

</style>
