(function(){
        
    $(document).ready(function(){
    
        var getAnnees = function(formation){
            $.get('../index.php?module=ue&action=ajax:getAnnees', {'formation' : formation}, function(data){
                $('#field_annee').html(data);
                $('#jforms_ue_intro_saisie_epreuve').buttonset();
                loadUes();
            });
        }
        var getUes = function(formation, annee, semestre){
            $.get('../index.php?module=ue&action=ajax:getUes', {'formation' : formation, 'annee' : annee, 'semestre' : semestre}, function(data){
                $('#field_ue').html(data);
                $('#field_epreuve').html('');
                $('#jforms_ue_intro_saisie_epreuve').buttonset();
            });
        }
        var getEpreuves = function(ue){
            $.get('../index.php?module=ue&action=ajax:getEpreuves', {'ue' : ue}, function(data){
                $('#field_epreuve').html(data);
                $('#jforms_ue_intro_saisie_epreuve').buttonset();
            });
        }
        
        
        $('#jforms_ue_intro_saisie_epreuve_formation').change(function(){
            getAnnees($(this).val());
        });
        
        $('#field_annee, #field_semestre').delegate('input:radio', 'change', function(){
            loadUes();
        });
        $('#field_ue').delegate('input:radio', 'change', function(){
            loadEpreuves();
        });
        
       var loadUes = function(){
            getUes(
                $('#jforms_ue_intro_saisie_epreuve_formation').val(),
                $('#field_annee input:checked')[0].value,
                $('#field_semestre input:checked')[0].value
            );
        };
        
        var loadEpreuves = function(){
            getEpreuves($('#field_ue input:checked')[0].value);
        };

        $('#jforms_ue_intro_saisie_epreuve').buttonset();

        $('#jforms_ue_intro_saisie_epreuve input:radio').change(function(){
            
        });

    
    });
    
})();