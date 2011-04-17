<form action="{formurl $submitAction, array('num_etudiant' => $num_etudiant)}" method="post">
    
        {formurlparam}
    
        <ul class="saisie-disp">
        {foreach $dispenses as $id_s => $ar}
            <li>
                <h3>{$libelle['formation'][$id_s]} - Semestre {$libelle['semestre'][$id_s]}</h3>
                <table>
                    <thead>
                        <tr>
                            <th>UEs</th>
                            <th>Salarié</th>
                            <th>Endetté</th>
                        </tr>
                    </thead>
                    <tbody>
                {foreach $ar as $id_ue => $disp}
                    <tr class="{cycle array('odd', 'even')}">
                        <td>{$libelle['ue'][$disp->id_ue]}</td>
                        {assign $uniqid = $disp->id_ue.':'.$disp->id_semestre}
                        <td>
                            <input id="sal_{$uniqid}" type="checkbox" value="{$uniqid}" name="salarie[]" {if $disp->salarie != ''}checked="checked"{/if} />
                        </td>
                        <td>
                            <input id="end_{$uniqid}" type="checkbox" value="{$uniqid}" name="end[]" {if $disp->endette != ''}checked="checked"{/if} />
                        </td> 
                    </tr>
                {/foreach}
                    </tbody>
                </table>
            </li>
        {/foreach}
        </ul>
        
        <input type="submit" value="Sauvegarder" />
</form>