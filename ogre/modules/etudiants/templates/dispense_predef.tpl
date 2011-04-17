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
                            <input class="sal" id="sal_{$uniqid}" type="checkbox" value="{$uniqid}" name="sal[]" {if $disp->salarie == '1'}checked="checked"{/if} />
                        </td>
                        <td>
                            <input class="end" id="end_{$uniqid}" type="checkbox" value="{$uniqid}" name="end[]" {if $disp->endette == '1'}checked="checked"{/if} />
                        </td> 
                    </tr>
                {/foreach}
                    </tbody>
                </table>
                
                <p id="to-check">
                    <a class="button" rel="sal">Cocher tout Salarié</a>
                    <a class="button" rel="end">Cocher tout Endetté</a>
                </p>
                
            </li>
        {/foreach}
        </ul>
        
        <input type="submit" value="Sauvegarder" />
</form>

<script type="text/javascript">{literal}
    $(document).ready(function(){
        $('#to-check a').click(function(e){
            
            var classe = $(this).attr('rel');
            var $els = $(this).parent().prev('table').find('input.' + classe);
            var state = $els.attr('checked');
            
            if(state == true){
                $els.attr('checked', false);
                if(classe == 'sal')
                    $(this).html('Cocher tout Salarié');
                else
                    $(this).html('Cocher tout Endetté');
            }
            else{
                $els.attr('checked', true);
                if(classe == 'sal')
                    $(this).html('Décocher tout Salarié');
                else
                    $(this).html('Décocher tout Endetté');
            }
            
            e.preventDefault();
            return false;
        });   
    });
    
{/literal}   
</script> 