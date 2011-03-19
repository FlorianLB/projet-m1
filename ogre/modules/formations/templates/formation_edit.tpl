{if $id === null}


{formfull $form, $submitAction}

{else}


{formfull $form, $submitAction, array('id'=>$id)}

{/if}



<p><a href="{jurl $listAction}" class="crud-link">Retourner à la liste des formations</a>.</p>