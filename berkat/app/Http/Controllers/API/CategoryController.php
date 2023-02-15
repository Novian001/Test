<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCategoryRequest;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ResponseFormatter;
    public function fetch()
    {
        try{
            $categories = Category::all();

            return $this->responseFormatter($this->httpCode['StatusOK'], $this->httpMessage['StatusOK'], $categories);
        }catch (Exception $error){
            return $this->responseFormatter($this->httpCode['StatusInternalServerError'], $error->getMessage(), null);
        }
    }

    public function fetchById($id)
    {
        try{
            $category = Category::find($id);

            if(!$category) {
                throw new Exception('Category not found');
            }

            return $this->responseFormatter($this->httpCode['StatusOK'], $this->httpMessage['StatusOK'], $category);
        }catch(Exception $e) {
            return $this->responseFormatter($this->httpCode['StatusInternalServerError'], $e->getMessage(), null);
        }
    }

    public function fetchMoviesByCategory($id)
    {
        try{
            $category = Category::find($id);

            if(!$category) {
                throw new Exception('Category not found');
            }

            $movies = $category->movies;

            return $this->responseFormatter($this->httpCode['StatusOK'], $this->httpMessage['StatusOK'], $movies);
        } catch(Exception $e) {
            return $this->responseFormatter($this->httpCode['StatusInternalServerError'], $e->getMessage(), null);
        }
    }

    public function store(CreateCategoryRequest $request)
    {
        try{
            $category = Category::create([
                'name' => $request->name,
                'user_id' => $request->user_id
            ]);

            if(!$category) {
                throw new Exception('Category not found');
            }

            return $this->responseFormatter($this->httpCode['StatusOK'], $this->httpMessage['StatusOK'], $category);
        }catch(Exception $e) {
            return $this->responseFormatter($this->httpCode['StatusInternalServerError'], $e->getMessage(), null);
        }
    }

    public function update(CreateCategoryRequest $request, $id)
    {
        try{
            $category = Category::find($id);

            if(!$category) {
                throw new Exception('Category not found');
            }

            $category->update([
                'name' => $request->name,
                'user_id' => $request->user_id
            ]);

            return $this->responseFormatter($this->httpCode['StatusOK'], $this->httpMessage['StatusOK'], $category);
        }catch(Exception $e) {
            return $this->responseFormatter($this->httpCode['StatusInternalServerError'], $e->getMessage(), null);
        }
    }

    public function destroy($id)
    {
        try{
            $category = Category::find($id);

            if(!$category) {
                throw new Exception('Category not found');
            }

            $category->delete();

            return $this->responseFormatter($this->httpCode['StatusOK'], $this->httpMessage['StatusOK'], $category);
        }catch(Exception $e) {
            return $this->responseFormatter($this->httpCode['StatusInternalServerError'], $e->getMessage(), null);
        }
    }
}
