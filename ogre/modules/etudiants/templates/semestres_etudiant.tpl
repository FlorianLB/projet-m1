<script type="text/javascript">{literal}

$(document).ready(function(){
    
    $( "#semestres_etudiant" ).buttonset();
    
});




{/literal}
</script>


<ul id="semestres_etudiant">
    {foreach $formations as $formation}
        <li>
            {$formation->code_formation}
            {if $formation->libelle != ''} : {$formation->libelle} {/if}{$formation->annee}
        
            {zone 'etudiants~etu_1_semestre', array('num_etudiant'=>$num_etudiant, 'id_formation'=>$formation->id_formation )}
        </li>
    {/foreach}
</ul>