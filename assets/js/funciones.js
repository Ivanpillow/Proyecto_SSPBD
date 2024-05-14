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
	

	
	$("#div_detalles_producto").hide();
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



//#region ventas.php


function ver_detalles_venta(id_venta, e){
	e.preventDefault();

	// console.log(id_venta);
	

	$("#detalles_venta_"+id_venta).toggle('fast');
	$("#detalles_venta_tabla_"+id_venta).toggle('fast');

	//Falta hacer que se abra los detalles de la venta, sólo en el llenar_tabla del controller


}


function ver_descripcion_producto(id_producto, e){
	e.preventDefault();

    // console.log(id_producto);
    

    $("#descripcion_producto_"+id_producto).toggle('fast');

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


//#region nueva-venta.php


function llenar_select_clientes(){
	console.log("HOLAAA");
	$.post("controller.php",
	{ 	
		action 		: "llenar_select_clientes",
	}, end_llenar_select_clientes);
}

function end_llenar_select_clientes(xml){
	$(xml).find("response").each(function(){
        if($(this).find("result").text() == "ok"){
            $("#select_clientes").html($(this).find("select_clientes").text());
        }
    });
}


function llenar_select_empleados(){
	$.post("controller.php",
	{ 	
		action 		: "llenar_select_empleados",
	}, end_llenar_select_empleados);
}

function end_llenar_select_empleados(xml){
	$(xml).find("response").each(function(){
        if($(this).find("result").text() == "ok"){
            $("#select_empleados").html($(this).find("select_empleados").text());
        }
    });
}

function llenar_select_productos(){
	$.post("controller.php",
	{ 	
		action 		: "llenar_select_productos",
	}, end_llenar_select_productos);
}

function end_llenar_select_productos(xml){
	$(xml).find("response").each(function(){
        if($(this).find("result").text() == "ok"){
            $("#select_producto").html($(this).find("select_productos").text());
        }
    });
}


function llenar_select_tallas(){
	var id_producto = $("#select_producto").val();

	// console.log("ID Producto: " + id_producto);

	$.post("controller.php",
	{ 	
		action 		: "llenar_select_tallas",
		id_producto : id_producto,
	}, end_llenar_select_tallas);
}

function end_llenar_select_tallas(xml){
	$(xml).find("response").each(function(){
        if($(this).find("result").text() == "ok"){
            $("#select_talla").html($(this).find("select_tallas").text());
			// console.log("ID Producto: "+ $(this).find("id_producto").text());

			if($(this).find("id_producto").text() == null){
				$("#dv_precio_unitario").val('');
			} else{
				$("#dv_precio_unitario").val($(this).find("precio_unitario").text());
			}
        }
    });
}


function crear_venta(){
	var id_cliente = $("#select_clientes").val();
	var id_empleado = $("#select_empleados").val();

	var continua = 1;

	if(id_cliente == 0){
		continua = 0;

		Swal.fire({
            icon: 'error',
            title: '¡Error!',
            text: "Selecciona un cliente.",
            timer: 1000,
            timerProgressBar: true,
        })
		return;
	}

	if(id_empleado == 0){
		continua = 0;

		Swal.fire({
            icon: 'error',
            title: '¡Error!',
            text: "Selecciona un empleado.",
            timer: 1000,
            timerProgressBar: true,
        })

		return;
	}
	
	


	if(continua == 1){
		$("#div_detalles_producto").show('slow');

		$.post("controller.php",
		{ 	
			action 			: "crear_venta",
			id_cliente     	: id_cliente,
			id_empleado 	: id_empleado,
		}, end_crear_venta);
	}
	
}

function end_crear_venta(xml){
	$(xml).find("response").each(function(){
        if($(this).find("result").text() == "ok"){
            $("#id_venta").val($(this).find("id_venta").text());
			$("#id_cliente").val($(this).find("id_cliente").text());

			Swal.fire({
				icon: 'success',
				title: 'Correcto!',
				text: $(this).find("result_text").text(),
				timer: 1000,
				timerProgressBar: true,
			})
        } else{
            $("#id_venta").val($(this).find("id_venta").text());
			$("#id_cliente").val($(this).find("id_cliente").text());

			Swal.fire({
				icon: $(this).find("result").text(),
				title: '¡Ojo!',
				text: $(this).find("result_text").text(),
				timer: 1000,
				timerProgressBar: true,
			})
		}
		llenar_tabla_dv();
    });
}


function agregar_dv(){
	var id_venta = $("#id_venta").val();
	var id_producto = $("#select_producto").val();
	var id_producto_talla = $("#select_talla").val();
	var cantidad = $("#dv_cantidad").val();

	continua = 1;

	// console.log("ID Venta: " + id_venta);
	// console.log("ID Producto"+id_producto);
	// console.log("ID Producto Talla"+id_producto_talla);
	// console.log("Cantidad: "+cantidad);

	//Verificar campos vacíos
	$("#form_agregar_dv .obligatorio").each(function (index) {
		if ($(this).val() == "") {
			continua = 0;
			Swal.fire({
				icon: 'error',
				title: '¡Error!',
				text: "Llena todos los campos obligatorios.",
				timer: 1000,
				timerProgressBar: true,
			})
			return;
		} 
	});

	if(id_producto == 0){
		continua = 0;
		Swal.fire({
			icon: 'error',
			title: '¡Error!',
			text: "Selecciona un producto.",
			timer: 1000,
			timerProgressBar: true,
		})
		return;
	}

	if(id_producto_talla == 0){
		continua = 0;
        Swal.fire({
            icon: 'error',
            title: '¡Error!',
            text: "Selecciona una talla.",
            timer: 1000,
            timerProgressBar: true,
        })
        return;
	}

	if(cantidad < 1){
		continua = 0;
		Swal.fire({
            icon: 'error',
            title: '¡Error!',
            text: "Selecciona una cantidad válida.",
            timer: 1000,
            timerProgressBar: true,
        })
        return;
	}

	

	if(continua == 1){
		$.post("controller.php",
		{ 	
			action 				: "agregar_dv",
			id_venta 			: id_venta,
			id_producto			: id_producto,
			id_producto_talla 	: id_producto_talla,
			cantidad 			: cantidad,
		}, end_agregar_dv);
	}
}

function end_agregar_dv(xml){

	$(xml).find("response").each(function(i){		 
		if($(this).find("result").text()=="ok"){ 
			$('#modalNuevaDV').modal('hide');
			$("#table_detalle_venta").load(location.href + " #table_detalle_venta");

			llenar_tabla_dv();

			$("#form_agregar_dv")[0].reset(); //Limpiar formulario
			
			Swal.fire({
				icon: 'success',
				title: '¡Correcto!',
				text: $(this).find("result_text").text(),
				timer: 1000,
				timerProgressBar: true,
			})

			// setTimeout(function() {
			// 	location.reload();
			// }, 1000);
			
		}else{
			$('#modalNuevaDV').modal('hide');
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

function editar_dv(){
	var id_detalle_venta = $("#editar_id_dv").val();
	var id_producto_talla = $("#editar_select_talla").val();
	var cantidad = $("#editar_dv_cantidad").val();

	var continua = 1;


	//Verificar campos vacíos
	$("#form_editar_dv .obligatorio").each(function (index) {
		if ($(this).val() == "") {
			continua = 0;
			Swal.fire({
				icon: 'error',
				title: '¡Error!',
				text: "Llena todos los campos obligatorios.",
				timer: 1000,
				timerProgressBar: true,
			})
			return;
		} 
	});


	if(cantidad < 1){
		continua = 0;
		Swal.fire({
            icon: 'error',
            title: '¡Error!',
            text: "Selecciona una cantidad válida.",
            timer: 1000,
            timerProgressBar: true,
        })
        return;
	}

	if(continua == 1){
		$.post("controller.php",
		{ 	
			action 				: "editar_dv",
			id_detalle_venta 	: id_detalle_venta,
			id_producto_talla 	: id_producto_talla,
			cantidad 			: cantidad,
		}, end_editar_dv);
	}
}

function end_editar_dv(xml){
	$(xml).find("response").each(function(i){		 
		if($(this).find("result").text()=="ok"){ 
			$('#modalEditarDV').modal('hide');
			$("#table_detalle_venta").load(location.href + " #table_detalle_venta");

			llenar_tabla_dv();
			
			Swal.fire({
				icon: 'success',
				title: '¡Correcto!',
				text: $(this).find("result_text").text(),
				timer: 1000,
				timerProgressBar: true,
			})

			// setTimeout(function() {
			// 	location.reload();
			// }, 1000);
			
		}else{
			$('#modalEditarDV').modal('hide');
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

function eliminar_dv(id_detalle_venta){

	$.post("controller.php",
		{ 	
			action 				: "eliminar_dv",
			id_detalle_venta 	: id_detalle_venta,
		}, end_eliminar_dv);
}

function end_eliminar_dv(xml){
	
	$(xml).find("response").each(function(i){		 
		if($(this).find("result").text()=="ok"){ 

			$("#table_detalle_venta").load(location.href + " #table_detalle_venta");

			llenar_tabla_dv();
			
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


function llenar_tabla_dv(){
	var id_venta = $("#id_venta").val();

	// console.log("ID Venta: " + id_venta);

	$.post("controller.php",
		{ 	
			action 				: "llenar_tabla_dv",
			id_venta			: id_venta
		}, end_llenar_tabla_dv);
}

function end_llenar_tabla_dv(xml){
	$(xml).find("response").each(function(i){		 
		if($(this).find("result").text()=="ok"){ 
			$('#table_detalle_venta').html($(this).find("tabla_detalle_venta").text());
		}
	});
}


function llenar_form_dv(id_detalle_venta){


	$.post("controller.php",
		{ 	
			action 				: "llenar_form_dv",
			id_detalle_venta			: id_detalle_venta
		}, end_llenar_form_dv);
}

function end_llenar_form_dv(xml){
	$(xml).find("response").each(function(i){		 
		if($(this).find("result").text()=="ok"){ 
			$("#editar_dv_producto").val($(this).find("nombre_producto").text());
			$("#editar_select_talla").html($(this).find("select_tallas").text());
			$("#editar_dv_cantidad").val($(this).find("cantidad").text());
			$("#editar_dv_precio_unitario").val($(this).find("precio").text());
		}
	});
}


function terminar_venta(){
	var id_venta = $("#id_venta").val();

	$.post("controller.php",
		{ 	
			action 					: "terminar_venta",
			id_venta				: id_venta
		}, end_terminar_venta);
}


function end_terminar_venta(xml){
	$(xml).find("response").each(function(i){		 
		if($(this).find("result").text()=="ok"){ 
			Swal.fire({
				icon: 'success',
				title: '¡Correcto!',
				text: $(this).find("result_text").text(),
				timer: 1000,
				timerProgressBar: true,
			})


			window.location.href = 'cliente/'+$(this).find("id_cliente").text();
			
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


// #region compras-php
//A partir de aqui pa abajooooooooooooooooooooooo

function ver_detalles_compra(id_compra, e){
	e.preventDefault();

	// console.log(id_venta);
	

	$("#detalles_compra_"+id_compra).toggle('fast');
	$("#detalles_compra_tabla_"+id_compra).toggle('fast');

	//Falta hacer que se abra los detalles de la venta, sólo en el llenar_tabla del controller


}







































//#region index.php 




//#region (productos)


function agregar_producto(){
	var nombre_producto = $("#add_nombre_producto").val();
	var descripcion = $("#add_descripcion").val();
	var precio = $("#add_precio").val();
	var stock = $("#add_stock").val();
	var categoria = $("#add_categoria").val();

	continua = 1;

	//Verificar campos vacíos
	$("#form_agregar_producto .obligatorio").each(function (index) {
		if ($(this).val() == "") {
			continua = 0;
			Swal.fire({
				icon: 'error',
				title: '¡Error!',
				text: "Llena todos los campos obligatorios.",
				timer: 1000,
				timerProgressBar: true,
			})
			return;
		} 
	});


	if(!validarPrecio(precio)){
		continua = 0;
		Swal.fire({
			icon: 'error',
			title: '¡Error!',
			text: "Ingresa un precio válido.",
			timer: 1000,
			timerProgressBar: true,
		})
		return;
	}


	if(!validarCantidad(stock)){
		continua = 0;
		Swal.fire({
			icon: 'error',
			title: '¡Error!',
			text: "Ingresa una cantidad en almacén válida.",
			timer: 1000,
			timerProgressBar: true,
		})
		return;
	}


	if(continua == 1){
		$.post("controller.php",
		{ 	
			action 			: "agregar_producto",
			nombre_producto     : nombre_producto,
			descripcion 		: descripcion,
			precio				: precio,
			stock				: stock,
			categoria			: categoria,
		}, end_agregar_producto);
	}

}


function end_agregar_producto(xml){
	$(xml).find("response").each(function(i){		 
		if($(this).find("result").text()=="ok"){ 
			$('#modalAgregarProducto').modal('hide');
			$("#div_tabla_productos").load(location.href + " #div_tabla_productos");

			// llenar_tabla_productos();

			$("#form_agregar_producto")[0].reset(); //Limpiar formulario
			
			Swal.fire({
				icon: 'success',
				title: '¡Correcto!',
				text: $(this).find("result_text").text(),
				timer: 1000,
				timerProgressBar: true,
			})

			// setTimeout(function() {
			// 	location.reload();
			// }, 1000);
			
		}else{
			$('#modalAgregarProducto').modal('hide');
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


function llenar_form_producto(id_producto){
	$.post("controller.php",
		{ 	
			action 			: "llenar_form_producto",
			id_producto     : id_producto,
		}, end_llenar_form_producto);
}

function end_llenar_form_producto(xml){
	$(xml).find("response").each(function(i){		 
		if($(this).find("result").text()=="ok"){ 
			// console.log("Si llega aqui");
			$("#edit_id_producto").val($(this).find("id_producto").text());
			$("#edit_nombre_producto").val($(this).find("nombre_producto").text());
			$("#edit_descripcion").val($(this).find("descripcion").text());
			$("#edit_precio").val($(this).find("precio").text());
			$("#edit_stock").val($(this).find("stock").text());
			$("#edit_categoria").val($(this).find("categoria").text());

		}
	});
}



function editar_producto(){
	var id_producto	= $("#edit_id_producto").val();
	var nombre_producto = $("#edit_nombre_producto").val();
	var descripcion = $("#edit_descripcion").val();
	var precio = $("#edit_precio").val();
	var stock = $("#edit_stock").val();
	var categoria = $("#edit_categoria").val();

	continua = 1;

	//Verificar campos vacíos
	$("#form_editar_producto .obligatorio").each(function (index) {
		if ($(this).val() == "") {
			continua = 0;
			Swal.fire({
				icon: 'error',
				title: '¡Error!',
				text: "Llena todos los campos obligatorios.",
				timer: 1000,
				timerProgressBar: true,
			})
			return;
		} 
	});


	if(!validarPrecio(precio)){
		continua = 0;
		Swal.fire({
			icon: 'error',
			title: '¡Error!',
			text: "Ingresa un precio válido.",
			timer: 1000,
			timerProgressBar: true,
		})
		return;
	}


	if(!validarCantidad(stock)){
		continua = 0;
		Swal.fire({
			icon: 'error',
			title: '¡Error!',
			text: "Ingresa una cantidad en almacén válida.",
			timer: 1000,
			timerProgressBar: true,
		})
		return;
	}


	if(continua == 1){
		$.post("controller.php",
		{ 	
			action 			: "editar_producto",
			id_producto			: id_producto,
			nombre_producto     : nombre_producto,
			descripcion 		: descripcion,
			precio				: precio,
			stock				: stock,
			categoria			: categoria,
		}, end_editar_producto);
	}

}


function end_editar_producto(xml){
	$(xml).find("response").each(function(i){		 
		if($(this).find("result").text()=="ok"){ 
			$('#modalEditarProducto').modal('hide');
			$("#div_tabla_productos").load(location.href + " #div_tabla_productos");

			// llenar_tabla_productos();

			$("#form_editar_producto")[0].reset(); //Limpiar formulario
			
			Swal.fire({
				icon: 'success',
				title: '¡Correcto!',
				text: $(this).find("result_text").text(),
				timer: 1000,
				timerProgressBar: true,
			})

			// setTimeout(function() {
			// 	location.reload();
			// }, 1000);
			
		}else{
			$('#modalEditarProducto').modal('hide');
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


function cambiar_status_producto(id_producto, tipo, e){
	e.preventDefault();

	$.post("controller.php",
	{ 	
		action 			: "cambiar_status_producto",
		id_producto		: id_producto,
		tipo			: tipo
	}, end_cambiar_status_producto);
	
}



function end_cambiar_status_producto(xml){
	$(xml).find("response").each(function(i){		 
		if($(this).find("result").text()=="ok"){ 
			$("#div_tabla_productos").load(location.href + " #div_tabla_productos");

			Swal.fire({
				icon: 'success',
				title: '¡Correcto!',
				text: $(this).find("result_text").text(),
				timer: 1000,
				timerProgressBar: true,
			})

		}
	});
}



function cambiar_status_talla(id_producto_talla, tipo, e){
	e.preventDefault();

	$.post("controller.php",
	{ 	
		action 			: "cambiar_status_talla",
		id_producto_talla	: id_producto_talla,
		tipo				: tipo
	}, end_cambiar_status_talla);
	
}



function end_cambiar_status_talla(xml){
	$(xml).find("response").each(function(i){		 
		if($(this).find("result").text()=="ok"){ 
			$("#div_tabla_tallas").load(location.href + " #div_tabla_tallas");

			Swal.fire({
				icon: 'success',
				title: '¡Correcto!',
				text: $(this).find("result_text").text(),
				timer: 1000,
				timerProgressBar: true,
			})

		}
	});
}




function llenar_select_producto_tallas(id_producto, e){
	e.preventDefault();

	// console.log("ID Producto: " + id_producto);

	$.post("controller.php",
	{ 	
		action 		: "llenar_select_producto_tallas",
		id_producto : id_producto,
	}, end_llenar_select_producto_tallas);
}

function end_llenar_select_producto_tallas(xml){
	$(xml).find("response").each(function(){
        if($(this).find("result").text() == "ok"){
            $("#select_talla").html($(this).find("select_tallas").text());
			// console.log("ID Producto: "+ $(this).find("id_producto").text());

        }
    });
}



function agregar_producto_talla(id_producto){
	var id_talla = $("#select_talla").val();


	$.post("controller.php",
	{ 	
		action 		: "agregar_producto_talla",
		id_producto : id_producto,
		id_talla	: id_talla,
	}, end_agregar_producto_talla);
}


function end_agregar_producto_talla(xml){
	console.log("Llega aqwui");
	$(xml).find("response").each(function(i){		 
		if($(this).find("result").text()=="ok"){ 
			$('#modalAgregarTalla').modal('hide');
			$("#div_tabla_tallas").load(location.href + " #div_tabla_tallas");

			$("#form_agregar_talla")[0].reset(); //Limpiar formulario
			
			Swal.fire({
				icon: 'success',
				title: '¡Correcto!',
				text: $(this).find("result_text").text(),
				timer: 1000,
				timerProgressBar: true,
			})

			
		}else{
			$('#modalAgregarTalla').modal('hide');
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






function validarPrecio(precio) {
    // Expresión regular para validar precios en formato numérico con hasta dos decimales
    var regexPrecio = /^\d+(\.\d{1,2})?$/;

    // Comprobamos si el precio coincide con la expresión regular
    if (regexPrecio.test(precio)) {
        // El precio es válido
        return true;
    } else {
        // El precio no es válido
        return false;
    }
}

function validarCantidad(cantidad) {
    // Verificar si la cantidad es un número
    if (!isNaN(cantidad)) {
        // La cantidad es un número
        return true;
    } else {
        // La cantidad no es un número
        return false;
    }
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