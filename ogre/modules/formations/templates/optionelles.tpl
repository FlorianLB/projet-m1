<form action="{formurl $submitAction, array('id' => $id, 'id_formation' => $id_formation)}" method="post">
    {formurlparam}
    <table>
        <tr>
            <th>UEs</th>
            <th>Cochez si optionelle</th>
        </tr>
        {foreach $options as $id => $o}
            <tr>
                <th><label for="ue-{$id}">{$o['label']}</label></th>
                <th><input id="ue-{$id}" type="checkbox" value="{$id}" name="ues[]" {if $o['checked']}checked="checked"{/if}/></th>
            </tr>
        {/foreach}
    </table>
    <p><input type="submit" value="Sauvegarder" />
</form>




<p>
    <a href="{jurl 'formations~formations:view', array('id' => $id_formation)}" class="button icon back"><span>Retourner à la formation</span></a>
</p>