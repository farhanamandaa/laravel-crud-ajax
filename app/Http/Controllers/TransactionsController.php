<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transaction;
use App\Book;
Use DataTables;

class TransactionsController extends Controller
{
    protected function validateBorrow(Request $request)
    {
        $custom_messages = [
            'borrower_name.required'  =>  'Mohon isi nama peminjam!',
            'quantity.required'    =>  'Mohon isi jumlah buku yang dipinjam!',
            'book_id.required'      => 'Mohon pilih buku yang akan dipinjam!'
        ];
        return $request->validate([
            'borrower_name'      =>  'required',
            'quantity'           =>  'required',
            'book_id'            =>  'required' 
        ],$custom_messages);
    }

    public function borrow()
    {
        $books = Book::all();
        return view('transaction.borrow',compact('books'));
    }

    public function return()
    {
        return view('transaction.return');
    }

    public function store(Request $request)
    {

        $validatedAttribute = $this->validateBorrow($request);
        $validatedAttribute['borrow_at'] = date('Y-m-d H:i:s');
        $insert_data = Transaction::create($validatedAttribute);
        if($validatedAttribute AND $insert_data){
            $response = array(
                'status'    =>  'success',
                'message'   =>  'Transaksi berhasil ditambahkan.'
                
            );
            return $response;
        }

        return array(
            'status'    =>  'error',
            'message'   =>  'Transaksi gagal ditambahkan.'
        );
    }

    public function update(Request $request,$id)
    {
        $validatedAttribute = array('return_at'=>date('Y-m-d H:i:s'));
        $update_data = Transaction::find($id)
                                ->update($validatedAttribute);
        if($validatedAttribute AND $update_data){
            $response = array(
                'status'    =>  'success',
                'message'   =>  'Kategori berhasil diubah.'
                );
            return $response;
        }

        return array(
            'status'    =>  'error',
            'message'   =>  'Kategori gagal diubah.'
        );
    }

    public function getBorrows(Request $request)
    {
        $borrows = Transaction::with('book:id,title');
        return DataTables::of($borrows)->make();
    }

    public function getReturns(Request $request)
    {
        $borrows = Transaction::with('book:id,title');
        return DataTables::of($borrows)
        ->addColumn('action',function($borrow){
            $html ='';
            if($borrow->return_at == NULL){
                $html = '<button type="button" class="btn btn-warning" onclick="returnBook('.$borrow->id.')"><i class="fa fa-edit"></i> Kembalikan</button>';
            }
            return $html;
        })                    
        ->make();
    }
}
