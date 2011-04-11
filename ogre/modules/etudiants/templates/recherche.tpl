<div class="widget">
    <h2>Rechercher un etudiant</h2>
    
    <div class="content">
        {form $form, $submitAction}
        
            <p> {ctrl_label 'num_etudiant'} : {ctrl_control 'num_etudiant'}  <span class="spacer-left">ou</span><span class="spacer-left">{ctrl_label 'nom'} : {ctrl_control 'nom'}</span></p>
     
            <div>{formsubmit}</div>
     
        {/form}
    </div>
    
</div>