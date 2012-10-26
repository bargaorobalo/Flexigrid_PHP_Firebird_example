<?php
	session_start();
?>
<html>
<head>
		<link rel="stylesheet" type="text/css" href="flexigrid/css/flexigrid.css" />
	<script type="text/javascript" src="flexigrid/js/jquery-1.7.1.min.js"></script>
	<script type="text/javascript" src="flexigrid/js/flexigrid.js"></script>
</head>
<body>
	<table class="gridListarPessoas"></table>
	<script type="text/javascript">
	$(document).ready(function(){
		$(".gridListarPessoas").flexigrid({
			url : 'listarPessoasJson.php',
			dataType : 'json',
			colModel : [ {
				display  : 'ID',
				name 	 : 'IDUSUARIO',
				width 	 : 30,
				sortable : false,
				align 	 : 'center',
				hide	 : true
			}, {
				display  : 'Nome do Usuário',
				name	 : 'NOME',
				width 	 : 150,
				sortable : true,
				align 	 : 'left'
			}, {
				display  : 'email',
				name	 : 'LOGIN',
				width 	 : 130,
				sortable : true,
				align 	 : 'left'
			}, {
				display  : 'Identificador',
				name	 : 'CODIGO',
				width 	 : 60,
				sortable : true,
				align 	 : 'center'
			}, {
				display  : 'Perfil',
				name	 : 'PERFIL',
				width 	 : 100,
				sortable : true,
				align 	 : 'center'
			}, {
				display  : 'Ativo',
				name	 : 'ATIVO',
				width 	 : 25,
				sortable : true,
				align 	 : 'center'
			}, {
				display  : 'Telefone',
				name	 : 'TELEFONE',
				width 	 : 70,
				sortable : false,
				align 	 : 'left'
			}, {
				display  : 'Idioma',
				name	 : 'IDIOMA',
				width 	 : 90,
				sortable : false,
				align 	 : 'left'
			}, {
				display  : 'Observação',
				name	 : 'OBSERVACAO',
				width 	 : 150,
				sortable : false,
				align 	 : 'left'
			}, {
				display  : 'ID Perfil',
				name	 : 'IDPERFIL',
				width 	 : 30,
				sortable : false,
				align 	 : 'left',
				hide	 : true
			}, {
				display  : 'ID Idioma',
				name	 : 'IDIDIOMA',
				width 	 : 30,
				sortable : false,
				align 	 : 'left',
				hide	 : true
			}],
			buttons : [ {
				name 	: 'Adicionar',
				bclass  : 'add',
				onpress : add
			}, {
				name 	: 'Remover',
				bclass  : 'delete',
				onpress : remove
			}, {
				name	: 'Editar',
				bclass  : 'edit',
				onpress : edit
			}],
			searchitems : [
			{display: 'Nome do Usuario', name : 'NOME', isdefault: true},
			{display: 'Email', name: 'LOGIN'},
			{display: 'Identificador', name: 'CODIGO'},
			{display: 'Perfil', name: 'PERFIL'}
			],
			sortname	: "IDUSUARIO",
			sortorder	: "ASC",
			usepager	: true,
			useRp		: true,
			rp			: 10,
			params: [{name:'IDEMPRESA', value: <?php echo $_SESSION['IDEMPRESA']; ?>}, {name:'IDPERFIL', value: <?php echo $_SESSION['IDPERFIL']; ?>}],
			showTableToggleBtn: true,
			resizable	: false,
			width		: "auto",
			height		: 330,
			singleSelect: true
		});
		$(".lightboxClose").live('click',function(e){
			$("#lightbox").hide();
			$('#lightboxDinamicContent').empty();
			$(".gridListarPessoas").flexReload();
			e.preventDefault();
			return false;
		});

		function remove(com, grid){
			var dadosGrid = new Array();
			$('.trSelected', grid).each(function() {
				$(this).find("td div").each(function(i,n){
					dadosGrid[i] = $(this).text();
				});
			});
			if(dadosGrid[0] != null){
				if(confirm('Deseja realmente excluir o usuário? Não poderá ser desfeito.')){
					$.ajax({
						type: "POST",
						url: "removePessoas.php",
						data: "idPessoa="+dadosGrid[0]+"",
						success: function(data){
							if(data == 0){
                        		alert('OK, Usuário removido com sucesso!');
                        		$(".gridListarPessoas").flexReload();
                        	}else{
                        		alert('Houve um problema ao remover o usuário, contate o suporte');
                        	}

						}
					});
				}
			}
		}
		function edit(com, grid){
			var dadosGrid = new Array();
			$('.trSelected', grid).each(function() {
				$(this).find("td div").each(function(i,n){
					dadosGrid[i] = $(this).text();
				});
			});
			if(dadosGrid[0] != null){
				$.ajax({
					type: "POST",
					url: "library/usuarios/editPessoas.php",
					data: "idPessoa="+dadosGrid[0]+"",
					success: function(data){
						$("#lightbox").show();
						$('#lightboxDinamicContent').html(data);
					}
				});
			}
		}

		function add(){
			$.ajax({
	            url: "library/usuarios/addPessoas.php",
	            dataType: "html",
	            success: function(data){
	                $("#tabsNav ul li").hide();
	                $(".tabAddPeople").show();
	                $(".tabAddPeople").addClass('tabActive');
	                $("#tabsContent div").hide();
	                $("#tabsContent").show();
	                $("#tabAddPeople").show();
	                $(".dropdown button").removeClass('generalMenuActive');
	                $(".dropdown button").next().slideUp("fast");
	                $('#tabAddPeople').empty();
	                $('#tabAddPeople').append(data);
	            }
       		});
		};
	});
	</script>
</body>
</html>