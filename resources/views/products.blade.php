<!DOCTYPE html>
<html lang="en">
<head>
    <title>Bootstrap Example</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
</head>
<body>

<div class="container">
    <h2>Products</h2>
    <form  name="product-form" id="product-form" method="post" action="javascript:void(0)">
        @csrf
        <div class="form-group">
            <label for="p_name">Product Name:</label>
            <input autofocus required type="text" class="form-control" id="p_name" placeholder="xx shoes" name="p_name">
        </div>
        <div class="form-group">
            <label for="quantity">Quantity in stock:</label>
            <input required type="number" class="form-control" id="quantity" placeholder="3" name="quantity" pattern="[0-9]">
        </div>
        <div class="form-group">
            <label for="price">Price per item</label>
            <input required type="number" class="form-control" id="price" placeholder="200" name="price">
        </div>
        <button type="submit" id="submit" class="btn  btn-primary mb-2">Submit</button>
    </form>

    <div class="mt-20 card">
        <p id="dump"></p>
        <table class="table table-striped" id="result">
            <thead class="thead-light">
                <tr>
                    <td>Product Name</td>
                    <td>Quantity</td>
                    <td>Price</td>
                    <td>Date Submitted</td>
                    <td>Total Value Number</td>
                </tr>
            </thead>
            <tbody id="tablebody">

            </tbody>
        </table>
    </div>
</div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js" integrity="sha512-+NqPlbbtM1QqiK8ZAo4Yrj2c4lNQoGv8P79DPtKzj++l5jnN39rHA/xsqn8zE9l0uSoxaCdrOgFs6yjyfbBxSg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>

    <script type="text/javascript">
        if ($('#product-form').length > 0) {
            $('#product-form').validate({
                rules:{
                    p_name: {
                        required: true
                    },
                    quantity: {
                        required: true

                    },
                    price: {
                        required: true
                    }
                },
                messages:{
                    p_name: {
                        required: "Please enter a product name."
                    },
                    quantity: {
                        required: "Please enter a quantity for the product."

                    },
                    price: {
                        required: "Please enter a price for the product."
                    }
                },
                submitHandler: function (form) {
                    $.ajaxSetup({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
                    });

                    $('#submit').html('Please Wait...');
                    $("#submit"). attr("disabled", true);

                    $.ajax({
                        url: "{{route('post.products')}}",
                        type: "POST",
                        data: $('#product-form').serialize(),
                        success: function (response) {
                            $('#submit').html('Submit');
                            $("#submit"). attr("disabled", false);
                            // console.log(response);
                            // $('#dump').html(response);
                            // console.log(response.result);
                            // console.log(response.total);
                            var result = $.parseJSON(response.result);
                            $("#tablebody").empty();
                            $(function() {
                                $.each(result, function(i, item) {
                                    $('<tr>').append(
                                        $('<td>').text(item.p_name),
                                        $('<td>').text(item.quantity),
                                        $('<td>').text(item.price),
                                        $('<td>').text(item.date),
                                        $('<td>').text(item.total)
                                    ).appendTo('#tablebody');
                                });

                                $('<tr>').append(
                                    $('<td>').text("SUM TOTAL"),
                                    $('<td>').text(""),
                                    $('<td>').text(""),
                                    $('<td>').text(""),
                                    $('<td>').text(response.total)
                                ).appendTo('#tablebody');
                            });
                            document.getElementById("product-form").reset();
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            $('#submit').html('Submit');
                            $("#submit"). attr("disabled", false);
                            $('#dump').html(XMLHttpRequest.data);
                        }
                    });

                }
            })
        }
    </script>
</body>
</html>