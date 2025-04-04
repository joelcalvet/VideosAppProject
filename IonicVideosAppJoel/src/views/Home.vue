<template>
  <ion-page>
    <ion-header>
      <ion-toolbar color="primary">
        <ion-title>Tots</ion-title>
      </ion-toolbar>
    </ion-header>
    <ion-content class="ion-padding">
      <ion-list class="multimedia-list">
        <ion-card v-for="item in multimedia" :key="item.id" class="multimedia-card">
          <ion-card-header>
            <ion-card-title class="card-title">{{ item.title || 'Sense títol' }}</ion-card-title>
          </ion-card-header>
          <ion-card-content class="card-content">
            <img v-if="isImage(item.path)" :src="getStorageURL(item.path)" alt="Imatge" class="media" />
            <video v-else-if="isVideo(item.path)" :src="getStorageURL(item.path)" controls class="media"></video>
            <p v-else class="unsupported">Format no suportat: {{ item.path }}</p>
          </ion-card-content>
        </ion-card>
      </ion-list>
      <ion-text class="no-content" v-if="!multimedia.length">No hi ha multimèdia disponible.</ion-text>
    </ion-content>
  </ion-page>
</template>

<script setup lang="ts">
import { IonPage, IonHeader, IonToolbar, IonTitle, IonContent, IonList, IonCard, IonCardHeader, IonCardTitle, IonCardContent, IonText } from '@ionic/vue';
import { ref, onMounted, watch } from 'vue';
import { useRoute } from 'vue-router';
import api, { getStorageURL } from '@/http';

const multimedia = ref<any[]>([]);
const route = useRoute(); // Obtenim la ruta actual

const loadMultimedia = async () => {
  try {
    const response = await api.get('/multimedia');
    multimedia.value = response.data;
    console.log('Multimèdia carregat a Home:', multimedia.value); // Depuració
  } catch (error) {
    console.error('Error carregant multimèdia:', error);
  }
};

const isImage = (path: string) => /\.(jpg|jpeg|png|gif)$/i.test(path);
const isVideo = (path: string) => /\.(mp4|webm|ogg)$/i.test(path);

// Carregar multimèdia quan es munta el component
onMounted(() => {
  loadMultimedia();
});

// Si l'usuari torna a aquesta pàgina (des d'una altra), es tornen a carregar les dades
watch(() => route.fullPath, () => {
  loadMultimedia();
});
</script>

<style scoped>
.multimedia-list {
  padding: 0;
}

.multimedia-card {
  background: #2a2a2a;
  color: #ffffff;
  border-radius: 12px;
  margin: 10px 0;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  transition: transform 0.2s ease;
}

.multimedia-card:hover {
  transform: scale(1.02);
}

.card-title {
  font-size: 18px;
  font-weight: 600;
  color: #ff4500;
}

.card-content {
  padding: 10px;
}

.media {
  max-width: 100%;
  height: auto;
  border-radius: 8px;
  margin-top: 10px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.unsupported {
  color: #cccccc;
  font-style: italic;
}

.no-content {
  display: block;
  text-align: center;
  color: #cccccc;
  margin-top: 20px;
  font-size: 16px;
}
</style>
