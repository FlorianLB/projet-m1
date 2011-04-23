{formdatafull $form}

<p>
    {ifacl2 'ue.modify.ue'}
        <a href="{jurl $editAction, array('id'=>$id)}" class="button icon edit"><span>Modifier l'UE</span></a>
    {/ifacl2}
    {ifacl2 'ue.delete.ue'}
        <a href="{jurl $deleteAction, array('id'=>$id)}" class="button icon delete" onclick="return confirm('Etes-vous sûr de vouloir supprimer cette UE?')"><span>Supprimer l'UE</span></a>
    {/ifacl2}
    <a href="{jurl $listAction}" class="button icon back"><span>Retourner à la liste des UEs</span></a>
</p>

