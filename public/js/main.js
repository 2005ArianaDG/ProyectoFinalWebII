// Script para manejar el cierre de sesión
$(document).ready(function() {
    $('#logoutBtn').click(function(e) {
        e.preventDefault();
        $(this).closest('form').submit();
    });
});
