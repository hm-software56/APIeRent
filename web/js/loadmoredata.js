$(window).scroll(function () {
        if ($(window).scrollTop() + $(window).height() >= $(document).height()) {
            var last_id = $(".post-id:last").attr("id");
            if(last_id!=null)
            {
            loadMoreData(last_id);
            }
        }
    });


function loadMoreData(last_id) {
    $.ajax({
        url: 'http://192.168.100.165/index.php/site/loadmoredata?id=' + last_id,
            type: "get",
            beforeSend: function () {
                $('.ajax-load').show();
            }
        })
        .done(function (data) {
            $('.ajax-load').hide();
            $("#post-data").append(data);
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
           // alert('server not responding...');
        });
} 