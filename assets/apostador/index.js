$('#filter_button_limpar').on('click', function(){
    $('#filter_nome').val(null);
    
    const url = new URL(window.location.href);
    
    url.searchParams.delete('filter_nome');
    
    $.LoadingOverlay('show');
        
    window.location.href = url.toString();
});