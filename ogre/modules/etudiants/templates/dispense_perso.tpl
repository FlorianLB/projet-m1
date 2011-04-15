
<p>Sélectionnez les épreuves dont l'élève est dispensé</p>

<form action="{formurl $submitAction, array('num_etudiant' => $num_etudiant)}" method="post">
    
        {formurlparam}
    
        <ul class="saisie-disp">
        {foreach $dispenses as $id_s => $ar}
            <li>
                <h3>{$libelle['formation'][$id_s]} - Semestre {$libelle['semestre'][$id_s]}</h3>
                <ul>
                {foreach $ar as $id_ue => $dispenses}
                    <li>
                        <strong>{$libelle['ue'][$id_ue]}</strong>
                        <div>
                        {foreach $dispenses as $disp}

                            {assign $uniqid = $disp->id_epreuve.':'.$disp->id_semestre}
                                <span class="{cycle array('odd', 'even')}">
                                    <label for="disp_{$uniqid}">{$disp->type_epreuve}</label>
                                    <input id="disp_{$uniqid}" type="checkbox" value="{$uniqid}" name="disp[]" {if $disp->flag_dispense != ''}checked="checked"{/if} />
                                </span>
                        {/foreach}
                        </div>
                    </li>
                {/foreach}
                </ul>
            </li>
        {/foreach}
        </ul>
        
        <input type="submit" value="Sauvegarder" />
</form>