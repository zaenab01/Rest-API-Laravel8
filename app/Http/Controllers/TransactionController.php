<?php

namespace App\Http\Controllers;
use App\Http\Controllers\TransactionController;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //mengambil data transaction, lalu diurutkan 
        $transaction = Transaction::orderBy('time', 'DESC')->get();
        //menyimpan data yang ditampilkan di array response
        $response =[
            'massage' => 'List transaction order by time',
            'data' => $transaction
        ];

        return response()->json($response, 200);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   //membuat aturan input user apa saja
        $validator = Validator::make($request->all(),[
            'title' => ['required'],
            'amount' => ['required', 'numeric'],
            'type' => ['required', 'in:expense,revenue']
        ]);
         //jika tidak sesuai aturan validasi maka eror 422
        if ($validator -> fails()){
            return response()->json($validator->errors(), 422);
        }

        try{
            $transaction = Transaction::create($request->all());
            $response =[
                'message' => 'Transaction created',
                'data' => $transaction
            ];
            return response()->json($response, 201); //201 artinya request berhasil dibuat

        }catch(QueryException $e){
            return response()->json([
                'message' => "Failed" . $e->errorInfo
            ]);

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transaction = Transaction::findOrFail($id);

        $response =[
            'massage' => 'Detail transaction resource',
            'data' => $transaction
        ];

        return response()->json($response, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);
          //membuat aturan input user apa saja
          $validator = Validator::make($request->all(),[
            'title' => ['required'],
            'amount' => ['required', 'numeric'],
            'type' => ['required', 'in:expense,revenue']
        ]);
         //jika tidak sesuai aturan validasi maka eror 422
        if ($validator -> fails()){
            return response()->json($validator->errors(), 422);
        }

        try{
            $transaction->update($request->all());
            $response =[
                'message' => 'Transaction updated ',
                'data' => $transaction
            ];
            return response()->json($response, 200 ); //201 artinya request berhasil dibuat

        }catch (QueryException $e){
            return response()->json([
                'message' => "Failed" . $e->errorInfo
            ]);

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        //membuat aturan input user apa saja

      try{
          $transaction->delete();
          $response =[
              'message' => 'Transaction deleted ',
          ];
          return response()->json($response, 200 ); //201 artinya request berhasil dibuat

      }catch (QueryException $e){
          return response()->json([
              'message' => "Failed" . $e->errorInfo
          ]);

      }
    }
}
