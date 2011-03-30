{if isset($form)}
    {formfull $form, $submitAction}
{/if}

{if isset($intro2)}
    <p><a href="{jurl 'ue~saisie_epreuve:intro'}" class="button icon back"><span>Retourner au choix de la formation</span></a></p>
{/if}

{if isset($intro1)}

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
{/if}