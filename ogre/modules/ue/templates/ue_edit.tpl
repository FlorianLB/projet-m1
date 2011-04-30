{if $id === null}


{formfull $form, $submitAction}


<script>{literal}
    $(document).ready(function(){
        
        
        {/literal}
        
        {foreach $formules as $f}
        
        $( "#{$f['id']}" ).autocomplete({literal}{
	      source: {/literal}{$f['data']}{literal}
                ,minLength: 0
        }).focus(function(){
            if($(this).val()  == '')
                $(this).autocomplete("search"); 
        });
        
        {/literal}
        {/foreach}
        {literal}
    });
{/literal}
</script>



{else}
{formfull $form, $submitAction, array('id'=>$id)}
{/if}











<p><a href="{jurl $listAction}" class="button icon back"><span>Retourner à la liste des UEs</span></a></p>