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
            <h1 class="black--text headline">Register</h1>
            <v-form lazy-validation class="pa-3 d-inline-block" style="width:350px;">
              <v-text-field
                :rules="requiredRules"
                v-model="firstName"
                placeholder="enter your first name"
                label="First Name"
              ></v-text-field>
              <v-text-field
                :rules="requiredRules"
                v-model="lastName"
                placeholder="enter your last name"
                label="Last Name"
              ></v-text-field>
              <v-text-field
                :rules="emailRules"
                v-model="email"
                placeholder="enter your email"
                label="E-mail"
              ></v-text-field>
              <v-text-field
                :rules="passwordRules"
                v-model="password"
                placeholder="enter your password"
                label="Password"
              ></v-text-field>
              <v-text-field
                :rules="passwordConfirmationRules"
                v-model="passwordConfirmation"
                placeholder="enter your password confirmation"
                label="Password Confirmation"
              ></v-text-field>
              <router-link class="ma-5" to="login">Already Have Account?</router-link>
              <v-btn color="orange lighten-1" @click="register">Sign Up</v-btn>
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
export default {
  data() {
    return {
      firstName: "",
      lastName: "",
      email: "",
      password: "",
      passwordConfirmation: "",
      snackbar: false,
      snackbarColor: "",
      snackbarMessage: "",
      requiredRules: [v => !!v || "this field is required"],
      emailRules: [
        v => !!v || "this field is required",
        v =>
          /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(v) ||
          "this must be email format"
      ],
      passwordRules: [
        v => !!v || "this field is required",
        v => (v && v.length >= 8) || "password should be greater than 8 chars"
      ],
      passwordConfirmationRules: [
        v => !!v || "this field is required",
        v =>
          (v && v == this.password) ||
          "password confirmation must match with password"
      ]
    };
  },
  methods: {
    register() {
      let user = {
        first_name: this.firstName,
        last_name: this.lastName,
        password: this.password,
        password_confirmation: this.passwordConfirmation,
        email: this.email
      };
      axios
        .post("/user/register", user)
        .then(response => {
          let message = response.data.message;
          this.showSnackBar("green", message);
        })
        .catch(error => {
          let status = error.response.status;
          if (status === 422) {
            let message = error.response.data.errors[0];
            this.showSnackBar("red", message);
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