{if $id === null}

<h1>{@crud.title.create@}</h1>
{formfull $form, $submitAction}

{else}

<h1>{@crud.title.update@}</h1>
{formfull $form, $submitAction, array('id'=>$id)}

{/if}



<p><a href="{jurl $listAction}" class="crud-link">{@jelix~crud.link.return.to.list@}</a></p>