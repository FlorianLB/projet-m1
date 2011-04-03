{formdatafull $form}

<p>Formation : </p>
<ul>
{foreach $formations as $formation}
    <li>{$formation->code_formation}
    {if $formation->libelle != ''} : {$formation->libelle} {/if}{$formation->annee}
        <ul class="semestre">
            {foreach $semestres as $semestre}
                <li>
                    {if $formation->id_formation == $semestre->id_formation }Semestre {$semestre->num_semestre}{/if}
                    Option : {$semestre->options}
                </li>
            {/foreach}
        </ul>
        <a href="{jurl 'etudiants~etudiants:etu_semestres',  array('id'=>$id, 'id_formation'=>$formation->id_formation )}" class="button icon options"><span>Définir les semestres</span></a>
    </li>
{/foreach}
</ul>


    {zone 'etudiants~historique_etudiant', array('num_etudiant' => $id)}


<p>
    <a href="{jurl $editAction, array('id'=>$id)}" class="button icon edit"><span>Modifier l'etudiant</span></a>
    <a href="{jurl $deleteAction, array('id'=>$id)}" class="button icon delete" onclick="return confirm('Etes-vous sûr de vouloir supprimer cet etudiant ?')"><span>Supprimer l'etudiant</span></a>
    <a href="{jurl $listAction}" class="button icon back"><span>Retourner à la liste des etudiants</span></a>
</p>

