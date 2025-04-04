<template>
  <ion-page>
    <ion-header>
      <ion-toolbar color="primary">
        <ion-title>Meus</ion-title>
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
            <p v-else class="unsupported">Format no suportat</p>
            <div class="button-container">
              <ion-button color="primary" @click="openEditModal(item)">Editar</ion-button>
              <ion-button color="danger" @click="deleteItem(item.id)">Eliminar</ion-button>
            </div>
          </ion-card-content>
        </ion-card>
      </ion-list>
      <ion-text class="no-content" v-if="!multimedia.length">No has pujat cap multimèdia.</ion-text>

      <!-- Modal per editar -->
      <ion-modal :is-open="isEditModalOpen" @didDismiss="closeEditModal">
        <ion-header>
          <ion-toolbar>
            <ion-title>Editar multimèdia</ion-title>
            <ion-buttons slot="end">
              <ion-button @click="closeEditModal">Tancar</ion-button>
            </ion-buttons>
          </ion-toolbar>
        </ion-header>
        <ion-content class="ion-padding">
          <!-- Previsualització del contingut actual -->
          <img v-if="isImage(editPath)" :src="getStorageURL(editPath)" alt="Imatge actual" class="modal-media" />
          <video v-else-if="isVideo(editPath)" :src="getStorageURL(editPath)" controls class="modal-media"></video>
          <p v-else class="unsupported">Format no suportat</p>
          <!-- Camp per al títol -->
          <ion-input v-model="editTitle" placeholder="Títol" class="modal-input"></ion-input>
          <!-- Camp per pujar un nou fitxer -->
          <input type="file" @change="onFileChange" accept="image/*,video/*" class="modal-file-input" />
          <ion-button expand="block" @click="saveEdit">Guardar</ion-button>
        </ion-content>
      </ion-modal>
    </ion-content>
  </ion-page>
</template>

<script setup lang="ts">
import {
  IonPage, IonHeader, IonToolbar, IonTitle, IonContent, IonList,
  IonCard, IonCardHeader, IonCardTitle, IonCardContent, IonText,
  IonButton, IonModal, IonButtons, IonInput
} from '@ionic/vue';
import { ref, onMounted, watch } from 'vue';
import api, { getStorageURL } from '@/http';
import { useRoute } from 'vue-router';

const multimedia = ref<any[]>([]);
const isEditModalOpen = ref(false);
const editItemId = ref<number | null>(null);
const editTitle = ref<string>('');
const editPath = ref<string>(''); // Per previsualitzar el fitxer actual
const editFile = ref<File | null>(null); // Per emmagatzemar el nou fitxer
const route = useRoute();

const loadMultimedia = async () => {
  try {
    const response = await api.get('/user/multimedia');
    multimedia.value = response.data;
  } catch (error) {
    console.error('Error carregant el meu multimèdia:', error);
  }
};

const isImage = (path: string) => /\.(jpg|jpeg|png|gif)$/i.test(path);
const isVideo = (path: string) => /\.(mp4|webm|ogg)$/i.test(path);

const openEditModal = (item: any) => {
  editItemId.value = item.id;
  editTitle.value = item.title || '';
  editPath.value = item.path; // Carrega el path actual per previsualitzar
  editFile.value = null; // Reseteja el fitxer nou
  isEditModalOpen.value = true;
};

const closeEditModal = () => {
  isEditModalOpen.value = false;
  editItemId.value = null;
  editTitle.value = '';
  editPath.value = '';
  editFile.value = null;
};

const onFileChange = (event: Event) => {
  const target = event.target as HTMLInputElement;
  if (target.files) editFile.value = target.files[0];
};

const saveEdit = async () => {
  if (!editItemId.value) return;

  const formData = new FormData();
  formData.append('title', editTitle.value || '');
  if (editFile.value) {
    formData.append('file', editFile.value); // Afegeix el nou fitxer si n'hi ha
    formData.append('_method', 'PUT'); // Necessari per Laravel amb FormData i PUT
  }

  try {
    const response = await api.post(`/multimedia/${editItemId.value}`, formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });
    const updatedItem = response.data;
    const index = multimedia.value.findIndex(item => item.id === updatedItem.id);
    if (index !== -1) {
      multimedia.value[index] = updatedItem; // Actualitza localment
    }
    closeEditModal();
    alert('Editat correctament!');
  } catch (error) {
    console.error('Error editant:', error);
    console.error('Detalls de l\'error:', error.response?.data);
    alert('Error al guardar els canvis.');
  }
};

const deleteItem = async (id: number) => {
  if (!confirm('Segur que vols eliminar aquest element?')) return;

  try {
    await api.delete(`/multimedia/${id}`);
    multimedia.value = multimedia.value.filter(item => item.id !== id); // Elimina localment
    alert('Eliminat correctament!');
  } catch (error) {
    console.error('Error eliminant:', error);
    alert('Error al eliminar.');
  }
};

onMounted(() => {
  loadMultimedia();
});

// Si l'usuari torna a aquesta pàgina, es tornen a carregar les dades
watch(() => route.fullPath, () => {
  loadMultimedia();
});
</script>

<style scoped>
.multimedia-list {
  padding: 0;
}

.multimedia-card {
  background: var(--ion-card-background, #2a2a2a);
  color: var(--ion-card-color, #ffffff);
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

.button-container {
  margin-top: 10px;
  display: flex;
  justify-content: space-between;
}

.modal-media {
  max-width: 100%;
  height: auto;
  border-radius: 8px;
  margin-bottom: 15px;
}

.modal-input {
  margin-bottom: 15px;
}

.modal-file-input {
  margin-bottom: 15px;
  display: block;
}
</style>