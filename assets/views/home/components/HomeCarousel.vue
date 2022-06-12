<template>
  <div v-if="latestNovels.length !== 0">
    <h3>Featured novels</h3>
    <div
      id="carouselExampleDark"
      class="carousel slide"
      data-bs-ride="carousel"
    >
      <div class="carousel-indicators">
        <button
          type="button"
          data-bs-target="#carouselExampleDark"
          data-bs-slide-to="0"
          class="active"
          aria-current="true"
          :aria-label="latestNovels[0].title"
        />
        <button
          v-for="(novel, key) in latestNovels.slice(1)"
          :key="key"
          type="button"
          data-bs-target="#carouselExampleDark"
          :data-bs-slide-to="key + 1"
          :aria-label="novel.title"
        />
      </div>
      <div class="carousel-inner rounded-3">
        <div class="carousel-item active">
          <img
            src="https://media.discordapp.net/attachments/978050895910686731/985399366296678480/IMG_3498.png?width=1440&height=405"
            class="d-block w-100"
            style="filter: blur(5px)"
            alt="slide background"
          />
          <RouterLink
            :to="{ name: 'novel', params: { id: latestNovels[0].id } }"
            class="carousel-caption text-white"
          >
            <h5 class="text-truncate">{{ latestNovels[0].title }}</h5>
            <p class="d-none d-md-block">
              {{ latestNovels[0].shortDescription }}
            </p>
          </RouterLink>
        </div>
        <div
          v-for="novel in latestNovels.slice(1)"
          :key="novel.id"
          class="carousel-item"
        >
          <img
            src="https://media.discordapp.net/attachments/978050895910686731/985399366296678480/IMG_3498.png?width=1440&height=405"
            class="d-block w-100"
            style="filter: blur(5px)"
            alt="slide background"
          />
          <RouterLink
            :to="{ name: 'novel', params: { id: novel.id } }"
            class="carousel-caption text-white"
          >
            <h5 class="text-truncate">{{ novel.title }}</h5>
            <p class="d-none d-md-block">{{ novel.shortDescription }}</p>
          </RouterLink>
        </div>
      </div>
      <button
        class="carousel-control-prev"
        type="button"
        data-bs-target="#carouselExampleDark"
        data-bs-slide="prev"
      >
        <span class="carousel-control-prev-icon" aria-hidden="true" />
        <span class="visually-hidden">Previous</span>
      </button>
      <button
        class="carousel-control-next"
        type="button"
        data-bs-target="#carouselExampleDark"
        data-bs-slide="next"
      >
        <span class="carousel-control-next-icon" aria-hidden="true" />
        <span class="visually-hidden">Next</span>
      </button>
    </div>
  </div>
</template>

<script setup>
import { reactive } from 'vue';
import axios from 'axios';

const latestNovels = reactive(
  await axios.get('/api/novels/featured').then(({ data }) => data)
);
</script>
