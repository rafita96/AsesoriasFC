$('#infoModal').on('show.bs.modal', function (event) {
    $("#horario > tr").remove();

    var button = $(event.relatedTarget); // Button that triggered the modal
    var horario = button.data('array').split(","); // Extract info from data-* attributes
    horario.splice(0, 1);

    var tema = button.data('tema');
    var materia = button.data('materia');
    var cantidad = button.data('cantidad');

    var dia = new Date(button.data('fecha'));
    var fechas = new Array(0,0,0,0,0);
    for(var i = 0; i < 7; i++){
        var res = (i+dia.getDay())%7;
        // Sabemos que no es sabado ni domingo
        if(res != 0 && res != 6){
            var d = new Date(dia.getTime());
            d.setDate(dia.getDate() + i);

            fechas[res-1] = d;
        }
    }

    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    $("#tema").attr('placeholder', tema);
    $("#materia").attr('placeholder', materia);
    $("#cantidad").attr('placeholder', cantidad);

    var tabla = document.getElementById('horario');
    var tr = document.createElement("tr");

    agregarEncabezado(tr, "Hora", "");
    agregarEncabezado(tr, "Lunes", fechaACorta(fechas[0]));
    agregarEncabezado(tr, "Martes", fechaACorta(fechas[1]));
    agregarEncabezado(tr, "Miércoles", fechaACorta(fechas[2]));
    agregarEncabezado(tr, "Jueves", fechaACorta(fechas[3]));
    agregarEncabezado(tr, "Viernes", fechaACorta(fechas[4]));
    tabla.appendChild(tr);

    for(var i = 0; i < 5; i++){
        var tr = document.createElement("tr");

        for(var j = 0; j < 6; j++){
            var td = document.createElement("td");
            td.setAttribute("class", "col-1");

            if(j == 0){
                var hora = (2*i + 8)%12;
                var siguiente = hora + 2;
                if(hora == 0){
                    hora = 12;
                }else if(hora == 10){
                    siguiente = 12;
                }
                td.innerHTML = hora + ":00-" + siguiente + ":00"
            }else{
                var index = i*5 + j - 1;
                if(index == horario[0]){
                    td.setAttribute("class", "col-1 bg-info");
                    horario.splice(0, 1);
                } 
            }

            tr.appendChild(td);
        }
        tabla.appendChild(tr);
    }
});

function agregarEncabezado(tr, dia, fecha){
    var th = document.createElement("th");
    var row = document.createElement("div");
    row.setAttribute("class", "row text-center");

    var col1 = document.createElement("div");
    col1.setAttribute("class", "col-12");
    col1.innerHTML = dia;

    var col2 = document.createElement("div");
    col2.setAttribute("class", "col-12");
    col2.innerHTML = fecha;

    row.appendChild(col1);
    row.appendChild(col2);

    th.appendChild(row);
    tr.appendChild(th);
}


function fechaACorta(fecha){
    var dia = fecha.getDate();
    var mes = fecha.getMonth()+1;
    var year = fecha.getFullYear();

    return dia+"/"+mes+"/"+year;
}