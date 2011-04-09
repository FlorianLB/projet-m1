<form action="{formurl $submitAction, array('id' => $id, 'id_formation' => $id_formation)}" method="post">
    <div>{formurlparam}</div>
        <ul>
        {foreach $semestres as $semestre }
            <li>
                <label for="field_s{$semestre['id']}">{$semestre['label']}</label>
                <input id="field_s{$semestre['id']}" type="checkbox" value="{$semestre['id']}" name="semestres[]" {if $semestre['checked']}checked="checked"{/if}/>
            
                <ul class="options">
                {foreach $ueoption as $ue }
                        {if $ue['sem']==$semestre['id']}
                        <li>
                            <label for="field_ue{$ue['id']}">{$ue['label']}</label>
                            <input id="field_ue{$ue['id']}" type="checkbox" value="{$ue['id']}:{$ue['sem']}" name="ues[]" {if $ue['checked']}checked="checked"{/if}/>
                        </li>
                        {/if}
                {/foreach}
                </ul>
            </li>
        {/foreach}
        </ul>
    <p><input type="submit" value="Sauvegarder" /></p>
</form>