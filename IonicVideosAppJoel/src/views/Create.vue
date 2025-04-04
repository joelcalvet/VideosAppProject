<template>
  <ion-page>
    <ion-header>
      <ion-toolbar color="primary">
        <ion-title>Pujar</ion-title>
      </ion-toolbar>
    </ion-header>
    <ion-content class="ion-padding">
      <ion-input v-model="title" placeholder="Títol"></ion-input>
      <ion-select v-model="type" placeholder="Selecciona el tipus">
        <ion-select-option value="photo">Foto</ion-select-option>
        <ion-select-option value="video">Vídeo</ion-select-option>
      </ion-select>
      <input type="file" @change="onFileChange" accept="image/*,video/*" />
      <ion-button @click="upload">Pujar</ion-button>
    </ion-content>
  </ion-page>
</template>

<script setup lang="ts">
import { IonPage, IonHeader, IonToolbar, IonTitle, IonContent, IonInput, IonButton, IonSelect, IonSelectOption } from '@ionic/vue';
import { ref } from 'vue';
import api from '@/http';
import { multimediaStore } from '@/stores/multimedia';

const title = ref<string>('');
const type = ref<string>('');
const file = ref<File | null>(null);

const onFileChange = (event: Event) => {
  const target = event.target as HTMLInputElement;
  if (target.files) file.value = target.files[0];
};

const upload = async () => {
  if (!file.value || !type.value) {
    alert('Selecciona un fitxer i un tipus.');
    return;
  }

  const formData = new FormData();
  formData.append('file', file.value);
  formData.append('type', type.value);
  formData.append('title', title.value || '');

  try {
    const response = await api.post('/multimedia', formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });
    multimediaStore.addMultimedia(response.data);
    title.value = '';
    type.value = '';
    file.value = null;
    alert('Pujat correctament!');
  } catch (error) {
    console.error('Error pujant:', error);
    console.error('Detalls de l\'error:', error.response?.data);

    if (error.response?.status === 422) {
      console.error('Validation errors:', error.response.data.errors);
      alert(`Error: ${error.response.data.message}`);
    }
  }
};
</script>