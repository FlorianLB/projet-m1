<form action="{formurl $submitAction, array('id' => $id, 'id_formation' => $id_formation)}" method="post">
    {formurlparam}
        <p>Semestre Inscrit et Ues optionnel</p>
        <ul>
        {foreach $semestres as $semestre }
            <li>
                <label for="{$semestre['id']}">{$semestre['label']}</label>
                <input id="{$semestre['id']}" type="checkbox" value="{$semestre['id']}" name="semestres[]" {if $semestre['checked']}checked="checked"{/if}/>
            </li>
            {foreach $ueoption as $ue }
                <ul class="semestre">
                    {if $ue['sem']==$semestre['id']}
                    <li><label for="{$ue['id']}">{$ue['label']}</label>
                    <input id="{$ue['id']}" type="checkbox" value="{$ue['id']}" name="ues[]" {if $ue['checked']}checked="checked"{/if}/></li>
                    {/if}
                </ul>
            {/foreach}
        {/foreach}
        </ul>
    <p><input type="submit" value="Sauvegarder" />
</form>




<p>
    <a href="{jurl 'etudiants~etudiants:view', array('id' => $id)}" class="button icon back"><span>Retourner à l'étudiant</span></a>
</p>