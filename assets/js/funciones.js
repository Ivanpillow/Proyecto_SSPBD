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