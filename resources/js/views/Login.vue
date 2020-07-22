<template>
  <v-app style="background:#7E57C2">
    <v-container fluid>
      <v-row class="text-center" style="margin-top:70px;">
        <v-col cols="12">
          <v-icon large class="d-inline auth-icon" color="white">collections</v-icon>
          <v-card-text class="font-weight-black white--text display-1 d-inline">SOLO IMAGES</v-card-text>
        </v-col>
        <v-col cols="12">
          <v-card class="pa-2 d-inline-block white--text" outlined tile>
            <h1 class="black--text headline">Login</h1>
            <v-form lazy-validation class="pa-3 d-inline-block">
              <v-text-field
                :rules="emailRules"
                v-model="email"
                type="email"
                placeholder="enter your email"
                label="E-mail"
              ></v-text-field>
              <v-text-field
                :type="show_password ? 'text' : 'password'"
                @click:append="show_password = !show_password"
                v-model="password"
                :rules="passwordRules"
                placeholder="enter your password"
                label="Password"
              ></v-text-field>
              <router-link class="ma-5" to="register">Create Account?</router-link>
              <v-btn color="orange lighten-1" @click="login">Sign In</v-btn>
              <multi-select :value="[]" :options="[{value:'basel',label:'name'}]"></multi-select>
            </v-form>
          </v-card>
        </v-col>
      </v-row>
      <v-snackbar v-model="snackbar" :color="snackbarColor" bottom left>
        {{snackbarMessage}}
        <v-btn @click="snackbar = false">Close</v-btn>
      </v-snackbar>
    </v-container>
  </v-app>
</template>
<script>
import MultiSelect from "../components/MultiSelect";

export default {
  components: {
    MultiSelect
  },
  data() {
    return {
      email: "",
      emailRules: [v => !!v || "Email is required"],
      password: "",
      passwordRules: [v => !!v || "Password is required"],
      show_password: false,
      snackbar: false,
      snackbarColor: "",
      snackbarMessage: ""
    };
  },
  created() {
    this.activate();
  },
  methods: {
    login() {
      let email = this.email;
      let password = this.password;
      if (email === "" || password === "") {
        this.showSnackBar("red", "Please Fill Your Form");
        return;
      }
      let credentials = { email: email, password: password };
      axios
        .post("/user/login", credentials)
        .then(response => {
          let data = response.data;
          let token = data.token;
          localStorage.setItem("token", token);
        })
        .catch(error => {
          if (error.response.status === 401) {
            this.showSnackBar("red", "Wrong Email or Password");
            return;
          }
          this.showSnackBar("red", "Something Went Wrong");
        });
    },
    activate() {
      let token = this.$route.query.token;
      if (!token) {
        return;
      }
      axios
        .get(`/user/activate/${token}`)
        .then(response => {
          let message = response.data.message;
          this.showSnackBar("green", "your account activated, you can login");
        })
        .catch(error => {
          let status = error.response.status;
          if (status === 404) {
            this.showSnackBar(
              "green",
              "your account already activated, you can login"
            );
            return;
          }
          this.showSnackBar("red", "something went wrong");
        });
    },
    showSnackBar(color, message) {
      this.snackbarColor = color;
      this.snackbarMessage = message;
      this.snackbar = true;
    }
  }
};
</script>
<style>
.auth-icon {
  vertical-align: text-bottom !important;
}
</style>