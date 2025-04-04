<template>
  <ion-page>
    <ion-content class="ion-padding">
      <div class="login-container">
        <h1>Iniciar Sessió</h1>
        <ion-input v-model="email" placeholder="Email" class="input-field"></ion-input>
        <ion-input v-model="password" type="password" placeholder="Contrasenya" class="input-field"></ion-input>
        <ion-button expand="block" @click="login">Login</ion-button>
        <p>No tens compte? <router-link to="/register">Registra't</router-link></p>
      </div>
    </ion-content>
  </ion-page>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { IonPage, IonContent, IonInput, IonButton } from '@ionic/vue';
import api from '@/http';
import { useRouter } from 'vue-router';

const email = ref<string>('');
const password = ref<string>('');
const router = useRouter();

const login = async () => {
  try {
    const response = await api.post('/login', { email: email.value, password: password.value });
    localStorage.setItem('token', response.data.token);
    router.push('/tabs/home');
  } catch (error) {
    console.error('Error al login:', error);
    alert('Credencials invàlides');
  }
};
</script>

<style scoped>
.login-container {
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