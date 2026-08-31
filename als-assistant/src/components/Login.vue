<script setup lang="ts">

import { ref } from 'vue';



  const form = ref(null);



  const onSubmit = () => {

    console.log(form.value);

  }

</script>



<template>

  <div class='login' align='center'>

    <Transition name="fade" appear class="flex h-1/3 justify-center">

      <div>

        <v-img

          width="300"

          max-width="300"

          src="@/assets/als-logo.svg"

        />

      </div>

    </Transition>

   



    <Transition name="slide-up" appear>

      <v-card class="sign-in-card h-2/3 w-full py-10 px-5 flex flex-col justify-between">

        <v-card-item>

          <v-card-title>

            Welcome back!

          </v-card-title>



          <p class="text-caption">

            Log in to access your tools.

          </p>

        </v-card-item>



        <div>

          <v-text-field

            variant="outlined"

            color="primary"

            label="Email"

          ></v-text-field>



          <v-text-field

            variant="outlined"

            color="primary"

            label="Password"

            type="password"

          ></v-text-field>



          <v-btn block color="primary" class="mb-5" size="x-large" >Log In</v-btn>



          <v-btn block color="primary" variant="plain">No account? Register</v-btn>

          <v-btn block color="primary" variant="plain">Forgot password?</v-btn>

        </div>

      </v-card>

    </Transition>

  </div>

</template>



<style>

.login {

  background-color: #009241 !important;

  display: flex;

  height: 100%;

  align-items:center;

  justify-content: space-between;

  flex-direction: column;

}



.v-container.login {

  align-content: center;

  min-height: 100dvh;

}



.sign-in-card {

  border-radius: 25px 25px 0 0 !important;

}



.slide-up-appear-active,

.slide-up-enter-active {

  transition: all 4s cubic-bezier(0.16, 1, 0.3, 1);

  transition-delay: 3s;

}



.slide-up-appear-from,

.slide-up-enter-from {

  opacity: 0;

  transform: translateY(150px);

}



.slide-up-appear-to,

.slide-up-enter-to {

  opacity: 1;

  transform: translateY(0);

}



.fade-appear-active,

.fade-enter-active,

.fade-leave-active {

  transition: opacity 1.5s ease-out, transform 4s cubic-bezier(0.16, 1, 0.3, 1) 2.5s;



}



.fade-appear-from,

.fade-enter-from,

.fade-leave-to {

  opacity: 0;

  transform: translateY(35vh);

}



.fade-appear-to,

.fade-enter-to,

.fade-leave-from {

  opacity: 1;

  transform: translateY(0);

}

</style>