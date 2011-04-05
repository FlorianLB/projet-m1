<p><a class="button icon add" href="{jurl $createAction}"><span>Créer une nouvelle formation</span></a></p>
<table class="records-list">
<thead>
<tr>
    {foreach $properties as $propname}
    {if isset($controls[$propname])}
    <th>{$controls[$propname]->label|eschtml}</th>
    {else}
    <th>{$propname|eschtml}</th>
    {/if}
    {/foreach}
</tr>
</thead>
<tbody>
{foreach $list as $record}
<tr class="{cycle array('odd','even')}">
    {foreach $properties as $propname}
    <td>
        <a href="{jurl $viewAction,array('id'=>$record->$primarykey)}">
            {$record->$propname|eschtml}
        </a>
    </td>
    {/foreach}
</tr>
{/foreach}
</tbody>
</table>
{if $recordCount > $listPageSize}
    {pagelinks $listAction, array(),  $recordCount, $page, $listPageSize, $offsetParameterName }
{/if}


