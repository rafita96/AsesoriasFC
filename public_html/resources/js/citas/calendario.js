var horario, input;
$(document).ready(function() {
    input = document.getElementById('appbundle_cita_horario');
    horario = new Array();

    var tabla = document.getElementById('horario');
    var tr = document.createElement("tr");

    agregarEncabezado(tr, "Hora");
    agregarEncabezado(tr, "Lunes");
    agregarEncabezado(tr, "Martes");
    agregarEncabezado(tr, "Miércoles");
    agregarEncabezado(tr, "Jueves");
    agregarEncabezado(tr, "Viernes");
    tabla.appendChild(tr);

    for(var i = 0; i < 5; i++){
        var tr = document.createElement("tr");

        for(var j = 0; j < 6; j++){
            var td = document.createElement("td");
            td.setAttribute("class", "col-1");

            if(j == 0){
                var hora = (2*i + 8)%12;
                if(hora == 0){
                    hora = 12;
                }
                td.innerHTML = hora + ":00 - " + ((hora + 2)%12) + ":00"
            }else{
                td.index = i*5 + j - 1;
                td.selected = false;

                td.addEventListener("click", function(){
                    if(this.selected){
                        this.selected = false;
                        var quitar = horario.indexOf(this.index);
                        if (quitar > -1) {
                            horario.splice(quitar, 1);
                            this.setAttribute("class","col-1");
                        }

                    }else{
                        this.selected = true;
                        if(horario.indexOf(this.index) == -1){
                            horario.push(this.index);
                            this.setAttribute("class","col-1 bg-info");
                        }
                    }
                    
                    input.value = horario;
                });
            }

            tr.appendChild(td);
        }
        tabla.appendChild(tr);
    }
});

function agregarEncabezado(tr, dia){
    var th = document.createElement("th");
    th.innerHTML = dia;
    tr.appendChild(th);
}

