<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
Use DataTables;

class CategoriesController extends Controller
{

    protected function validateCategory(Request $request)
    {
        $custom_messages = [
            'required'  =>  'Mohon isi nama kategori!',
            'unique'    =>  'Nama kategori sudah ada. Mohon pilih nama kategori lain.'
        ];
        return $request->validate([
            'name'      =>  'required|unique:categories,name', 
        ],$custom_messages);
    }

    public function index()
    {
        $categories = Category::all();
        return view('category.index',compact('categories'));
    }

    public function store(Request $request)
    {

        $validatedAttribute = $this->validateCategory($request);
        $insert_data = Category::create($validatedAttribute);
        if($validatedAttribute AND $insert_data){
            $response = array(
                'status'    =>  'success',
                'message'   =>  'Kategori berhasil ditambahkan.'
                
            );
            return $response;
        }

        return array(
            'status'    =>  'error',
            'message'   =>  'Kategori gagal ditambahkan.'
        );
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return $category;
    }

    public function update(Request $request)
    {
        $validatedAttribute = $this->validateCategory($request);
        $update_data = Category::find($request->id)
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

    public function destroy($id)
    {
        Category::destroy($id);
        return redirect('/categories');
    }
    
    public function getCategories()
    {
        $categories = Category::all();
        return DataTables::of($categories)
                ->addColumn('action',function($category){
                    return '
                    <button type="button" class="btn btn-warning" onclick="showCategory('.$category->id.')"><i class="fa fa-edit"></i> Ubah</button>
                    <button type="button" class="btn btn-danger" onclick="deleteCategory('.$category->id.')"><i class="fa fa-trash"></i> Hapus</button>
                    ';
                })
                ->make();
    }
}
