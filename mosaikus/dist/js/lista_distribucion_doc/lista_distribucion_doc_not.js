
function cargar_autocompletado(){       
        
          $('.pasar').click(function() { !$('#origen option:selected').remove().appendTo('#destino'); total_per_sel();});  
            $('.quitar').click(function() { !$('#destino option:selected').remove().appendTo('#origen'); total_per_sel();});
            $('.pasartodos').click(function() { $('#origen option').each(function() { $(this).remove().appendTo('#destino'); }); total_per_sel();});
            $('.quitartodos').click(function() { $('#destino option').each(function() { $(this).remove().appendTo('#origen'); }); total_per_sel();});
            $('.submit').click(function() { $('#destino option').prop('selected', 'selected'); });
            
            $('#origen').on('dblclick', 'option', function() {
                !$('#origen option:selected').remove().appendTo('#destino');
                total_per_sel();
            });            
            
            String.prototype.capitalize = function() {
                return this.charAt(0).toUpperCase() + this.slice(1);
            }
            
            $('#b-id_personal').keyup(function() {
                procesar_filtrar_arbol();
            });
            
            $('#b-nombres').keyup(function() {
                procesar_filtrar_arbol();
            });
            $('#b-apellido_paterno').keyup(function() {
                procesar_filtrar_arbol();
            });
            $('#b-apellido_materno').keyup(function() {
                procesar_filtrar_arbol();
            });
    }
    
    function total_per_sel(){
        $("#total-pers-sel").html($('#destino option').length + ' Personas seleccionadas.');
    }
    
    
    function validar_ld_noti(doc){    
        if ( $('#destino option').length <= 0 ){
            $('#destino').attr('data-validation',"required");
        }else {
             $('#destino option').prop('selected', true);
            $('#destino').removeAttr('data-validation');
        }
        if($('#idFormulario').isValid()) {
            $( "#btn-guardar" ).html('Procesando..');
            $( "#btn-guardar" ).prop( "disabled", true );
            array = new XArray();
            if (doc.getElementById("opc").value == "new")
                array.setObjeto('ListaDistribucionDoc','guardar');
            else
                array.setObjeto('ListaDistribucionDoc','actualizar');
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.getForm('idFormulario');
            array.addParametro('import','clases.lista_distribucion_doc.ListaDistribucionDoc');
            xajax_Loading(array.getArray());
        }else{
        
        }
    }

    
    