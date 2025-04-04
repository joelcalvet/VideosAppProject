<template>
  <ion-app>
    <ion-header>
      <ion-toolbar color="primary">
        <ion-title>IonicVideosAppJoel</ion-title>
      </ion-toolbar>
      <!-- Menú només visible fora de login/register -->
      <ion-toolbar v-if="!isAuthRoute" class="menu-toolbar">
        <div class="menu-buttons">
          <ion-button @click="navigateTo('/tabs/home')" :class="{ 'active': isActive('/tabs/home') }">
            <ion-icon :icon="home"></ion-icon>
            <ion-label>Tots</ion-label>
          </ion-button>
          <ion-button @click="navigateTo('/tabs/user')" :class="{ 'active': isActive('/tabs/user') }">
            <ion-icon :icon="person"></ion-icon>
            <ion-label>Meus</ion-label>
          </ion-button>
          <ion-button @click="navigateTo('/tabs/create')" :class="{ 'active': isActive('/tabs/create') }">
            <ion-icon :icon="add"></ion-icon>
            <ion-label>Pujar</ion-label>
          </ion-button>
          <ion-button @click="logout">
            <ion-icon :icon="logOut"></ion-icon>
            <ion-label>Sortir</ion-label>
          </ion-button>
        </div>
      </ion-toolbar>
    </ion-header>

    <ion-content>
      <ion-router-outlet />
    </ion-content>

    <ion-footer>
      <ion-toolbar>
        <ion-title>Creador: Joel - 2025</ion-title>
      </ion-toolbar>
    </ion-footer>
  </ion-app>
</template>

<script setup lang="ts">
import { IonApp, IonRouterOutlet, IonHeader, IonToolbar, IonTitle, IonFooter, IonContent, IonButton, IonIcon, IonLabel } from '@ionic/vue';
import { home, person, add, logOut } from 'ionicons/icons';
import { useRouter, useRoute } from 'vue-router';
import { computed } from 'vue';
import api from '@/http';

const router = useRouter();
const route = useRoute();

const navigateTo = (path: string) => {
  router.push(path);
};

const isActive = (path: string) => {
  return route.path === path;
};

const logout = async () => {
  try {
    await api.post('/logout');
  } catch (error) {
    console.error('Error al tancar sessió:', error);
  } finally {
    localStorage.removeItem('token');
    router.push('/login');
  }
};

// Propietat reactiva per comprovar si és una ruta d'autenticació
const isAuthRoute = computed(() => {
  return route.path === '/login' || route.path === '/register';
});
</script>

<style scoped>
ion-app {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

ion-header {
  flex-shrink: 0;
}

/* ✅ Ajustem l'alçada perquè el text no es talli */
.menu-toolbar {
  --background: #2a2a2a;
  --color: #ffffff;
  height: 80px;
  display: flex;
  justify-content: center;
  align-items: center;
}

/* ✅ Botons més compactes i centrats */
.menu-buttons {
  display: flex;
  justify-content: space-around;
  width: 100%;
  max-width: 600px;
}

/* ✅ Ajustaments perquè tot càpiga */
ion-button {
  --color: #ffffff;
  --background: transparent;
  --background-activated: transparent;
  --background-hover: rgba(255, 255, 255, 0.1);
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  width: 90px; /* Més estret perquè càpiguen més botons */
  height: auto;
  padding: 5px;
}

/* ✅ Icones més petites per guanyar espai */
ion-icon {
  font-size: 22px;
  margin-bottom: 2px;
}

/* ✅ Mida del text reduïda perquè tot es vegi */
ion-label {
  color: #ffffff;
  font-size: 11px;
  font-weight: 500;
  text-align: center;
}

/* ✅ Indicar quin botó està actiu */
ion-button.active {
  --color: #ff4500;
  border-bottom: 2px solid #ff4500;
}

/* ✅ Assegurar que el contingut es vegi bé i no es talli */
ion-content {
  flex: 1;
  overflow-y: auto;
}

ion-footer {
  flex-shrink: 0;
  height: 40px;
}

ion-footer ion-toolbar {
  --background: #2a2a2a;
  text-align: center;
}
</style>
