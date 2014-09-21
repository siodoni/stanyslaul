function excluir() {
    var decisao = confirm("Deseja excluir o registro?");
    if (decisao) {
        return true;
    } else {
        return false;
    }
}

$(function() {
    $('#dlgCarregando').puidialog({modal: true, resizable: false, width: 110, closable: false});
});

$(document).ready(function() {
    //console.log("ready...");
    $('#dlgCarregando').puidialog('show');
});

$(window).load(function() {
    //console.log("load...");
    $('#dlgCarregando').puidialog('hide');
});