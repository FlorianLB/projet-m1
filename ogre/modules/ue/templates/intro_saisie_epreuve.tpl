{form $form, $submitAction}
    <p>
        <span>{ctrl_label 'formation'}</span>
        <span id="field_formation">{ctrl_control 'formation'}</span>
    </p>
    
    <p>
        <span>{ctrl_label 'semestre'}</span>
        <span id="field_semestre">{ctrl_control 'semestre'}</span>
    </p>
        
    <p>
        <span>{ctrl_label 'annee'}</span>
        <span id="field_annee">{ctrl_control 'annee'}</span>
    </p>
            
    <p>
        <span>{ctrl_label 'ue'}</span>
        <span id="field_ue">{ctrl_control 'ue'}</span>
    </p>
                
    <p>
        <span>{ctrl_label 'epreuve'}</span>
        <span id="field_epreuve">{ctrl_control 'epreuve'}</span>
    </p>


<div>{formsubmit}</div>

{/form}



<script type="text/javascript">{literal}
(function(){
    c = new jFormsJQControlString('formation', 'Formation');
    c.errInvalid='La saisie de "Formation" est invalide';
    c.errRequired='La saisie de "Formation" est obligatoire';
    c.required = true;
    jFormsJQ.tForm.addControl(c);
})();
$(document).ready(function (){
    var $label = $('#jforms_ue_intro_saisie_epreuve_formation_label');
    $label.append('<span class="jforms-required-star">*</span>');
});
{/literal}
</script>