$('#filter_button_limpar').on('click', function(){
    $('#filter_loteria').prop('selectedIndex', 0);
    $('#filter_apurado').prop('selectedIndex', 0);
    $('#filter_concurso').val(null);
    $('#filter_nome').val(null);
    
    const url = new URL(window.location.href);
    
    url.searchParams.delete('filter_loteria');
    url.searchParams.delete('filter_apurado');
    url.searchParams.delete('filter_concurso');
    url.searchParams.delete('filter_bolao');
    
    $.LoadingOverlay('show');
        
    window.location.href = url.toString();
});