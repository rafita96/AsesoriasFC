var horario, input, fechas;
$(document).ready(function() {
    input = document.getElementById('appbundle_cita_horario');
    input.value = "";

    horario = new Array();

    var tabla = document.getElementById('horario');
    var thead = document.createElement("thead");
    var tr = document.createElement("tr");

    var dia = new Date();
    fechas = new Array(0,0,0,0,0);

    for(var i = 0; i < 7; i++){
        var res = (i+dia.getDay())%7;
        // Sabemos que no es sabado ni domingo
        if(res != 0 && res != 6){
            var d = new Date();
            d.setDate(dia.getDate() + i);

            fechas[res-1] = d;
        }
    }

    agregarEncabezado(tr, "Hora", "");
    agregarEncabezado(tr, "Lunes", fechaACorta(fechas[0]));
    agregarEncabezado(tr, "Martes", fechaACorta(fechas[1]));
    agregarEncabezado(tr, "Miércoles", fechaACorta(fechas[2]));
    agregarEncabezado(tr, "Jueves", fechaACorta(fechas[3]));
    agregarEncabezado(tr, "Viernes", fechaACorta(fechas[4]));
    thead.appendChild(tr);
    tabla.appendChild(thead);
    // console.log(fechas);

    var tbody = document.createElement("tbody");
    for(var i = 0; i < 5; i++){
        var tr = document.createElement("tr");

        for(var j = 0; j < 6; j++){
            var td = document.createElement("td");

            if(j == 0){
                var hora = (2*i + 8)%12;
                var siguiente = hora + 2;
                if(hora == 0){
                    hora = 12;
                }else if(hora == 10){
                    siguiente = 12;
                }
                td.setAttribute("class", "col-1 hora")
                td.innerHTML = hora + ":00 - " + siguiente + ":00"
            }else{
                td.index = i*5 + j - 1;
                td.selected = false;

                td.addEventListener("click", function(){
                    if(this.selected){
                        this.selected = false;
                        var quitar = horario.indexOf(this.index);
                        if (quitar > -1) {
                            horario.splice(quitar, 1);
                            this.setAttribute("class","");
                        }

                    }else{
                        this.selected = true;
                        if(horario.indexOf(this.index) == -1){
                            horario.push(this.index);
                            this.setAttribute("class","bg-info");
                        }
                    }
                    
                    input.value = horario;
                });
            }

            tr.appendChild(td);
        }
        tbody.appendChild(tr);
    }

    tabla.appendChild(tbody);
    
    if($(window).width() < 760){
        resizeTable();
    }
});

function agregarEncabezado(tr, dia, fecha){
    var th = document.createElement("th");
    th.setAttribute("class","col-1");
    var row = document.createElement("div");
    row.setAttribute("class", "row text-center");

    var col1 = document.createElement("div");
    col1.setAttribute("class","col-12");
    col1.innerHTML = dia;

    var col2 = document.createElement("div");
    col2.setAttribute("class","col-12");
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

$("form[name='appbundle_cita']").submit(function(e){
    if(horario.length <= 0){
        toastr("No has elegido un horario.");
        return false;
    }

    return true;
});

function resizeTable(){
    var tabla = $(".table");

    var dias = ["Lunes "+fechaACorta(fechas[0]), "Martes "+fechaACorta(fechas[1]), 
            "Mi\u00E9rcoles "+fechaACorta(fechas[2]), "Jueves "+fechaACorta(fechas[3]), 
            "Viernes "+fechaACorta(fechas[4])];
    for(var i = 1; i < 6; i++){
        tabla.find('tbody tr:nth-child(' + i + ') td:first-child').before("<tr><td col-span='2'>"+dias[i-1]+"</td></tr>");
    }
} 