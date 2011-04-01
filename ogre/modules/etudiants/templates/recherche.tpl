<div class="widget">
    <h2>Rechercher un etudiant</h2>
    
    <div class="content">
        {form $form, $submitAction}
        
           {formcontrols}
              <p> {ctrl_label} : {ctrl_control} </p>
           {/formcontrols}
        
          <p> {formsubmit} </p>
     
        {/form}
    </div>
    
</div>