<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateMovieRequest;
use App\Models\Category;
use App\Models\Movie;
use Exception;
use App\Http\Requests\UpdateMovieRequest;
use Illuminate\Http\Request;
use Termwind\Components\Dd;

class MovieController extends Controller
{
    use ResponseFormatter;

    // Menampilkan film yang sedang tayang
    public function nowPlaying()
    {
        try {
            $category = Category::where('name', 'now playing')->first();
            
            if(!$category) {
                throw new Exception('Category not found');
            }
            $movies = $category->movies;
            // dd($movies);

            return $this->responseFormatter($this->httpCode['StatusOK'], $this->httpMessage['StatusOK'], $movies);
        } catch (Exception $e) {
            return $this->responseFormatter($this->httpCode['StatusInternalServerError'], $e->getMessage(), null);
        }
    }

    // Menampilkan film yang populer
    public function popular()
    {
        try {
            $category = Category::where('name', 'popular')->first();
            
            if(!$category) {
                throw new Exception('Category not found');
            }
            $movies = $category->movies;

            return $this->responseFormatter($this->httpCode['StatusOK'], $this->httpMessage['StatusOK'], $movies);
        } catch (Exception $e) {
            return $this->responseFormatter($this->httpCode['StatusInternalServerError'], $e->getMessage(), null);
        }
    }

    // Menampilkan film dengan rating tertinggi
    public function topRated()
    {
        try {
            $category = Category::where('name', 'top rated')->first();
            
            if(!$category) {
                throw new Exception('Category not found');
            }
            $movies = $category->movies;

            return $this->responseFormatter($this->httpCode['StatusOK'], $this->httpMessage['StatusOK'], $movies);
        } catch (Exception $e) {
            return $this->responseFormatter($this->httpCode['StatusInternalServerError'], $e->getMessage(), null);
        }
    }

    // Menampilkan film yang akan datang
    public function upcoming()
    {
        try {
            $category = Category::where('name', 'upcoming')->first();
            
            if(!$category) {
                throw new Exception('Category not found');
            }
            $movies = $category->movies;

            return $this->responseFormatter($this->httpCode['StatusOK'], $this->httpMessage['StatusOK'], $movies);
        } catch (Exception $e) {
            return $this->responseFormatter($this->httpCode['StatusInternalServerError'], $e->getMessage(), null);
        }
    }

    public function store(CreateMovieRequest $request)
    {
        try {
            $movie = Movie::create([
                'title' => $request->title,
                'overview' => $request->overview,
                'poster_path' => $request->poster_path,
                'release_date' => $request->release_date,
                'popularity' => $request->popularity,
                'vote_average' => $request->vote_average,
                'category_id' => $request->category_id,
            ]);
            
            if(!$movie) {
                throw new Exception('Movie failed to create');
            }

            return $this->responseFormatter($this->httpCode['StatusOK'], $this->httpMessage['StatusOK'], $movie);
        } catch (Exception $e) {
            return $this->responseFormatter($this->httpCode['StatusInternalServerError'], $e->getMessage(), null);
        }
    }

    public function update(UpdateMovieRequest $request, $id){
        try{
            $movie = Movie::find($id);

            if(!$movie) {
                throw new Exception('Movie not found');
            }

            $movie->update([
                'title' => $request->title,
                'overview' => $request->overview,
                'poster_path' => $request->poster_path,
                'release_date' => $request->release_date,
                'popularity' => $request->popularity,
                'vote_average' => $request->vote_average,
                'category_id' => $request->category_id,
            ]);

            return $this->responseFormatter($this->httpCode['StatusOK'], $this->httpMessage['StatusOK'], $movie);

        }catch(Exception $e){
            return $this->responseFormatter($this->httpCode['StatusInternalServerError'], $e->getMessage(), null);
        }
    }

    public function destroy($id){
        try{
            $movie = Movie::find($id);

            if(!$movie) {
                throw new Exception('Movie not found');
            }

            $movie->delete();

            return $this->responseFormatter($this->httpCode['StatusOK'], $this->httpMessage['StatusOK'], $movie);

        }catch(Exception $e){
            return $this->responseFormatter($this->httpCode['StatusInternalServerError'], $e->getMessage(), null);
        }
    }
}
