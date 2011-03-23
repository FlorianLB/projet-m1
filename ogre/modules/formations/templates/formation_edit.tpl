{if $id === null}


{formfull $form, $submitAction}

{else}


{formfull $form, $submitAction, array('id'=>$id)}


{/if}



<p><a href="{jurl $viewAction, array('id' => $id)}" class="button icon back"><span>Retourner à la formation</span></a></p>