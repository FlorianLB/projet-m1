<div class="widget">
    <h2>Importer fichier geisha</h2>
    
    <div class="content">
        {form $form, $submitAction}
        
            <p> {ctrl_label 'csv_geisha'} : {ctrl_control 'csv_geisha'}  <span class="spacer-left"></span><span class="spacer-left">{formsubmit}</span></p>
     
            <p> {ctrl_label 'annee'} : {ctrl_control 'annee'} <span class="spacer-left"></span><span class="spacer-left"></p>
            <p> <a href="{jurl 'formations~annee:index'}" onclick="return confirm('Etes-vous sûr de vouloir générer une nouvelle année ?')">Generer une nouvelle année</a></p>
          
        {/form}
    </div>
    
</div>
