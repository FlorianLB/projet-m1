{if $id === null}


{formfull $form, $submitAction}

{else}


{formfull $form, $submitAction, array('id'=>$id)}

<p>Modifier les semestres : </p>

{if isset($semestres)}
    <ul class="semestre">
        {foreach $semestres as $s}
            <li><a href="{jurl 'formations~semestre:view', array('id' => $s['id'], 'id_formation' => $id)}">{$s['libelle']}</a></li>
        {/foreach}
        </ul>
{/if}

{/if}



<p><a href="{jurl $listAction}" class="button icon back"><span>Retourner à la liste des formations</span></a></p>