{formdatafull $form}

{foreach $formations as $formation}
    <p>{$formation}</p>    
{/foreach}

<p>
    <a href="{jurl $editAction, array('id'=>$id)}" class="button icon edit"><span>Modifier l'etudiant</span></a>
    <a href="{jurl $deleteAction, array('id'=>$id)}" class="button icon delete" onclick="return confirm('Etes-vous sûr de vouloir supprimer cet etudiant ?')"><span>Supprimer l'etudiant</span></a>
    <a href="{jurl $listAction}" class="button icon back"><span>Retourner à la liste des etudiants</span></a>
</p>

