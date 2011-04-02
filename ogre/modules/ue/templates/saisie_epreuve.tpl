<form action="{formurl $submitAction, array('id_semestre' => $id_semestre, 'id_epreuve' => $id_epreuve)}" method="post">

    <div>{formurlparam}</div>

    <table class="saisie-epreuve">
        <thead>
            <tr>
                <th>Etudiants</th>
                <th>Note</th>
            </tr>
        </thead>
        <tbody>
            {foreach $data as $item}
                <tr class="{cycle array('odd','even')}">
                    <td>
                        <label for="input-{$item['etudiant']['num']}">{$item['etudiant']['nom']} {$item['etudiant']['prenom']}</label>
                    </td>
                    <td>
                        <input id="input-{$item['etudiant']['num']}" type="text" value="{if isset($item['valeur'])}{if $item['valeur'] == -1}ABS{else}{$item['valeur']}{/if}{/if}" name="note[{$item['etudiant']['num']}]"/>
                    </td>
                </tr>
            {/foreach}
        </tbody>
    </table>

        <p>Mettre ABS si l'etudiant était absent lors de l'epreuve</p>

    <p><input type="submit" value="Sauvegarder"/>
</form>