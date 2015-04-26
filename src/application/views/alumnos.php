<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Alumnos</title>
	<style>
		body{
			font-size:18px;
			font-family:'Arial',arial,serif;
			padding:20px;
		}
		input{
			font-size:18px;
			font-family:'Arial',arial,serif;
			padding:5px;
		}
	</style>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
</head>
<body>
	<div class="form">
		<label>Nombre: </label><input type="text"></input><br/>
		<label>Apellido paterno: </label><input type="text"></input><br/>
		<label>Apellido materno: </label><input type="text"></input><br/>
		<label>Edad: </label><input type="text" pattern="[0-9]*"></input><br/>
		---<br/>
		<label>Campus: </label><input type="text"></input><br/>
		<label>Titulación: </label><input type="text"></input><br/>
		<label>Curso: </label><input type="text"></input><br/>
		---<br/>
		<button>ENVIAR</button>
	</div>
	<div class="resultados" style="color:red;margin-top:50px"></div>
	<script type="text/javascript">

		var formdata;

		function populateFormData(){
			formdata = {
				nomAlumno: $(".form > input:eq(0)").val(),
				aPatAlumno: $(".form > input:eq(1)").val(),
				aMatAlumno: $(".form > input:eq(2)").val(),
				edadAlumno: $(".form > input:eq(3)").val(),
				Campus: $(".form > input:eq(4)").val(),
				Titulacion: $(".form > input:eq(5)").val(),
				Curso: $(".form > input:eq(6)").val(),
			}
		}

		function mensaje(msg){
			$(".resultados").html(msg);
			window.setTimeout(function(){
				$(".resultados").html("");
			},1000);
		}

		function clearForm(){
			$(".form > input").val("");
		}

		function validateForm(){
			return true; // as for now
		}

		function getCampus(){
			return $(".form > input:eq(4)").val();
		}


		$(document).ready(function(){
			$(".form > button").bind("click",function(){
				if(validateForm()){

					$(this).text("Enviando...");

					var campus = false;

					switch( getCampus() ){   // for future change of datatype
						case "tij":
							campus = "tij";
						break;
						case "mxl":
							campus = "mxl";
						break;
					}

					if(campus){

						populateFormData();

						$.post("<?php echo site_url('alumnos/insert'); ?>", formdata ,function(data){
							if(data.success){
								
								mensaje("Correcto");

								clearForm();

								$(".form > button").text("ENVIAR");

							}
						})

					}
					else{

						mensaje("Campus inválido");

						$(".form > button").text("ENVIAR");

					}

				}
				else{
					mensaje("Tienes un error");
				}
			})
		})
	</script>
</body>
</html>