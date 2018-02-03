var seleccionado = null;
$(document).ready(function () {
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

    var tabla = document.getElementById('horario');
    var tr = document.createElement("thead");

    agregarEncabezado(tr, "Hora", "");
    agregarEncabezado(tr, "Lunes", fechaACorta(fechas[0]));
    agregarEncabezado(tr, "Martes", fechaACorta(fechas[1]));
    agregarEncabezado(tr, "Miércoles", fechaACorta(fechas[2]));
    agregarEncabezado(tr, "Jueves", fechaACorta(fechas[3]));
    agregarEncabezado(tr, "Viernes", fechaACorta(fechas[4]));
    tabla.appendChild(tr);

    var tbody = document.createElement("tbody");
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
                    td.seleccionado = false;

                    var tiempo = fechas[index%5]
                    td.fecha = tiempo.getFullYear() + "-" 
                                + (tiempo.getMonth() + 1) + "-" 
                                + tiempo.getDate();
                    var hora = Math.floor(index/5)*2 + 8;
                    td.hora = hora == 0 ? 12 : hora;

                    td.addEventListener('click', function(){
                        
                        if(this.seleccionado){
                            this.seleccionado = false;

                            seleccionado = null;
                            this.setAttribute("class", "col-1 bg-info");
                        }else{
                            this.seleccionado = true;

                            // Seleccionado anterior
                            if(seleccionado != null){
                                seleccionado.setAttribute("class", "col-1 bg-info");
                                seleccionado.seleccionado = false;
                            }
                            seleccionado = this;

                            this.setAttribute("class", "col-1 bg-warning");
                            
                            $("#fecha_input").val(this.fecha);
                            $("#hora_input").val(this.hora);
                        }

                    });

                    horario.splice(0, 1);
                } 
            }

            tr.appendChild(td);
        }
        tbody.appendChild(tr);
    }

    tabla.appendChild(tbody);

    // agregarFechasDisponibles(disponibilidad);
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

function agregarFechasDisponibles(fechas){
    var selectF = document.getElementById("fechas");
    var dias = ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes"];

    for (var i = 0; i < fechas.length; i++) {
        var fecha = new Date(fechas[i][0]);

        var option = document.createElement("option");
        option.value = fecha.getTime();
        option.innerHTML = fechaACorta(fecha);

        selectF.appendChild(option);
    }
}

$("#aceptar_form").submit(function(e){
    if(seleccionado == null){
        toastr("No has elegido un horario.");
        return false;
    }

    return true;
});