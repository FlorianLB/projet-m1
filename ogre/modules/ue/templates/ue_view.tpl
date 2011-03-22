{formdatafull $form}

<p>
    <a href="{jurl $editAction, array('id'=>$id)}" class="button icon edit"><span>Modifier l'UE</span></a>
    <a href="{jurl $deleteAction, array('id'=>$id)}" class="button icon delete" onclick="return confirm('Etes-vous sûr de vouloir supprimer cette UE?')"><span>Supprimer l'UE</span></a>
    <a href="{jurl $listAction}" class="button icon back"><span>Retourner à la liste des UEs</span></a>
</p>

