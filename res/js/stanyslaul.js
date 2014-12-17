function excluir() {
    var decisao = confirm("Deseja excluir o registro?");
    if (decisao) {
        return true;
    } else {
        return false;
    }
}

function atualizaDataTable() {
    /*
    $(".pui-datatable-data td").each(function() {
        if ($(this).text().length > 80) {
            $(this).text($(this).text().substr(0,77) + "...");
        }
    });
    */
}

$(function() {
    $('#dlgCarregando').puidialog({
        modal: true,
        resizable: false,
        width: 110,
        closable: false});
});

$(document).ready(function() {
    $('#dlgCarregando').puidialog('show');
});

$(window).load(function() {
    $('#dlgCarregando').puidialog('hide');
});