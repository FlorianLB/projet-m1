<form action="{formurl $submitAction}" method="post">

    <div>{formurlparam}</div>

    <table>
        <tr>
            <th>Etudiants</th>
            <th>Note</th>
        </tr>
        {foreach $data as $item}
            <tr>
                <td><label for="input-{$item['etudiant']['num']}">{$item['etudiant']['nom']} - {$item['etudiant']['prenom']}</label></td>
                <td><input id="input-{$item['etudiant']['num']}" type="text" value="{if isset($item['valeur'])}{$item['valeur']}{/if}" /></td>
            </tr>
        {/foreach}
    </table>

</form>