@extends('layout')

@section('content')
<section class="section">
    <div class="section-header">
      <h1>Buku</h1>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="card-header-action">
                    <button class="btn btn-primary" onclick="showCategoryForm()" type="button"><i class="fa fa-plus"></i> Tambah Buku</button>
                </div>
            </div>

            <div class="card-body">
                <form id="form-filter">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="">Penulis</label>
                            <select name="author"  class="form-control select2">
                                <option value="">--Pilih Penulis--</option>
                                @foreach ($authors as $author => $value)
                                    <option>{{$author}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="">Penerbit</label>
                            <select name="publisher"  class="form-control select2">
                                <option value="">--Pilih Penerbit--</option>
                                @foreach ($publishers as $publisher => $value)
                                    <option>{{$publisher}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="">Tahun Rilis</label>
                            <select name="release_year"  class="form-control select2">
                                <option value="">--Pilih Tahun Rilis--</option>
                                @foreach ($release_years as $release_year => $value)
                                    <option>{{$release_year}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="">Kategori Buku</label>
                            <select name="category_id"  class="form-control select2">
                                <option value="">--Pilih Kategori Buku--</option>
                                @foreach ($categories as $category)
                                    <option value="{{$category->id}}"> {{$category->name}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label for="">&nbsp;</label>
                        <button type="button" onclick="filter()" class="btn btn-info btn-block"><i class="fa fa-search"></i> Filter</button>
                    </div>
                </div>
                </form>
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-striped" id="table-buku" style="width:100%;">
                            <thead class="info">
                                <tr>
                                    <th>No</th>
                                    <th>Gambar</th>
                                    <th>Judul</th>
                                    <th>Penulis</th>
                                    <th>Penerbit</th>
                                    <th>Tahun Rilis</th>
                                    <th>Halaman</th>
                                    <th>Kuantitas</th>
                                    <th>Kategori Buku</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" id="modal-add" aria-hidden="true" style="display: none;">       
    <div class="modal-dialog modal-md" role="document">         
        <div class="modal-content">           
            <div class="modal-header">             
                <h5 class="modal-title">Tambah Buku</h5>             
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">               
                    <span aria-hidden="true">×</span>             
                </button>           
            </div>           
                <div class="modal-body"> 
                    <form action="/books" method="POST" id="form-add" autocomplete="off">          
                    <div class="form-group">
                        @csrf
                        <label for="title">Judul</label>
                        <input type="text" class="form-control" name="title" placeholder="Masukkan Judul Buku.">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label for="author">Penulis</label>
                        <input type="text" class="form-control" name="author" placeholder="Masukkan Penulis Buku.">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label for="publisher">Penerbit</label>
                        <input type="text" class="form-control" name="publisher" placeholder="Masukkan Judul Buku.">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label for="release_year">Tahun Rilis</label>
                        <input type="text" class="form-control" name="release_year" placeholder="Masukkan Judul Buku.">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label for="pages">Halaman</label>
                        <input type="text" class="form-control" name="pages" placeholder="Masukkan Judul Buku.">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Kuantitas</label>
                        <input type="number" class="form-control" name="quantity" placeholder="Masukkan Judul Buku.">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label for="image_link">Gambar</label>
                        <input type="text" class="form-control" name="image_link" placeholder="Masukkan Judul Buku.">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label for="category_id">Kategori Buku</label>
                        <select name="category_id" class="form-control">
                            <option value="">--Pilih Kategori Buku--</option>
                            @foreach ($categories as $category)
                                <option value="{{$category->id}}"> {{$category->name}} </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" id="btn-add">Submit</button>    
                </div>         
                </form>
            </div>       
        </div>    
    </div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" id="modal-edit" aria-hidden="true" style="display: none;">       
    <div class="modal-dialog modal-md" role="document">         
        <div class="modal-content">           
            <div class="modal-header">             
                <h5 class="modal-title">Ubah Kategori</h5>             
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">               
                    <span aria-hidden="true">×</span>             
                </button>           
            </div>           
                <div class="modal-body"> 
                    <form action="/books/edit" method="POST" id="form-edit" autocomplete="off">          
                        <div class="form-group">
                            @csrf
                            <label for="title">Judul</label>
                            <input type="text" class="form-control" name="title" placeholder="Masukkan Judul Buku.">
                            <div class="invalid-feedback"></div>
                            <input name="id" type="hidden">
                        </div>
                        <div class="form-group">
                            <label for="author">Penulis</label>
                            <input type="text" class="form-control" name="author" placeholder="Masukkan Penulis Buku.">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label for="publisher">Penerbit</label>
                            <input type="text" class="form-control" name="publisher" placeholder="Masukkan Judul Buku.">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label for="release_year">Tahun Rilis</label>
                            <input type="text" class="form-control" name="release_year" placeholder="Masukkan Judul Buku.">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label for="pages">Halaman</label>
                            <input type="text" class="form-control" name="pages" placeholder="Masukkan Judul Buku.">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label for="quantity">Kuantitas</label>
                            <input type="number" class="form-control" name="quantity" placeholder="Masukkan Judul Buku.">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label for="image_link">Gambar</label>
                            <input type="text" class="form-control" name="image_link" placeholder="Masukkan Judul Buku.">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label for="category_id">Kategori Buku</label>
                            <select name="category_id"  class="form-control">
                                <option value="">--Pilih Kategori Buku--</option>
                                @foreach ($categories as $category)
                                    <option value="{{$category->id}}"> {{$category->name}} </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" id="btn-edit">Submit</button>    
                </div>         
                </form>
            </div>       
        </div>    
    </div>
</div>
<script>
    var t =  $("#table-buku").DataTable({  //inisiasi datatable
        initComplete: function() {
            var api = this.api();
            $('#table-buku_filter input')
            .off('.DT')
            .on('keyup.DT', function(e) {
                if (e.keyCode == 13) { //jika klik enter di kolom search, maka akan lakukan pencarian
                    api.search(this.value).draw();
                }
            });
        },
        processing : true,
        serverside : true, //mengambil data secara serverside (request ajax)
        ajax : {
            url : `{{route('ajax.books')}}`, //link yg digunakan sebagai sumber data
            cache : true,
        }, 
        columns: [
            {data: 'id'},
            {data: 'image_link'},
            {data: 'title'},
            {data: 'author'},
            {data: 'publisher'},
            {data: 'release_year'},
            {data: 'pages'},
            {data: 'quantity'},
            {data: 'category.name'},
            {data: 'action'},
        ],
        columnDefs: [
            {
                'targets':[1],
                'render': function(data,type,row){
                    var html = `<a target='blank' href='${data}'><img src='${data}' style="width:75px;"></img></a>`
                    return html
                }
            }
        ],
        rowCallback: function(row, data, displayNum, displayIndex, dataIndex) {  // generate nomor di table
            var number = displayNum + 1
            $('td:eq(0)', row).html(number);
        }
    })

    function refreshData(param='')
    {
        var url = `{{route('ajax.books')}}`
        if(param!='')
        {
            url += `?${param}`
        }
        t.ajax.url(url).load()
    }

    function showCategoryForm()
    {
        $(`#form-add`)
            .find(`input.form-control,select.form-control`)
            .removeClass('is-invalid')
            .val('').change()
            .next(`.invalid-feedback`)
            .html('')
        $('#modal-add').modal()
    }

    $("#form-add").submit(function(event){
        event.preventDefault()
        var form = $(this);
        var mydata = new FormData(this);
        $.ajax({
            type: "POST",
            url : '/books',
            data : mydata,
            processData: false,
            contentType: false,
            beforeSend : function(){
                $("#btn-add").addClass('btn-progress').attr('disabled',true)
                $(`#form-add`)
                    .find(`input.form-control,select.form-control`)
                    .removeClass('is-invalid')
                    .next(`.invalid-feedback`)
                    .html('')
            },
            success: function(response, textStatus, xhr) {
                refreshData()
                $("#btn-add").removeClass('btn-progress').attr('disabled',false)
                $("#modal-add").modal('hide')
                $("input[name='name']").val('').change()
                Swal.fire({
                    icon: 'success',
                    title: 'Perhatian',
                    text: response.message,
                    timer: 2000
                })
            },
            error: function(xhr, textStatus, errorThrown) {
                if(xhr.status == 422){
                    var errors = xhr.responseJSON.errors
                    for(error in errors){
                        var error_message = errors[error][0]
                        console.log(error_message)
                        $(`#form-add`)
                            .find(`input[name=${error}],select[name=${error}]`)
                            .addClass('is-invalid')
                            .next(`.invalid-feedback`)
                            .html(error_message)
                    }
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan pada sistem. Coba lagi beberapa saat lagi!',
                    })
                }
                $("#btn-add").removeClass('btn-progress').attr('disabled',false)
            }
        })
    })

    function showBook(id)
    {
        $(`#form-edit`)
            .find(`input.form-control,select.form-control`)
            .removeClass('is-invalid')
            .next(`.invalid-feedback`)
            .html('')
        $.get(`/books/edit/${id}`)
            .done(function(book){
                for(data in book)
                {
                    var value = book[data]
                    $(`#form-edit input[name='${data}'],select[name='${data}']`).val(value).change()
                    $("#modal-edit").modal()
                }
            })
            .fail(function(){
                Swal.fire(
                        'Ooops!',
                        'Terjadi kesalahan saat menerima data.',
                        'error'
                    )
            })
    }

    $("#form-edit").submit(function(event){
        event.preventDefault()
        var form = $(this);
        var mydata = new FormData(this);
        $.ajax({
            type: "POST",
            url : '/books/edit',
            data : mydata,
            processData: false,
            contentType: false,
            beforeSend : function(){
                $("#btn-edit").addClass('btn-progress').attr('disabled',true)
                $(`#form-edit`)
                    .find(`input.form-control,select.form-control`)
                    .removeClass('is-invalid')
                    .next(`.invalid-feedback`)
                    .html('')
            },
            success: function(response, textStatus, xhr) {
                refreshData()
                $("#btn-edit").removeClass('btn-progress').attr('disabled',false)
                $("#modal-edit").modal('hide')
                Swal.fire({
                    icon: 'success',
                    title: 'Perhatian',
                    text: response.message,
                    timer: 2000
                })
            },
            error: function(xhr, textStatus, errorThrown) {
                if(xhr.status == 422){ // jika input tidak memenuhi validasi yang ada.
                    var errors = xhr.responseJSON.errors
                    for(error in errors){
                        var error_message = errors[error][0]
                        $(`#form-edit`)
                            .find(`input[name=${error}],select[name=${error}]`)
                            .addClass('is-invalid')
                            .next(`.invalid-feedback`)
                            .html(error_message)                                                    
                    }
                }else{
                    $("#btn-edit").removeClass('btn-progress').attr('disabled',false)
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan pada sistem. Coba lagi beberapa saat lagi!',
                    })
                }
                $("#btn-edit").removeClass('btn-progress').attr('disabled',false)  
            }
        })
    })

    function deleteBook(id)
    {
        Swal.fire({
            title: 'Apakah anda yakin akan menghapus buku ini?',
            text: "Data yang telah dihapus tidak bisa dikembalikan lagi!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya!',
            cancelButtonText: 'Tidak!'
            }).then((result) => {
            if (result.value) {
                $.get(`/books/delete/${id}`,function(){
                    refreshData()
                    Swal.fire(
                        'Deleted!',
                        'Buku berhasi dihapus.',
                        'success'
                    )
                })  
                
            }
        })
    }

    function filter()
    {
        var parameter = $("#form-filter").serialize()
        refreshData(parameter)
    }
</script>
@endsection('content')