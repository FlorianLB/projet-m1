<form action="{formurl $submitAction, array('num_etudiant' => $num_etudiant)}" method="post">
    
        {formurlparam}
    
        <ul class="saisie-note">
        {foreach $notes as $id_s => $ar}
            <li>
                <strong>{$libelle['formation'][$id_s]} - Semestre {$libelle['semestre'][$id_s]}</strong>
                <ul>
                {foreach $ar as $id_ue => $notes}
                    <li>
                        <table>
                            <thead>
                                <tr><th colspan="3">
                                    <span>{$libelle['ue'][$id_s][$id_ue]}</span><br/>
                                    <span>
                                        <label for="moy_{$id_ue}">Moyenne</label>
                                        <input id="moy_{$id_ue}" type="text" disabled="disabled" value="{$moyennes[$id_s][$id_ue]}"/>
                                    </span>
                                </th></tr>
                            </thead>
                            <tbody>
                        {foreach $notes as $note}

                            {assign $uniqid = $note->id_epreuve.':'.$note->id_semestre}
                        
                            <tr class="{cycle array('odd', 'even')}">
                                <td><label for="note_{$uniqid}">{$note->type_epreuve}</label></td>
                                <td>
                                    <input id="note_{$uniqid}" type="text" value="{if $note->valeur != ''}{if $note->valeur == -1}ABS{elseif $note->valeur == -2}DISP{else}{$note->valeur|round:2}{/if}{/if}" name="note[{$uniqid}]" {if $note->valeur == -2}disabled="disabled"{/if}/>
                                </td>
                                <td>
                                    {if $note->n_statut == 2}(importé){/if}
                                </td>
                            </tr>
                        {/foreach}
                            </tbody>
                        </table>
                    </li>
                {/foreach}
                </ul>
            </li>
        {/foreach}
        </ul>
        
        <p>Mettre ABS si l'élève était absent.</p>
        
        <input type="submit" value="Sauvegarder" />
</form>