<template>
    <v-app>
        <v-navigation-drawer app clipped>
            <v-list dense nav>
                <v-list-item to="/">
                    <v-list-item-avatar>
                        <v-icon>{{ icons.home }}</v-icon>
                    </v-list-item-avatar>
                    <v-list-item-content>
                        <v-list-item-title>Home</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item to="/about">
                    <v-list-item-avatar>
                        <v-icon>{{ icons.about }}</v-icon>
                    </v-list-item-avatar>
                    <v-list-item-content>
                        <v-list-item-title>About</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>
        <v-app-bar app clipped-left dark>
            <v-toolbar-title>My Symfony/Vuetify App</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn text to="/api" @click="window.location = 'http://' + document.location.domain + '/api'" v-if="connected">Api Platform</v-btn>
            <v-btn text to="/logout" v-if="connected">Log out</v-btn>
            <v-btn text to="/login" v-if="!connected">Sign in</v-btn>
            <v-btn text to="/register" v-if="!connected">Sign up</v-btn>
        </v-app-bar>
        <v-content>
            <v-container fluid>
                <router-view></router-view>
            </v-container>
        </v-content>
        <v-footer app inset>
            <span class="mx-2">&copy; 2019 Anyone Who Finds This</span>
        </v-footer>
    </v-app>
</template>

<script>
	import {mdiHome, mdiInformation} from '@mdi/js'

	export default {
		name: 'App',
		data: () => ({
			icons: {
				about: mdiInformation,
				home: mdiHome
			}
		}),
        computed: {
			connected() {
				return this.$store.state.token.length === 0;
            }
        }
	}
</script>

<style>

</style>
