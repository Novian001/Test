<template>
  <div>
    <ul>
      <li v-for="movie in movies" :key="movie.title">
        title = {{ movie.title }}
      </li>
      <li v-for="movie in movies" :key="movie.release_date">
        release =
        {{ movie.release_date }}
      </li>
      <li v-for="movie in movies" :key="movie.popularity">
        popularity =
        {{ movie.popularity }}
      </li>
      <li v-for="movie in movies" :key="movie.vote_average">
        vote =
        {{ movie.vote_average }}
      </li>
      <li v-for="movie in movies" :key="movie.category_id">
        category =
        {{ movie.category_id }}
      </li>
    </ul>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      movies: [],
    };
  },
  created() {
    axios
      .get("http://127.0.0.1:8000/api/now-playing")
      .then((response) => {
        this.movies = response.data.data;
      })
      .catch((error) => {
        console.log(error);
      });
  },
};
</script>
