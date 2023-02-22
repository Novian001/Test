<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ResponseFormatter;

    public function index(Request $request)
    {
        return $this->responseFormatterWithMeta($this->httpCode['StatusOK'], $this->httpMessage['StatusOK'], Product::orderBy('created_at', 'desc')->cursorPaginate($request->input('per_page', 15)));
    }

    public function store(CreateProductRequest $request){
        try{
            $product = Product::create([
                'name' => $request->name,
                'price' => $request->price,
                'quantity' => $request->quantity
            ]);

            if(!$product)
                return $this->errorResponseFormatter($this->httpCode['StatusInternalServerError'], $this->httpMessage['StatusInternalServerError']);

            return $this->responseFormatter($this->httpCode['StatusOK'], $this->httpMessage['StatusOK'], $product);
        }catch (Exception $error){
            return $this->errorResponseFormatter($this->httpCode['StatusInternalServerError'], $error->getMessage());
        }
    }

    public function update(UpdateProductRequest $request, $id){
        try{
            $product = Product::find($id);
            if(!$product)
                return $this->errorResponseFormatter($this->httpCode['StatusNotFound'], $this->httpMessage['StatusNotFound']);

            $product->update([
                'name' => $request->name,
                'price' => $request->price,
                'quantity' => $request->quantity
            ]);

            return $this->responseFormatter($this->httpCode['StatusOK'], $this->httpMessage['StatusOK'], $product);
        }catch (Exception $error){
            return $this->errorResponseFormatter($this->httpCode['StatusInternalServerError'], $error->getMessage());
        }
    }

    public function delete($id){
        try{
            $product = Product::find($id);
            if(!$product)
                return $this->errorResponseFormatter($this->httpCode['StatusNotFound'], $this->httpMessage['StatusNotFound']);

            $product->delete();

            return $this->responseFormatter($this->httpCode['StatusOK'], $this->httpMessage['StatusOK'], $product);
        }catch (Exception $error){
            return $this->errorResponseFormatter($this->httpCode['StatusInternalServerError'], $error->getMessage());
        }
    }
    
    public function show($id){
        try{
            $product = Product::find($id);
            if(!$product)
                return $this->errorResponseFormatter($this->httpCode['StatusNotFound'], $this->httpMessage['StatusNotFound']);

            return $this->responseFormatter($this->httpCode['StatusOK'], $this->httpMessage['StatusOK'], $product);
        }catch (Exception $error){
            return $this->errorResponseFormatter($this->httpCode['StatusInternalServerError'], $error->getMessage());
        }
    }

    public function list(){
        try{
            $product = Product::all();
            if(!$product)
                return $this->errorResponseFormatter($this->httpCode['StatusNotFound'], $this->httpMessage['StatusNotFound']);

            return $this->responseFormatter($this->httpCode['StatusOK'], $this->httpMessage['StatusOK'], $product);
        }catch (Exception $error){
            return $this->errorResponseFormatter($this->httpCode['StatusInternalServerError'], $error->getMessage());
        }
    }
}
