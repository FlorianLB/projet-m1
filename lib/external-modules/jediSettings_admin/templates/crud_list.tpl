<h1>{@crud.title.list@}</h1>

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
    <th>&nbsp;</th>
</tr>
</thead>
<tbody>
{foreach $list as $record}
<tr class="{cycle array('odd','even')}">
    {foreach $properties as $propname}
    <td>{$record->$propname|eschtml}</td>
    {/foreach}
    <td>
        <a href="{jurl $viewAction,array('id'=>$record->$primarykey)}">
            <img src="{$j_basepath}assets/admin/images/zoom.png" alt="{@jelix~crud.link.view.record@}" title="{@jelix~crud.link.view.record@}"/>
        </a>

        <a href="{jurl $editAction, array('id'=>$record->$primarykey)}" class="crud-link">
            <img src="{$j_basepath}assets/admin/images/pencil.png" alt="{@jelix~crud.link.edit.record@}" title="{@jelix~crud.link.edit.record@}"/>
        </a>

        <a href="{jurl $deleteAction, array('id'=>$record->$primarykey)}" class="crud-link" onclick="return confirm('{@jelix~crud.confirm.deletion@}')">
            <img src="{$j_basepath}assets/admin/images/cross.png" alt="{@jelix~crud.link.delete.record@}" title="{@jelix~crud.link.delete.record@}"/>
        </a>
    </td>
</tr>
{/foreach}
</tbody>
</table>
{if $recordCount > $listPageSize}
<p class="record-pages-list">{@jelix~crud.title.pages@} : {pagelinks $listAction, array(),  $recordCount, $page, $listPageSize, $offsetParameterName }</p>
{/if}
<p><a href="{jurl $createAction}" class="crud-link">{@crud.create@}</a></p>

