<p><a class="button icon back" href="{jurl 'etudiants~etudiants:view', array('id' => $num_etudiant)}"><span>Retour à la fiche étudiant</span></a></p>

<div class="block">
    <h2 class="legend">Dispenses classiques</h2>
    {zone 'etudiants~dispense_predef', array('num_etudiant' => $num_etudiant)}
</div>

<div class="block">
    <h2 class="legend">Dispenses personnalisées</h2>
    {zone 'etudiants~dispense_perso', array('num_etudiant' => $num_etudiant)}
</div>

<p><a class="button icon back" href="{jurl 'etudiants~etudiants:view', array('id' => $num_etudiant)}"><span>Retour à la fiche étudiant</span></a></p>