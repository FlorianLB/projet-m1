{formdatafull $form}

<p>
    <a href="{jurl $editAction, array('id'=>$id)}" class="button icon edit"><span>Modifier la formation</span></a>
    <a href="{jurl $deleteAction, array('id'=>$id)}" class="button icon delete" onclick="return confirm('Etes-vous sûr de vouloir supprimer cette formation ?')"><span>Supprimer la formation</span></a>
</p>


{foreach $semestres as $num => $semestre}
    <p>Semestre {$num}</p>
        <ul class="semestre">
            {foreach $semestre['ues'] as $ue}
                <li>
                    {$ue->code_ue}
                    {if $ue->libelle != ''} : {$ue->libelle} {/if}
                    {if $ue->optionelle == "1"} (option) {/if}
                </li>
            {/foreach}
        </ul>
        <p>
            <a href="{jurl 'formations~semestre:view', array('id'=>$semestre['id'], 'id_formation' => $id)}" class="button icon edit"><span>Modifier le semestre {$num}</span></a>
            <a href="{jurl 'formations~semestre:uesoptionelles', array('id'=>$semestre['id'], 'id_formation' => $id)}" class="button icon options"><span>Définir les UES optionelles</span></a>
        </p>
{/foreach}


<p>
    <a href="{jurl $listAction}" class="button icon back"><span>Retourner à la liste des formations</span></a>
</p>


