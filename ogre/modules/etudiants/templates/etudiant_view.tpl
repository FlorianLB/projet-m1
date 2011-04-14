<div id="fiche-semestres">
    
    <div id="fiche" class="block">
        <h2 class="legend">Fiche</h2>
        {formdatafull $form}
    </div>

    <div class="block">
        <h2 class="legend">Inscriptions et options</h2>
        {zone 'etudiants~semestres_etudiant', array('num_etudiant' => $id)}
    </div>
    
</div>

    <div id="historique" class="block">
        <h2 class="legend">Historique</h2>
        {zone 'etudiants~historique_etudiant', array('num_etudiant' => $id)}
    </div>

<div class="clear"></div>


<div class="block">
    <h2 class="legend">Notes</h2>
    {zone 'etudiants~notes_etudiant', array('num_etudiant' => $id)}
</div>

<p>
    <a href="{jurl $editAction, array('id'=>$id)}" class="button icon edit"><span>Modifier l'etudiant</span></a>
    <a href="{jurl $deleteAction, array('id'=>$id)}" class="button icon delete" onclick="return confirm('Etes-vous sûr de vouloir supprimer cet etudiant ?')"><span>Supprimer l'etudiant</span></a>
    <a href="{jurl $listAction}" class="button icon back"><span>Retourner à la liste des etudiants</span></a>
</p>

{*
        <p>Formation : </p>
        <ul>
        {foreach $formations as $formation}
            <li>{$formation->code_formation}
            {if $formation->libelle != ''} : {$formation->libelle} {/if}{$formation->annee}
                <ul class="semestre">
                    {foreach $semestres as $semestre}
                        <li>
                            {if $formation->id_formation == $semestre->id_formation }Semestre {$semestre->num_semestre}
                            Option : {$semestre->options}{/if}
                        </li>
                    {/foreach}
                </ul>
                <a href="{jurl 'etudiants~etudiants:etu_semestres',  array('id'=>$id, 'id_formation'=>$formation->id_formation )}" class="button icon options"><span>Définir les semestres</span></a>
            </li>
        {/foreach}
        </ul>
*}
