<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTransactionRequest;
use App\Models\Transaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    use ResponseFormatter;
    
    public function index(Request $request)
    {
        return $this->responseFormatterWithMeta($this->httpCode['StatusOK'], $this->httpMessage['StatusOK'], Transaction::orderBy('created_at', 'desc')->cursorPaginate($request->input('per_page', 15)));
    }

    public function store(CreateTransactionRequest $request){
        try{
            if(!Auth::user()->role == 'customer')
                return $this->errorResponseFormatter($this->httpCode['StatusUnauthorized'], $this->httpMessage['StatusUnauthorized']);
            
            $transaction = Transaction::create([
                'user_id' => $request->user_id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'total_price' => $request->total_price
            ]);

            if(!$transaction)
                return $this->errorResponseFormatter($this->httpCode['StatusInternalServerError'], $this->httpMessage['StatusInternalServerError']);

            return $this->responseFormatter($this->httpCode['StatusOK'], $this->httpMessage['StatusOK'], $transaction);
        }catch (Exception $error){
            return $this->errorResponseFormatter($this->httpCode['StatusInternalServerError'], $error->getMessage());
        }
    }

    public function list(){
        try{
            if(!Auth::user()->role == 'admin')
             return $this->responseFormatterWithMeta($this->httpCode['StatusOK'], $this->httpMessage['StatusOK'], Transaction::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->cursorPaginate(15));
            
            $product = Transaction::all();
            if(!$product)
                return $this->errorResponseFormatter($this->httpCode['StatusNotFound'], $this->httpMessage['StatusNotFound']);

            return $this->responseFormatter($this->httpCode['StatusOK'], $this->httpMessage['StatusOK'], $product);
        }catch (Exception $error){
            return $this->errorResponseFormatter($this->httpCode['StatusInternalServerError'], $error->getMessage());
        }
    }

    public function getDetailTransaction(Request $request, $uuid){
        try{
            $user = $request->user();
            $transaction = Transaction::where('uuid', $uuid)->where('user_id', $user->id)->first();
            if(!$transaction)
                return $this->errorResponseFormatter($this->httpCode['StatusNotFound'], $this->httpMessage['StatusNotFound']);

            return $this->responseFormatter($this->httpCode['StatusOK'], $this->httpMessage['StatusOK'], $transaction);
        }catch (Exception $error){
            return $this->errorResponseFormatter($this->httpCode['StatusInternalServerError'], $error->getMessage());
        }
    }
}
