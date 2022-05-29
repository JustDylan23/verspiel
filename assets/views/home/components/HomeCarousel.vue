<template>
  <div>
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
            src="https://media.discordapp.net/attachments/978050895910686731/979458260543373352/post_test_6.png?width=1920&height=540"
            class="d-block w-100"
            style="filter: blur(5px)"
            alt="..."
          />
          <div class="carousel-caption d-none d-md-block">
            <h5>{{ latestNovels[0].title }}</h5>
            <p>{{ latestNovels[0].description }}</p>
          </div>
        </div>
        <div
          v-for="novel in latestNovels.slice(1)"
          :key="novel.id"
          class="carousel-item"
        >
          <img
            src="https://media.discordapp.net/attachments/978050895910686731/979458260543373352/post_test_6.png?width=1920&height=540"
            class="d-block w-100"
            style="filter: blur(5px)"
            alt="..."
          />
          <div class="carousel-caption d-none d-md-block">
            <h5>{{ novel.title }}</h5>
            <p>{{ novel.description }}</p>
          </div>
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
