{if $id === null}


{formfull $form, $submitAction}

{else}


{formfull $form, $submitAction, array('id'=>$id)}

{/if}



<p><a href="{jurl $listAction}" class="button icon back"><span>Retourner à la liste des formations</span></a></p>