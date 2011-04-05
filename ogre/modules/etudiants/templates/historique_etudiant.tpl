<p>Historique</p>
    
<ul>
    {foreach $inscriptions  as $annee => $inscs}
    <li>
        {$annee}
        <ul class="puce-fleche">
        {foreach $inscs as $i}
        <li>
            {$i->code_formation} - Semestre {$i->num_semestre} ( {$i->libelle} )
        </li>
        {/foreach}
        </ul>
    </li>
    {/foreach}
</ul>