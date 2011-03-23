{form $form, $submitAction, array('id'=>$id, 'id_formation' => $id_formation)}

    {formcontrols}
        <p>{ctrl_label} : </p>
        <p>{ctrl_control}</p>
    {/formcontrols}
    
    
    {formsubmit}
    
{/form}

<p>
    <a href="{jurl 'formations~formations:view', array('id' => $id_formation)}" class="button icon back"><span>Retourner à la formation</span></a>
</p>