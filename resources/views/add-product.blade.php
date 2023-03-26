@extends('layout')


@section('content')
<div class="container-fluid">
    <div><h3 class="bg-secondary text-white">Add Product</h3></div>
    <div class="row">
        <form method="POST" enctype="multipart/form-data" id="product_form">
            @csrf
        <div class="col-6">
            <div class="form-group">
                <label for="">Title</label>
                <input type="text" name="title" id="title" class="form-control">
            </div>
            <div class="form-group">
                <label for="">Description</label>
                <textarea name="description" id="description" class="form-control" row="10" cols="10"></textarea>
            </div>
            <div class="form-group">
                <label for="">Image</label>
                <input type="file" name="file" id="file" class="form-control">
                <div class="uploadmsg"></div>
                <div class="imageshow"></div>
            </div>
            <div>
            <table class="table table-bordered" id="dynamicAddRemove">  
            <tr>  
            <td>
                <div class="form-group">
                    <label for="">Size</label><input type="text" name="moreFields[0][size]" placeholder="Enter size" class="form-control" />
                </div>
            </td>  
            <td>
                <div class="form-group">
                    <label for="">Color</label><input type="text" name="moreFields[0][color]" placeholder="Enter color" class="form-control" />
                </div>
            </td> 
            <td><button type="button" name="add" id="add-btn" class="btn btn-success btn-sm">Add More</button></td>  
            </tr>  
            </table>
            </div>
            <p id="errors"></p>
            <div><button type="button" id="submitBtn">Submit</button></div>
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
        let myform = document.getElementById("product_form");

        var formData = new FormData(myform);
        $.ajax({
        type:'POST',
        url: "{{ url('imageUpload')}}",
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
    $('#submitBtn').on('click', (e) => {
        e.preventDefault();
        let myform = document.getElementById("product_form");
        var formData = new FormData(myform);
        let _token = $('meta[name="csrf-token"]').attr('content');
        
        $.ajax({
            url: '/product',
            type: 'POST',
            contentType: 'multipart/form-data',
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            success: (response) => {
                // success
                if(response.message == "success"){
                    $('#product_form')[0].reset();
                    swal('Successfully Added');
                    $('#errors').html('');
                    $('.imageshow').html('');
                    $('.uploadmsg').hide()
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