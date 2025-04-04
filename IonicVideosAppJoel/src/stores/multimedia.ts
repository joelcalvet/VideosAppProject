import { ref } from 'vue';
import api from '@/http';

interface MultimediaItem {
    id: number;
    title?: string | null;
    path: string;
    user_id: number;
    created_at?: string;
    updated_at?: string;
}

export const multimediaStore = {
    multimedia: ref<MultimediaItem[]>([]),
    userMultimedia: ref<MultimediaItem[]>([]),

    async fetchMultimedia() {
        try {
            const response = await api.get('/multimedia');
            this.multimedia.value = response.data as MultimediaItem[];
        } catch (error) {
            console.error('Error carregant multimèdia:', error);
        }
    },

    async fetchUserMultimedia() {
        try {
            const response = await api.get('/user/multimedia');
            this.userMultimedia.value = response.data as MultimediaItem[];
        } catch (error) {
            console.error('Error carregant el meu multimèdia:', error);
        }
    },

    addMultimedia(item: MultimediaItem) {
        this.multimedia.value.push(item); // Afegeix a la llista global
        this.userMultimedia.value.push(item); // Afegeix a la llista de l'usuari
    },

    updateMultimedia(item: MultimediaItem) {
        const index = this.multimedia.value.findIndex(m => m.id === item.id);
        if (index !== -1) this.multimedia.value[index] = item;
        const userIndex = this.userMultimedia.value.findIndex(m => m.id === item.id);
        if (userIndex !== -1) this.userMultimedia.value[userIndex] = item;
    },

    deleteMultimedia(id: number) {
        this.multimedia.value = this.multimedia.value.filter(m => m.id !== id);
        this.userMultimedia.value = this.userMultimedia.value.filter(m => m.id !== id);
    }
};