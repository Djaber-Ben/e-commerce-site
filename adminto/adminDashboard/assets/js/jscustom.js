/// 

$(document).ready(function () {

    $('.delete_message_btn').click(function (e) {
        e.preventDefault();
  
        var id = $(this).val();  // Use val() to get the value from the button
        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    method: "POST",
                    url: "code.php",
                    data: {
                        'message_id': id,
                        'delete_message_btn': true,
                    },
                    success: function (response) {
                        console.log(response);
                        if (response == 200) {
                            swal("Good job!", "message deleted successfully", "success");
                            $("#message_table").load(location.href + " #message_table");
                        } else if (response == 500) {
                            swal("Error!", "Something went wrong", "error");
                        }
                    }
                });
            }
        });
    });

    $('.delete_product_btn').click(function (e) {
        e.preventDefault();
  
        var id = $(this).val();  // Use val() to get the value from the button
        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    method: "POST",
                    url: "code.php",
                    data: {
                        'product_id': id,
                        'delete_product_btn': true,
                    },
                    success: function (response) {
                        console.log(response);
                        if (response == 200) {
                            swal("Good job!", "Product deleted successfully", "success");
                            $("#product_table").load(location.href + " #product_table");
                        } else if (response == 500) {
                            swal("Error!", "Something went wrong", "error");
                        }
                    }
                });
            }
        });
    });
  
    $('.delete_category_btn').click(function (e) {
        e.preventDefault();
  
        var id = $(this).val();  // Use val() to get the value from the button
        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    method: "POST",
                    url: "code.php",
                    data: {
                        'category_id': id,
                        'delete_category_btn': true,
                    },
                    success: function (response) {
                        console.log(response);
                        if (response == 200) {
                            swal("Good job!", "Category deleted successfully", "success");
                            $("#category_table").load(location.href + " #category_table");
                        } else if (response == 500) {
                            swal("Error!", "Something went wrong", "error");
                        }
                    }
                });
            }
        });
    });

    
    
    
  });
  