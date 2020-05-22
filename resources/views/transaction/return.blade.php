@extends('layout')

@section('content')
<section class="section">
    <div class="section-header">
      <h1>Peminjaman</h1>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="float-right">
                    {{-- <button class="btn btn-primary" onclick="showBorrowForm()" type="button"><i class="fa fa-plus"></i> Tambah Peminjaman</button> --}}
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="table-peminjaman" style="width:100%;">
                        <thead class="info">
                            <tr>
                                <th>No</th>
                                <th>Nama Buku</th>
                                <th>Peminjam</th>
                                <th>Kuantitas</th>
                                <th>Tanggal Peminjaman</th>
                                <th>Status Pengembalian</th>
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
    var t =  $("#table-peminjaman").DataTable({  //inisiasi datatable
        initComplete: function() {
            var api = this.api();
            $('#table-peminjaman input')
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
            url : `{{route('ajax.returns')}}`, //link yg digunakan sebagai sumber data
            cache : true,
        }, 
        columns: [
            {data: 'id'},
            {data: 'book.title'},
            {data: 'borrower_name'},
            {data: 'quantity'},
            {data: 'borrow_at'},
            {data: 'return_at'},
            {data: 'action'},

        ],
        columnDefs: [
            {
                'targets':[5],
                'render': function(data,type,row){
                    var status = 'Sudah Dikembalikan';
                    if(data == null) status = 'Belum Dikembalikan';
                    return status
                }
            }
        ],
        rowCallback: function(row, data, displayNum, displayIndex, dataIndex) {  // generate nomor di table
            var number = displayNum + 1
            $('td:eq(0)', row).html(number);
        }
    })

    function refreshData()
    {
        var url = `{{route('ajax.returns')}}`
        t.ajax.url(url).load()
    }

    function showBorrowForm()
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
            url : '/transaction/borrow',
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

    function returnBook(id)
    {
        Swal.fire({
            title: 'Apakah anda yakin akan mengembalikan buku ini?',
            text: "Data yang telah dihapus tidak bisa dikembalikan lagi!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya!',
            cancelButtonText: 'Tidak!'
            }).then((result) => {
            if (result.value) {
                $.get(`/transaction/return/${id}`,function(){
                    refreshData()
                    Swal.fire(
                        'Deleted!',
                        'Buku berhasi dikembalikan.',
                        'success'
                    )
                })  
                
            }
        })
    }

</script>
@endsection
