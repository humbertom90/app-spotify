<template>
  <div class="app-container">
    <div class="container py-4">
      <div class="row mb-3">
        <div class="col-md-6 offset-md-3">
          <input v-model="bandName" class="form-control mb-3" placeholder="Ingrese el nombre de la banda">
          <button @click="searchAlbums" class="btn btn-primary">Buscar</button>
        </div>
      </div>

      <div class="text-center" v-if="loading">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Cargando...</span>
        </div>
        <p class="mt-3">Cargando álbumes...</p>
      </div>

      <div class="text-center" v-if="errorResponse">
        <p>{{ errorResponse }}</p>
      </div>
      
      <div class="row row-cols-1 row-cols-md-4 g-4" v-if="!loading && !errorResponse">
        <div v-for="album in displayedAlbums" :key="album.name" class="col">
          <div class="card h-100">
            <img :src="album.cover.url" :alt="album.name" class="card-img-top" style="object-fit: cover;">
            <div class="card-body">
              <h5 class="card-title">{{ album.name }}</h5>
              <p class="card-text">Fecha de lanzamiento: {{ album.released }}</p>
              <p class="card-text">Número de pistas: {{ album.tracks }}</p>
            </div>
          </div>
        </div>
      </div>

      <nav aria-label="Page navigation" v-if="!loading && displayedAlbums.length > 0 && totalPages > 1">
        <ul class="pagination justify-content-center py-4">
          <li class="page-item" :class="{ disabled: currentPage === 1 }">
            <a class="page-link" href="#" @click="goToPage(currentPage - 1)">Anterior</a>
          </li>
          <li class="page-item" v-for="page in totalPages" :key="page" :class="{ active: page === currentPage }">
            <a class="page-link" href="#" @click="goToPage(page)">{{ page }}</a>
          </li>
          <li class="page-item" :class="{ disabled: currentPage === totalPages }">
            <a class="page-link" href="#" @click="goToPage(currentPage + 1)">Siguiente</a>
          </li>
        </ul>
      </nav>
    </div>
  </div>
</template>

<script setup>
  import { ref, computed } from 'vue';
  import axios from 'axios';

  const apiUrl = import.meta.env.VITE_API_BASE_URL;
  const bandName = ref('');
  const albums = ref([]);
  const currentPage = ref(1);
  const itemsPerPage = 4;
  const loading = ref(false);
  const errorResponse = ref(null);

  const searchAlbums = async () => {
    loading.value = true;
    errorResponse.value = null;
    try {
      const response = await axios.get(`${apiUrl}/albums?q=${bandName.value}&page=${currentPage.value}`);
      albums.value = response.data;
    } catch (error) {
      errorResponse.value = error.response.data.error;
    } finally {
      loading.value = false;
    }
  };

  const displayedAlbums = computed(() => {
    const startIndex = (currentPage.value - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    return albums.value.slice(startIndex, endIndex);
  });

  const goToPage = (page) => {
    if (page >= 1 && page <= totalPages.value) {
      currentPage.value = page;
      searchAlbums();
    }
  };

  const totalPages = computed(() => {
    return Math.ceil(albums.value.length / itemsPerPage);
  });
</script>

<style scope>
.app-container {
  width: 100vw;
  height: 100vh;
  background-image: url('../../images/background.jpg');
  background-attachment: fixed;
  background-size: cover;
  background-position: center; 
 
}
.card {
  transition: transform 0.2s;
}

.card:hover {
  transform: translateY(-5px);
}
</style>