$( document ).ready(function() {

    var page = 1;
    var current_page = 1;
    var total_page = 0;

    var limit = 7;
    var offset = 0;
    var sortField='id';
    var sortType='asc';
    var total = 0;
    var is_ajax_fire = 0;

    manageData();

    /* manage data list */
    function manageData() {
        $.ajax({
            dataType: 'json',
            url: url+'api/list',
            data: {
                limit:limit,
                offset: offset,
                orderField: sortField,
                orderType: sortType
            }
        }).done(function(data){
            total = data.total;
            total_page = Math.ceil(total/limit);
            current_page = total_page == 0 ? 0 : page ;

            $('#pagination').twbsPagination({
                totalPages: total_page,
                visiblePages: current_page,
                onPageClick: function (event, pageL) {
                    page = pageL;
                    offset = (pageL-1) * limit;

                    if(is_ajax_fire != 0){
                        getPageData();
                    }
                }
            });

            manageRow(data.products);
            is_ajax_fire = 1;

        });

    }

    /* Get Page Data*/
    function getPageData() {
        $.ajax({
            dataType: 'json',
            url: url+'api/list',
            data: {
                limit:limit,
                offset: offset,
                orderField: sortField,
                orderType: sortType
            }
        }).done(function(data){
            total = data.total;
            total_page = Math.ceil(total/limit);
            current_page = page;

            manageRow(data.products);
        });
    }

    /* Add new Item table row */
    function manageRow(data) {
        var	rows = '';
        $.each( data, function( key, value ) {
            rows = rows + '<tr>';
            rows = rows + '<td>'+value.id+'</td>';
            rows = rows + '<td>'+value.name+'</td>';
            rows = rows + '<td>'+value.description+'</td>';
            rows = rows + '<td>'+value.price+'</td>';
            rows = rows + '<td>'+value.url+'</td>';
            rows = rows + '<td data-id="'+value.id+'">';
            rows = rows + '<button data-toggle="modal" data-target="#edit-item" class="btn btn-primary edit-item">Edit</button> ';
            rows = rows + '<button class="btn btn-danger remove-item">Delete</button>';
            rows = rows + '</td>';
            rows = rows + '</tr>';
        });

        $("tbody").html(rows);
    }

    /* Create new Item */
    $(".crud-submit").click(function(e){
        e.preventDefault();
        var form_action = $("#create-item").find("form").attr("action");

        var name = $("#create-item").find("input[name='name']").val();
        var description = $("#create-item").find("textarea[name='description']").val();
        var price = $("#create-item").find("input[name='price']").val();
        var imageUrl = $("#create-item").find("input[name='url']").val();

        if(name != '' && !isNaN(price)){
            $.ajax({
                dataType: 'json',
                type:'POST',
                url: url + form_action,
                data:{
                      name: name,
                      description: description,
                      price: price,
                      url: imageUrl,
                      limit:limit,
                      offset: offset,
                      orderField: sortField,
                      orderType: sortType
                }
            }).done(function(data){
                //clear all text in create form
                $("#create-item").find("input[name='id']").val('');
                $("#create-item").find("input[name='name']").val('');
                $("#create-item").find("textarea[name='description']").val('');
                $("#create-item").find("input[name='price']").val('');
                $("#create-item").find("input[name='url']").val('');

                var previuosTotal =total;
                total++;
                total_page = Math.ceil(total/limit);

                if (previuosTotal>0) {
                    $('#pagination').twbsPagination('destroy');


                    $('#pagination').twbsPagination({
                        totalPages: total_page,
                        visiblePages: current_page,
                        onPageClick: function (event, pageL) {
                            page = pageL;
                            offset = (pageL - 1) * limit;

                            if (is_ajax_fire != 0) {
                                getPageData();
                            }
                        }
                    });
                }
                else
                {
                    manageData();
                }

                $(".modal").modal('hide');
                toastr.success('Item Created Successfully.', 'Success Alert', {timeOut: 5000});
            });
        }else{
            alert('You are missing name or price.')
        }
    });

    /* Remove Item */
    $("body").on("click",".remove-item",function(){
        var id = $(this).parent("td").data('id');
        var c_obj = $(this).parents("tr");

        $.ajax({
            dataType: 'json',
            type:'DELETE',
            url: url + 'api/delete/' + id,
            data:{
                limit:limit,
                offset: offset,
                orderField: sortField,
                orderType: sortType
            }
        }).done(function(data){
            //c_obj.remove();
            toastr.success('Item Deleted Successfully.', 'Success Alert', {timeOut: 5000});

            total--;
            total_page = Math.ceil(total/limit);
            current_page = current_page>total_page ? total_page: current_page;


            $('#pagination').twbsPagination('destroy');

            if (total>0) {
                $('#pagination').twbsPagination({
                    totalPages: total_page,
                    visiblePages: current_page,
                    onPageClick: function (event, pageL) {
                        page = pageL;
                        offset = (pageL - 1) * limit;

                        if (is_ajax_fire != 0) {
                            getPageData();
                        }
                    }
                });
            }
            else {
                getPageData();
            }

        });

    });

    /* Edit Item */
    $("body").on("click",".edit-item",function(){

        var id = $(this).parent("td").data('id');
        var name = $(this).parent("td").prev("td").prev("td").prev("td").prev("td").text();
        var description = $(this).parent("td").prev("td").prev("td").prev("td").text();
        var price = $(this).parent("td").prev("td").prev("td").text();
        var imgUrl = $(this).parent("td").prev("td").text();

        $("#edit-item").find("input[name='id']").val(id);
        $("#edit-item").find("input[name='name']").val(name);
        $("#edit-item").find("textarea[name='description']").val(description);
        $("#edit-item").find("input[name='price']").val(price);
        $("#edit-item").find("input[name='url']").val(imgUrl);
    });

    /* Updated new Item */
    $(".crud-submit-edit").click(function(e){

        e.preventDefault();
        var form_action = $("#edit-item").find("form").attr("action");

        var id = $("#edit-item").find("input[name='id']").val();
        var name = $("#edit-item").find("input[name='name']").val();
        var description = $("#edit-item").find("textarea[name='description']").val();
        var price =$("#edit-item").find("input[name='price']").val();
        var imgUrl=$("#edit-item").find("input[name='url']").val();

        if(name != '' && !isNaN(price)){
            $.ajax({
                dataType: 'json',
                type:'PUT',
                url: url + form_action+'/'+id,
                data:{
                    name:name,
                    description:description,
                    price:price,
                    url: imgUrl,
                    limit:limit,
                    offset: offset,
                    orderField: sortField,
                    orderType: sortType
                }
            }).done(function(data){
                getPageData();
                $(".modal").modal('hide');
                toastr.success('Item Updated Successfully.', 'Success Alert', {timeOut: 3000});
            });
        }else{
            alert('You are missing name or price.')
        }

    });


    $('#productTable').DataTable({

        "ordering": true,
        columnDefs: [{
            orderable: false,
            targets: "no-sort"
        }],
        drawCallback: function(settings) {
            var pagination = $(this).closest('.dataTables_wrapper').find('.dataTables_paginate');
            pagination.toggle(false);
        },
        searching: false,
        bInfo: false,
        bLengthChange: false
    });

    $('#productTable').on('order.dt', function(component, arg2, arg3) {
        sortType = arg3[0].dir;
        sortField = arg3[0].col==0 ? 'id' : 'price';

        getPageData();
    });


});