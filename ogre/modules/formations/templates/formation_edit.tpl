{if $id === null}


{formfull $form, $submitAction}

<p><a href="{jurl $listAction}" class="button icon back"><span>Retourner à la listes des formations</span></a></p>
{else}


{formfull $form, $submitAction, array('id'=>$id)}

<p><a href="{jurl $viewAction, array('id' => $id)}" class="button icon back"><span>Retourner à la formation</span></a></p>
{/if}
