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


	// $(".detalles_venta").hide();
	
});

 

function agregar_tabla(){
	var name_table  = $("#name_table").val();
	var campo_id  = $("#campo_id").val();

	var continua = 1;

	//Verificar que los campos no estén vacíos
    if(name_table.trim() === '' || campo_id.trim() === ''){
        continua = 0;
    }


	if(continua == 1){
		$.post("controller.php",
		{ 	action 					: "agregar_tabla",
				name_table 		: name_table,
				campo_id 		: campo_id 
		}, end_agregar_tabla);
	} else{
		Swal.fire({
			icon: 'error',
			title: '¡Error!',
			text: "Llena todos los campos.",
			timer: 1000,
			timerProgressBar: true,
		})
	}
	
}


function end_agregar_tabla(xml){	   
	$(xml).find("response").each(function(i){		 
		if ($(this).find("result").text()=="ok"){ 
			$('#modalAgregar').modal('hide');
			$("#table_tablas").load(location.href + " #table_tablas");
			Swal.fire({
				icon: 'success',
				title: '¡Correcto!',
				text: $(this).find("result_text").text(),
				timer: 1000,
				timerProgressBar: true,
			}) 
		}else{
			Swal.fire({
				icon: 'error',
				title: '¡Error!',
				text: $(this).find("result_text").text(),
				timer: 1000,
				timerProgressBar: true,
			})
		}
	});
}


function eliminar_tabla(){
	var nombre_tabla = $("#eliminar_nombre_tabla").val();

	// console.log(nombre_tabla);

	  
	$.post("controller.php",
		{
			action : "eliminar_tabla",
			nombre_tabla			: nombre_tabla,
		}, end_eliminar_tabla);
	
}

function end_eliminar_tabla(xml){
	$(xml).find("response").each(function(i){		 
		if ($(this).find("result").text()=="ok"){ 
			$('#modalEliminar').modal('hide');
			$("#table_tablas").load(location.href + " #table_tablas"); 
			Swal.fire({
				icon: 'success',
				title: 'Correcto!',
				text: $(this).find("result_text").text(),
				timer: 1000,
				timerProgressBar: true,
			})
		}else{
			$('#modalEliminar').modal('hide');
			Swal.fire({
				icon: 'error',
				title: '¡Error!',
				text: $(this).find("result_text").text(),
				timer: 1000,
				timerProgressBar: true,
			})
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
			Swal.fire({
				icon: 'success',
				title: '¡Correcto!',
				text: $(this).find("result_text").text(),
				timer: 1000,
				timerProgressBar: true,
			})
		}else{
			Swal.fire({
				icon: 'error',
				title: '¡Error!',
				text: $(this).find("result_text").text(),
				timer: 1000,
				timerProgressBar: true,
			})
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
			$('#modalEliminar').modal('hide');
			$("#formEliminarCampo").trigger("reset");
			$("#table_tablas").load(location.href + " #table_tablas"); 
			Swal.fire({
				icon: 'success',
				title: '¡Correcto!',
				text: $(this).find("result_text").text(),
				timer: 1000,
				timerProgressBar: true,
			})
		}else{
			$('#modalEliminar').modal('hide');
			Swal.fire({
				icon: 'error',
				title: '¡Error!',
				text: $(this).find("result_text").text(),
				timer: 1000,
				timerProgressBar: true,
			})
		}
	});
}




// ESTO FALTAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA
function modificar_campo(){

}

function end_modificar_campo(xml){
	$(xml).find("response").each(function(i){		 
		if ($(this).find("result").text()=="ok"){ 
			$("#formModificarCampo").trigger("reset");
			$("#table_tablas").load(location.href + " #table_tablas"); 
			Swal.fire({
				icon: 'success',
				title: '¡Correcto!',
				text: $(this).find("result_text").text(),
				timer: 1000,
				timerProgressBar: true,
			})
		}else{
			Swal.fire({
				icon: 'error',
				title: '¡Error!',
				text: $(this).find("result_text").text(),
				timer: 1000,
				timerProgressBar: true,
			})
		}
	});
}


function agregar_registro(tabla){
	var continua = 1;
	var formData = {};
	
    $('#form_nuevo_registro input').each(function(){
		//Obtener el nombre del campo y su valor
        var nombreCampo = $(this).attr('name');
        var valorCampo = $(this).val();

		if(valorCampo === ''){
			// console.log("Campo vacio: ")
			// console.log(valorCampo)
            continua = 0;
        } else{
            formData[nombreCampo] = valorCampo;
		}

        
    });

	formData['tabla'] = tabla;
	formData['action'] = 'agregar_registro';

	if(continua == 1){
		$.post("controller.php", formData, end_agregar_registro);
	} else{
		Swal.fire({
			icon: 'error',
			title: '¡Error!',
			text: "Llena todos los campos.",
			timer: 1000,
			timerProgressBar: true,
		})
		
	}

	// $.post("controller.php",
	// { 	action 					: "agregar_registro",
	// 	tabla 					: tabla,
	// }, end_agregar_registro);
}

function end_agregar_registro(xml){
	$(xml).find("response").each(function(i){		 
		if ($(this).find("result").text()=="ok"){ 
			// $('#container_tabla').load('tabla/'+$(this).find("tabla").text()); //Recargar tabla con nuevo registro
			$("#form_nuevo_registro")[0].reset(); //Limpiar formulario
			$('#modalAgregar').modal('hide');

			Swal.fire({
					icon: 'success',
					title: '¡Correcto!',
					text: $(this).find("result_text").text(),
					timer: 1000,
					timerProgressBar: true,
				})
			setTimeout(function() {
				location.reload();
			}, 1000);
			
			
		}else{
			
			Swal.fire({
				icon: 'error',
				title: '¡Error!',
				text: $(this).find("result_text").text(),
				timer: 1000,
				timerProgressBar: true,
			})
		}
	});
}



function modificar_registro(tabla){
	console.log("Llega aqui");
	var nombre_id = $("#editar_nombre_id").val();
	var id_registro = $("#editar_id_registro").val();

	var continua = 1;
	var formData = {};
	
    $('#form_editar_registro input').each(function(){
		//Obtener el nombre del campo y su valor
        var nombreCampo = $(this).attr('name');
        var valorCampo = $(this).val();

		if(valorCampo === ''){
			// console.log("Campo vacio: ")
			// console.log(valorCampo)
            continua = 0;
        } else{
            formData[nombreCampo] = valorCampo;
		}

        
    });

	formData['tabla'] = tabla;
	formData['action'] = 'modificar_registro';

	if(continua == 1){
		$.post("controller.php", formData, end_modificar_registro);
	} else{
		Swal.fire({
			icon: 'error',
			title: '¡Error!',
			text: "Llena todos los campos.",
			timer: 1000,
			timerProgressBar: true,
		})
	}

}

function end_modificar_registro(xml){
	
    $(xml).find("response").each(function(){
        if ($(this).find("result").text()=="ok"){ 
			$("#form_editar_registro")[0].reset(); //Limpiar formulario
			$('#modalEditar').modal('hide');

			Swal.fire({
					icon: 'success',
					title: '¡Correcto!',
					text: $(this).find("result_text").text(),
					timer: 1000,
					timerProgressBar: true,
				})
			setTimeout(function() {
				location.reload();
			}, 1000);
			
			
		}else{
			
			Swal.fire({
				icon: 'error',
				title: '¡Error!',
				text: $(this).find("result_text").text(),
				timer: 1000,
				timerProgressBar: true,
			})
		}
    });
}





function eliminar_registro(){
	var tabla = $("#eliminar_nombre_tabla").val();
	var nombre_id_registro = $("#eliminar_nombre_id").val();
	var id_registro = $("#eliminar_id_registro").val();

	// console.log(tabla);
	// console.log(nombre_id_registro);
	// console.log(id_registro);

	$.post("controller.php",
	{ 			action 					: "eliminar_registro",
				tabla 					: tabla,
				nombre_id_registro		: nombre_id_registro,
				id_registro 			: id_registro,
	}, end_eliminar_registro);
}

function end_eliminar_registro(xml){
	$(xml).find("response").each(function(i){		 
		if($(this).find("result").text()=="ok"){ 
			$('#modalEliminar').modal('hide');
			
			Swal.fire({
				icon: 'success',
				title: '¡Correcto!',
				text: $(this).find("result_text").text(),
				timer: 1000,
				timerProgressBar: true,
			})

			setTimeout(function() {
				location.reload();
			}, 1000);
			
		}else{
			$('#modalEliminar').modal('hide');
			Swal.fire({
				icon: 'error',
				title: '¡Error!',
				text: $(this).find("result_text").text(),
				timer: 1000,
				timerProgressBar: true,
			})
		}
	});
}

function llenar_form_tabla(nombre_tabla, id_columna){
	// console.log(nombre_tabla);
	// console.log(id_columna);

	$.post("controller.php",
	{ 			action 				: "llenar_form_tabla",
				nombre_tabla		: nombre_tabla,
				id_columna 			: id_columna,
	}, end_llenar_form_tabla);
}

function end_llenar_form_tabla(xml){
    $(xml).find("response").each(function(){
        if($(this).find("result").text() == "ok"){
            var cuantos_campos = parseInt($(this).find("cuantos_campos").text());
            var campos_json = $(this).find("nombres_campos").text();
            var registros_json = $(this).find("registros_campos").text();

            var campos = JSON.parse(campos_json);
            var registros = JSON.parse(registros_json);

            for(var i = 0; i < cuantos_campos; i++){
                var nombre_campo = campos[i];
                var valor_campo = registros[i];

                $('#editar_' + nombre_campo).val(valor_campo);
            }
        }
    });
}


function ver_detalles_venta(id_venta, e){
	e.preventDefault();

	// console.log(id_venta);
	

	$("#detalles_venta_"+id_venta).toggle('fast');
	$("#detalles_venta_tabla_"+id_venta).toggle('fast');

	//Falta hacer que se abra los detalles de la venta, sólo en el llenar_tabla del controller


}


function llenar_tabla_ventas(){
	var select_ventas = $("#select_ventas").val();


	$.post("controller.php",
	{ 			action 					: "llenar_tabla_ventas",
				select_ventas 			: select_ventas,
	}, end_llenar_tabla_ventas);
}

function end_llenar_tabla_ventas(xml){
    $(xml).find("response").each(function(){
        if($(this).find("result").text() == "ok"){
            $("#tabla_ventas").html($(this).find("tabla_ventas").text());

			$("#total_vendido").html("Total vendido: $" +$(this).find("total_vendido").text());

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