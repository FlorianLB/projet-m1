<p>Historique</p>
    
<ul class="historique">
    {foreach $inscriptions  as $annee => $inscs}
    <li>
        <span class="annee">{$annee}</span>
        <ul>
        {foreach $inscs as $i}
        <li class="{$i->statut}">
            {$i->code_formation} - Semestre {$i->num_semestre} ( {$i->libelle} )
        </li>
        {/foreach}
        </ul>
    </li>
    {/foreach}
</ul>