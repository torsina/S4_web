import Vue from 'vue'
import Vuetify from 'vuetify/lib'
import '@mdi/font/css/materialdesignicons.css'
const Vuex = require("vuex");

Vue.use(Vuetify);
Vue.use(Vuex);

const store = new Vuex.Store({
  state: {
    token: ""
    // have to putempty objects for use by the actions here
  },
  getters: {

  },
  mutations: {
    setJWTToken(state, token) {
      state.token = token;
    }
  },
  actions: {
    // put axios requests here
  }
});

Vue.config.productionTip = false;

new Vue({
  store,
  vuetify: new Vuetify({
    icons: {
      iconfont: 'mdi'
    }
  }),
  components: {
    "post-like-button": require('./components/PostLikeButton').default,
    "post-vote-count": require('./components/PostVoteCount').default,
    "form-tag-input": require('./components/FormTagInput').default,
    "post-create-form": require("./components/PostCreateForm").default
  }
}).$mount('#app');
