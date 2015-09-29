$('#visits-insurer_id').change(function(){
    if ( $(this).val() > 0)
    {
       $('[value=1]').prop('checked','checked');
    }
    else
    {
        $('[value=0]').prop('checked','checked');
    }
})

$('[value=0]').click(function(){
    $('#visits-insurer_id :first').attr("selected", "selected");
    $("#select2-visits-insurer_id-container").empty();
})