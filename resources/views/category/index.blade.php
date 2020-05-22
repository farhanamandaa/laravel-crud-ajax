@extends('layout')

@section('content')
<section class="section">
    <div class="section-header">
      <h1>Kategori</h1>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="float-right">
                    <button class="btn btn-primary" onclick="showCategoryForm()" type="button"><i class="fa fa-plus"></i> Tambah Kategori</button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="table-kategori" style="width:100%;">
                        <thead class="info">
                            <tr>
                                <th>No</th>
                                <th>Nama Kategori</th>
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
<div class="modal fade" tabindex="-1" role="dialog" id="modal-add" aria-hidden="true" style="display: none;">       
    <div class="modal-dialog modal-md" role="document">         
        <div class="modal-content">           
            <div class="modal-header">             
                <h5 class="modal-title">Tambah Kategori</h5>             
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">               
                    <span aria-hidden="true">×</span>             
                </button>           
            </div>           
                <div class="modal-body"> 
                    <form action="/categories" method="POST" id="form-add" autocomplete="off">          
                    <div class="form-group">
                        @csrf
                        <label for="name">Nama Kategori</label>
                        <input type="text" class="form-control" name="name" placeholder="Masukkan nama kategori.">
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
                    <form action="/categories/edit" method="POST" id="form-edit" autocomplete="off">          
                    <div class="form-group">
                        @csrf
                        <label for="name">Nama Kategori</label>
                        <input type="text" class="form-control" name="name" placeholder="Masukkan nama kategori.">
                        <div class="invalid-feedback"></div>
                        <input type="hidden" name="id">
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
    var t =  $("#table-kategori").DataTable({  //inisiasi datatable
        initComplete: function() {
            var api = this.api();
            $('#table-kategori_filter input')
            .off('.DT')
            .on('keyup.DT', function(e) {
                if (e.keyCode == 13) { //jika klik enter di kolom search, maka akan lakukan pencarian
                    api.search(this.value).draw();
                }
            });
        },
        processing : true,
        serverside : true, //mengambil data secara serverside (request ajax)
        ajax : `{{route('ajax.categories')}}`, //link yg digunakan sebagai sumber data
        columns: [
            {data: 'id'},
            {data: 'name'},
            {data: 'action'},
        ],
        rowCallback: function(row, data, displayNum, displayIndex, dataIndex) {  // generate nomor di table
            var number = displayNum + 1
            $('td:eq(0)', row).html(number);
        }
    })

    function refreshData()
    {
        t.ajax.url(`{{route('ajax.categories')}}`).load()
    }

    function showCategoryForm()
    {
        $(`#form-add`)
            .find(`input.form-control`)
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
            url : '/categories',
            data : mydata,
            processData: false,
            contentType: false,
            beforeSend : function(){
                $("#btn-add").addClass('btn-progress').attr('disabled',true)
                $(`#form-add`)
                    .find(`input.form-control`)
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
                        $(`#form-add`)
                            .find(`input.form-control`)
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

    function showCategory(id)
    {
        $.get(`/categories/edit/${id}`)
            .done(function(data){
                $("#form-edit input[name='name']").val(data.name).change()
                $("#form-edit input[name='id']").val(data.id).change()
                $("#modal-edit").modal()
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
            url : '/categories/edit',
            data : mydata,
            processData: false,
            contentType: false,
            beforeSend : function(){
                $("#btn-edit").addClass('btn-progress').attr('disabled',true)
                $(`#form-edit`)
                    .find(`input.form-control`)
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
                if(xhr.status == 422){
                    var errors = xhr.responseJSON.errors
                    for(error in errors){
                        var error_message = errors[error][0]
                        $(`#form-edit`)
                            .find(`input[name=${error}]`)
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

    function deleteCategory(id)
    {
        Swal.fire({
            title: 'Apakah anda yakin akan menghapus kategori ini?',
            text: "Data yang telah dihapus tidak bisa dikembalikan lagi!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya!',
            cancelButtonText: 'Tidak!'
            }).then((result) => {
            if (result.value) { //jika user klik tombol ya
                $.get(`/categories/delete/${id}`,function(){
                    refreshData()
                    Swal.fire(
                        'Deleted!',
                        'Kategori berhasi dihapus.',
                        'success'
                    )
                })  
                
            }
        })
    }
</script>
@endsection
