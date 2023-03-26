@extends('layout')


@section('content')
<div class="container-fluid">
    <h2 class="bg-secondary text-white">Products</h2>
    <div>
        <a href="product/create" class="btn btn-primary">Add</a>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Id</th>
                <th>Title</th>
                <th>Description</th>
                <th>Image</th>
                <th>Variants</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if(!$products->isEmpty())
            @foreach($products as $product)
            <tr>
                <input type="hidden" name="" id="product_id" value="{{$product->id}}">
                <td>{{$product->image}}</td>
                <td>{{$product->title}}</td>
                <td>{{$product->description}}</td>
                <td><img src="/images/{{$product->image}}" class="img-thumbnail" width="50" height="50" alt="test"></td>
                <td>
                    @foreach($product->variants as $variant)
                    <p>Size - {{$variant->size}}, Color - {{$variant->color}}</p>
                    @endforeach
                </td>
                <td class="">
                    <a href="product/{{$product->id}}/edit" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>
                    <a 
                        href="javascript:void(0)" 
                        id="delete-user" 
                        data-url="{{ url('/product', $product->id) }}" 
                        class="btn btn-danger btn-sm"
                        ><i class="fa fa-trash"></i></a>
                </td>

            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="6">No Data Found</td>
            </tr>
            @endif
        </tbody>
    </table>
</div>
<script type="text/javascript">
      
    $(document).ready(function () {
   
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('body').on('click', '#delete-user', function () {
  
          var userURL = $(this).data('url');
          var trObj = $(this);
          var id = $('#product_id').val();
          swal({
            title: "Are you sure you want to remove this Product?",
            text: "You will not be able to recover this Product!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Yes, I am sure!',
            cancelButtonText: "No, cancel it!",
            closeOnConfirm: false,
            closeOnCancel: false
         }).then((confirm) => {

           if (confirm){
            console.log('test');
                $.ajax({
                    url: userURL,
                    type: 'DELETE',
                    dataType: 'JSON',
                    data:{
                        'id': id,
                        '_token': '{{ csrf_token() }}',
                    },
                    success: function(result) {
                        window.location.reload();
                    }
                });

           }else{
                swal('Failed to delete');
           }
        });
  
       });
        
    });
    
</script>
@endsection