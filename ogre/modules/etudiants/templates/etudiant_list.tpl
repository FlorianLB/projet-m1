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
            {if $propname == 'date_naissance'}
                {$record->$propname|date_format:"%d/%m/%Y"}
            {else}
                {$record->$propname|eschtml}
            {/if}
        </a>
    </td>
    {/foreach}
</tr>
{/foreach}
</tbody>
</table>
{if $recordCount > $listPageSize}
    {pagelinks $listAction, $params,  $recordCount, $page, $listPageSize, $offsetParameterName }
{/if}


