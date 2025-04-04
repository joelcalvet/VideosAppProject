<template>
  <ion-page>
    <ion-content class="ion-padding">
      <div class="register-container">
        <h1>Registre</h1>
        <ion-input v-model="name" placeholder="Nom" class="input-field"></ion-input>
        <ion-input v-model="email" placeholder="Email" class="input-field"></ion-input>
        <ion-input v-model="password" type="password" placeholder="Contrasenya" class="input-field"></ion-input>
        <ion-button expand="block" @click="register">Registrar-se</ion-button>
        <p>Ja tens compte? <router-link to="/login">Inicia sessió</router-link></p>
      </div>
    </ion-content>
  </ion-page>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { IonPage, IonContent, IonInput, IonButton } from '@ionic/vue';
import api from '@/http';
import { useRouter } from 'vue-router';

const name = ref<string>('');
const email = ref<string>('');
const password = ref<string>('');
const router = useRouter();

const register = async () => {
  try {
    const response = await api.post('/register', {
      name: name.value,
      email: email.value,
      password: password.value,
    });
    localStorage.setItem('token', response.data.token);
    router.push('/tabs/home');
  } catch (error: any) {
    console.error('Error al registre:', error);
    if (error.response?.status === 422) {
      const errors = error.response.data.errors;
      if (errors.email) {
        alert('Aquest email ja està registrat!');
      } else {
        alert('Error de validació: ' + JSON.stringify(errors));
      }
    } else {
      alert('Error al registrar-se: ' + (error.response?.data?.message || 'Error desconegut'));
    }
  }
};
</script>

<style scoped>
.register-container {
  max-width: 400px;
  margin: 0 auto;
  padding: 20px;
  text-align: center;
}
h1 {
  color: var(--ion-color-primary);
  margin-bottom: 25px;
  font-size: 2rem;
}
p {
  color: #cccccc;
}
</style>