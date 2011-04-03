<p>Historique</p>

<table>
    <tbody>
        {foreach $inscriptions  as $i}
        <tr>
            <td>{$i->code_formation} ({$i->annee})</td>
            <td>Semestre {$i->num_semestre} - {$i->statut}</td>
        </tr>
        {/foreach}
    </tbody>
</table>