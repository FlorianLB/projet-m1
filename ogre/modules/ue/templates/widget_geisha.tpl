<div class="widget">
    <h2>Importer fichier geisha</h2>
    
    <div class="content">
        {form $form, $submitAction}
        
            <p> {ctrl_label 'csv_geisha'} : {ctrl_control 'csv_geisha'}  <span class="spacer-left"></span><span class="spacer-left">{formsubmit}</span></p>
     
            <p> {ctrl_label 'annee'} : {ctrl_control 'annee'} <span class="spacer-left"></span><span class="spacer-left"></p>
     
        {/form}
    </div>
    
</div>