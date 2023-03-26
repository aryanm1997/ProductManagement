@extends('layout')


@section('content')
<div class="container-fluid">
    <div><h3 class="bg-secondary text-white">Edit Product</h3></div>
    <div class="row">
        <?php $products = $products[0]; ?>
        <form method="POST" enctype="multipart/form-data" id="product_form">
            @csrf
            <input name="_method" id="method" type="hidden" value="PUT">

        <div class="col-6">
            <div class="form-group">
                <label for="">Title</label>
                <input type="text" name="title" id="title" class="form-control" value="{{$products->title}}">
            </div>
            <div class="form-group">
                <label for="">Description</label>
                <textarea name="description" id="description" class="form-control" row="10" cols="10">{{$products->description}}</textarea>
            </div>
            <div class="form-group">
                <label for="">Image</label>
                <input type="file" name="file" id="file" class="form-control">
                @if($products->image)
                <img id="edit-image" src="/images/{{$products->image}}" class="img-thumbnail" width="50" height="50" alt="test">
                @endif
                <div class="uploadmsg"></div>
                <div class="imageshow"></div>
            </div>
            <div>
            <table class="table table-bordered" id="dynamicAddRemove"> 
            @foreach($products->variants as $variant) 
            <tr>  
            <td>
            <input type="hidden" value="{{ $variant->id }}" name="moreUpdate[{{ $variant->id }}][id]" />
            <input type="hidden" value="{{ $products->id }}" name="moreUpdate[{{ $variant->id }}][product_id]" />

                <div class="form-group">
                    <label for="">Size</label><input type="text" name="moreUpdate[{{$variant->id}}][size]" placeholder="Enter size" class="form-control" value="{{$variant->size}}" />
                </div>
            </td>  
            <td>
                <div class="form-group">
                    <label for="">Color</label><input type="text" name="moreUpdate[{{$variant->id}}][color]" placeholder="Enter color" class="form-control" value="{{$variant->color}}" />
                </div>
            </td>
            </tr>  
            @endforeach
            <tr>
                <td colspan="3"><button type="button" name="add" id="add-btn" class="btn btn-success btn-sm">Add More</button></td>
            </tr>

            </table>
            </div>
            <p id="errors"></p>
            <div><button type="button" id="updateBtn">Update</button></div>
        </div>
    </div>
</div>
<script type="text/javascript">
var i = 0;
$("#add-btn").click(function(){
++i;
$("#dynamicAddRemove").append('<tr><td><div class="form-group"><label for="">Size</label><input type="text" name="moreFields['+i+'][size]" placeholder="Enter size" class="form-control" /></div></td><td><div class="form-group"><label for="">Color</label><input type="text" name="moreFields['+i+'][color]" placeholder="Enter color" class="form-control" /></div></td><td><button type="button" class="btn btn-danger remove-tr">Remove</button></td></tr>');
});
$(document).on('click', '.remove-tr', function(){  
$(this).parents('tr').remove();
});  


$(function () { 
    $("#file").change(function(e) {
        e.preventDefault();
        $('#method').val('');
        let myform = document.getElementById("product_form");

        var formData = new FormData(myform);
        $.ajax({
        type:'POST',
        url: "{{ url('imageUpload') }}",
        data: formData,
        cache:false,
        contentType: false,
        processData: false,
        success: (result) => {
            if(result.message == "success"){
                $('.uploadmsg').text('Successfully Uploaded');
                $('.uploadmsg').removeClass('bg-danger');
                $('.uploadmsg').addClass('bg-success text-white rounded w-50 text-center');
                $('.imageshow').html(result.uploaded_image);
                $('#edit-image').hide();
            }else{
                $('.uploadmsg').text('Upload failed');
                $('.uploadmsg').removeClass('bg-success');
                $('.uploadmsg').addClass('bg-danger text-white rounded w-50 text-center');
            }
        },
        error: function(data){
        console.log(data);
        }
        });
    });   
    $('#updateBtn').on('click', (e) => {
        e.preventDefault();
        $('#method').val('PUT');
        let myform = document.getElementById("product_form");
        var formData = new FormData(myform);
        let _token = $('meta[name="csrf-token"]').attr('content');
        
        $.ajax({
            url: "{{ route('product.update', $products->id) }}",
            type: 'POST',
            contentType: 'multipart/form-data',
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            success: (response) => {
                if(response.message == "success"){
                    swal('Successfully Updated');
                    window.location.reload();
                    $('#errors').html('');
                }else{
                    $('#errors').html(response.message)
                }
                console.log(response);
            },
            error: (response) => {
                console.log(response);
            }
        });
    });


});
</script>
@endsection