$(function() {
    $("#set_order").on('change',function() {
        if ($(this).is(':checked')) {
            $('#sort_heads_modal').modal('show');
        }
    });

    $('#sorted_table_Liability').sortable();

    $('#sorted_table_Asset').sortable();
});
function submitSort() {
    var l = '';
    var a = '';
    $.each($('#sorted_table_Liability').find('td'), function() {
        l = l+$(this).text()+',';
    });
    $.each($('#sorted_table_Asset').find('td'), function() {
        a = a+$(this).text()+',';
    });
    $('#order_liabilities').val(l);
    $('#order_assets').val(a);
    $('#sort_heads_modal').modal('hide');
}

