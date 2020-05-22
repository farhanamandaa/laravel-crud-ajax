<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Book;
use App\Category;
Use DataTables;

class BooksController extends Controller
{
    protected function validateBook(Request $request)
    {
        $custom_messages = [
            'title.required'  =>  'Mohon isi judul buku!',
            'title.unique'    =>  'Judul buku sudah ada. Mohon pilih judul buku lain.',
            'author.required' =>  'Mohon isi nama penulis!',
            'publisher.required' => 'Mohon isi nama penerbit!',
            'release_year.required' => 'Mohon isi tahun rilis!',
            'pages.required' => 'Mohon isi jumlah halaman!',
            'quantity.required' => 'Mohon isi jumlah buku!',
            'image_link.required' => 'Mohon isi link gambar!',
            'category_id.required' => 'Mohon pilih kategori buku!',
        ];
        return $request->validate([
            'title'      =>  'required|unique:books,title,'.$request->id,  //jika ada kolom id pada request, maka validatation unique tidak berlaku (digunakan untuk update data)
            'author'    => 'required',
            'publisher'    => 'required',
            'release_year'    => 'required',
            'pages'    => 'required',
            'quantity'    => 'required',
            'image_link'    => 'required',
            'category_id'    => 'required', 
        ],$custom_messages);
    }

    public function index()
    {
        $books = Book::all();
        $categories = Category::all();
        $authors = $books->groupBy('author')->toArray();
        $publishers = $books->groupBy('publisher')->toArray();
        $release_years = $books->groupBy('release_year')->toArray();
        return view('book.index',compact('books','categories','authors','publishers','release_years'));
    }

    public function store(Request $request)
    {

        $validatedAttribute = $this->validateBook($request);
        $insert_data = Book::create($validatedAttribute);
        if($validatedAttribute AND $insert_data){
            $response = array(
                'status'    =>  'success',
                'message'   =>  'Buku berhasil ditambahkan.'
                
            );
            return $response;
        }

        return array(
            'status'    =>  'error',
            'message'   =>  'Buku gagal ditambahkan.'
        );
    }

    public function edit($id)
    {
        $book = Book::select('id','title','author','publisher','release_year','pages','quantity','image_link','category_id')
                    ->findOrFail($id);
        return $book;
    }

    public function update(Request $request)
    {
        $validatedAttribute = $this->validateBook($request);
        $update_data = Book::find($request->id)
                                ->update($validatedAttribute);
        if($validatedAttribute AND $update_data){
            $response = array(
                'status'    =>  'success',
                'message'   =>  'Buku berhasil diubah.'
                );
            return $response;
        }

        return array(
            'status'    =>  'error',
            'message'   =>  'Buku gagal diubah.'
        );
    }

    public function destroy($id)
    {
        Book::destroy($id);
        return redirect('/books');
    }

    public function getBooks(Request $request)
    {
        $books = Book::with('category:id,name');
        if($request->query())
        {
            foreach($request->query() as $column => $value)
            {
                if(!empty($value)){
                    $where[] = array($column,'=',$value);
                    $books = $books->where($where);
                }                
            }                 
            
        }
        return DataTables::of($books)
                ->addColumn('action',function($book){
                    return '
                    <button type="button" class="btn btn-warning" onclick="showBook('.$book->id.')"><i class="fa fa-edit"></i> Ubah</button>
                    <button type="button" class="btn btn-danger" onclick="deleteBook('.$book->id.')"><i class="fa fa-trash"></i> Hapus</button>
                    ';
                })
                ->make();
    }
}
