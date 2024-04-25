// $("#btn_login").click(inicia_sesion);
// $("#btn_registro").click(registro_user);

//index
$(".tipo_dato").change(function(){
    if($(this).val() == "VARCHAR"){
        $(".longitud").prop("disabled", false);
    } else{
        $(".longitud").val('');
        $(".longitud").prop("disabled", true);
    }
});
$(".tipo_dato").change();


$("#select_ordenamiento").change(function(){
	var name_table = $("#nombre_tabla").val();
	var opcion = $(this).val();

	llenar_tabla(name_table, opcion);
});


function llenar_tabla(name_table, opcion){
	$.post("controller.php",
	{ 	action 					: "llenar_tabla",
			name_table 		: name_table,
			opcion          : opcion	
	}, end_llenar_tabla);
} 

function end_llenar_tabla(xml){
	// console.log($(this).find("tabla_a_rellenar").text());
	// rellena_tablas(xml,"tabla_a_rellenar",ocultamiento_columnas);

	$(xml).find("response").each(function(i){	
		if($(this).find("result").text()=="ok"){
			$("#tabla_a_rellenar").html($(this).find("tabla_a_rellenar").text());
		}
	});
}


$(document).ready(function() { 
	//modificar-tablas
	$("#formEliminarCampo").hide();
	$("#formModificarCampo").hide();

    $("#accion_tabla").change(function() {
        var accionSeleccionada = $(this).val();

        if (accionSeleccionada === "agregar_columna") {
            $("#formAgregarCampo").show('slow');
            $("#formEliminarCampo").hide('slow');
            $("#formModificarCampo").hide('slow');
        } else if(accionSeleccionada === "borrar_columna"){
            $("#formAgregarCampo").hide('slow');
            $("#formEliminarCampo").show('slow');
            $("#formModificarCampo").hide('slow');
        } else if(accionSeleccionada === "modificar_columna"){
            $("#formAgregarCampo").hide('slow');
            $("#formEliminarCampo").hide('slow');
            $("#formModificarCampo").show('slow');
        }
    });

    $("#accion_tabla").change();
	
});

 

function agregar_tabla(){
	var name_table  = $("#name_table").val();
	var campo_id  = $("#campo_id").val();


	$.post("controller.php",
		{ 	action 					: "agregar_tabla",
				name_table 		: name_table,
				campo_id 		: campo_id 
		}, end_agregar_tabla);
}
function end_agregar_tabla(xml){	   
	$(xml).find("response").each(function(i){		 
		if ($(this).find("result").text()=="ok"){ 
			$("#table_tablas").load(location.href + " #table_tablas"); 
			swal("¡Correcto!", $(this).find("result_text").text(), "success");
		}else{
			swal("Error", $(this).find("result_text").text(), "error");
		}
	});
}


function eliminar_tabla(nombre_tabla){

	// console.log(nombre_tabla);

	swal({   
		title: "¿Eliminar?",   
		text: "Está acción no podrá revertirse.",   
		/*type: "warning",    */
		showCancelButton: true,    
		confirmButtonColor: "#DD6B55",   
		confirmButtonText: "Eliminar",  
		cancelButtonText: "Cancelar",   
		closeOnConfirm: true 
	}, function(){    
		$.post("controller.php",
			{
				action : "eliminar_tabla",
				nombre_tabla			: nombre_tabla,
			}, end_eliminar_tabla);
	}); 
}

function end_eliminar_tabla(xml){
	$(xml).find("response").each(function(i){		 
		if ($(this).find("result").text()=="ok"){ 
			$("#table_tablas").load(location.href + " #table_tablas"); 
			swal("¡Correcto!", $(this).find("result_text").text(), "success");
		}else{
			swal("Error", $(this).find("result_text").text(), "error");
		}
	});
}

function redirect_modificar_tabla(nombre_tabla){

    window.location.href = "modificar-tablas/"+nombre_tabla;
}


function agregar_campo(e){
	e.preventDefault();
	var name_table = $("#nombre_tabla").val();
	var agregar_campo_columna = $("#agregar_campo_columna").val();
	var agregar_campo_tipo_dato = $("#agregar_campo_tipo_dato").val();
	var agregar_campo_longitud = $("#agregar_campo_longitud").val();

	// console.log("Si llega");

	$.post("controller.php",
		{ 	action 					: "agregar_campo",
				name_table 					: name_table,
				agregar_campo_columna 		: agregar_campo_columna,
				agregar_campo_tipo_dato		: agregar_campo_tipo_dato,
				agregar_campo_longitud		: agregar_campo_longitud,
		}, end_agregar_campo);
}

function end_agregar_campo(xml){
	console.log("Si regresa");
	$(xml).find("response").each(function(i){		 
		if ($(this).find("result").text()=="ok"){ 
			$("#formAgregarCampo").trigger("reset");
			$("#table_tablas").load(location.href + " #table_tablas"); 
			swal("¡Correcto!", $(this).find("result_text").text(), "success");
		}else{
			swal("Error", $(this).find("result_text").text(), "error");
		}
	});
}


function eliminar_campo(e){
	e.preventDefault();
	var name_table = $("#nombre_tabla").val();
	var eliminar_campo_columna = $("#eliminar_campo_columna").val();

	// console.log("Si llega");

	$.post("controller.php",
		{ 	action 					: "eliminar_campo",
				name_table 					: name_table,
				eliminar_campo_columna 		: eliminar_campo_columna,
		}, end_eliminar_campo);
}

function end_eliminar_campo(xml){
	$(xml).find("response").each(function(i){		 
		if ($(this).find("result").text()=="ok"){ 
			$("#formEliminarCampo").trigger("reset");
			$("#table_tablas").load(location.href + " #table_tablas"); 
			swal("¡Correcto!", $(this).find("result_text").text(), "success");
		}else{
			swal("Error", $(this).find("result_text").text(), "error");
		}
	});
}












function validateCodigoUDG(codigo) {
    var regex = /^[0-9]{9}$/;
    
    if (regex.test(codigo)) {
        return true; 
    } else {
        return false; 
    }
}


function validateEmail(email) {
	var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
	var dominioUdgReg = /\.udg\.mx$/;


	if(emailReg.test(email) && dominioUdgReg.test(email)){
        return true; //El email es válido y termina con ".udg.mx"
    } else {
        return false; //El email no es válido o no termina con ".udg.mx"
    }
  }


  function fecha_formato_sql(fecha){  
	var res = fecha.split("-");
	var dia = res[0];
	var mes = res[1];
	var ano = res[2];
	var mes_num = "0";
	switch (mes){
		case "Ene": mes_num = "01"; break;
		case "Feb": mes_num = "02"; break;
		case "Mar": mes_num = "03"; break;
		case "Abr": mes_num = "04"; break;
		case "May": mes_num = "05"; break;
		case "Jun": mes_num = "06"; break;
		case "Jul": mes_num = "07"; break;
		case "Ago": mes_num = "08"; break;
		case "Sep": mes_num = "09"; break;
		case "Oct": mes_num = "10"; break;
		case "Nov": mes_num = "11"; break;
		case "Dic": mes_num = "12"; break;
	}	
	var nueva_fecha = ano + "-" + mes_num + "-" + dia;
	return nueva_fecha;
}



function rellena_tablas(xml,$id_tabla_mostrar_datos,$ocultamiento_columnas){
	$(xml).find("response").each(function(i){		 
		if ($(this).find("result").text()=="ok"){
			/*Destruye la tabla y reinicializa valores*/
			$("#"+$id_tabla_mostrar_datos).html(""); 
			table = $('#example').DataTable();
			table.buttons().destroy(); 
			$(".dt-buttons").remove(); 
			table .clear() .draw(); 
			table.destroy(); 

			$("#"+$id_tabla_mostrar_datos).html($(this).find($id_tabla_mostrar_datos).text()); 
			/*inicializa la tabla y Carga los botones de Exportación con los datos extraidos*/	
			$('[data-toggle="tooltip"]').tooltip();
			table = $('#example').DataTable({
				dom: 'Bfrtip',
				pageLength : 30,
				buttons: [
					{  
						extend: 'excel',
						exportOptions: {
							columns: [":visible"]
						}
					},
					{
						extend: 'pdf',
						exportOptions: {
							columns: [":visible"]
						}
					},
					{
						extend: 'print',
						exportOptions: {
							columns: [":visible"]
						}
					},
					{
						extend: 'colvis',
						columns: ':gt(0)'
					}
				],
				columnDefs: [{targets:$ocultamiento_columnas,visible:false}], 
				//Manda a llamar el contenido de la variable global idioma_espanol con el objetivo de definir el lenguage para diferentes labels usados.
				language: idioma_espanol
			});
		}
	});
}