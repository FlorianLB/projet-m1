(function(){
        
    $(document).ready(function(){
        
        
        $('#jforms_ue_ueform_formule').blur(function(){
            
            var formule = $(this).val();
            
            enleve(['EvC'], formule, $('#jforms_ue_ueform_formule_salarie'));
            enleve(['EvC', 'TP'], formule, $('#jforms_ue_ueform_formule_endette'));
            
            
            
        });
        
        
        function enleve(notes, value, dest){
            
            for(var i in notes){
                var regexp = new RegExp("( )?([+-])?( )?" + notes[i], "i");
                value = value.replace(regexp, '');
            }
            
            dest.val(value);
            
        }
        
        
        
    });
    
})();