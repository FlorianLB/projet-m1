{formdatafull $form}


{foreach $semestres as $num => $semestre}
    <p>Semestre {$num}</p>
        <ul class="semestre">
            {foreach $semestre as $ue}
                <li>
                    {$ue->code_ue}
                    {if $ue->libelle != ''} : {$ue->libelle} {/if}
                    {if $ue->optionelle == "1"} (option) {/if}
                </li>
            {/foreach}
        </ul>
{/foreach}

<p>
    <a href="{jurl $editAction, array('id'=>$id)}" class="button icon edit"><span>Modifier la formation</span></a>
    <a href="{jurl $deleteAction, array('id'=>$id)}" class="button icon delete" onclick="return confirm('Etes-vous sûr de vouloir supprimer cette formation ?')"><span>Supprimer la formation</span></a>
    <a href="{jurl $listAction}" class="button icon back"><span>Retourner à la liste des formations</span></a>
</p>

