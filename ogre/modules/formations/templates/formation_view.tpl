{formdatafull $form}

{if isset($semestres)}
    <ul class="semestre">
        {foreach $semestres as $s}
            <li><a href="{jurl 'formations~semestre:view', array('id' => $s['id'])}">{$s['libelle']}</a></li>
        {/foreach}
        </ul>
{/if}
<p>
    <a href="{jurl $editAction, array('id'=>$id)}" class="button icon edit"><span>Modifier la formation</span></a>
    <a href="{jurl $deleteAction, array('id'=>$id)}" class="button icon delete" onclick="return confirm('Etes-vous sûr de vouloir supprimer cette formation ?')"><span>Supprimer la formation</span></a>
    <a href="{jurl $listAction}" class="button icon back"><span>Retourner à la liste des formations</span></a>
</p>

