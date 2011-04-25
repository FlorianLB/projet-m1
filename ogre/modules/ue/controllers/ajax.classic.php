<?php


class ajaxCtrl extends jController {

    function getAnnees() {
        $rep = $this->getResponse('htmlfragment');
        
       $annees = jDao::get('formations~formation')->getByCode($this->param('formation'));
        
        $result = '';
        
        $i = 0;
        foreach($annees as $a){
                $checked = ($i == 0) ? 'checked="checked"' : '';
                
            $result .= '
                <label class="ui-button ui-widget" for="annee'.$a->annee.'">'.$a->annee.'</label>
                <input id="annee'.$a->annee.'" type="radio" name="annee" value="'.$a->annee.'" '.$checked.'/>
            ';
            $i++;
        }
        
        $rep->addContent($result);
        return $rep;
    }
    
    function getUes() {
        $rep = $this->getResponse('htmlfragment');
        
       $annees = jDao::get('formations~formation')->getByCode($this->param('formation'));
        
        $formation = jDao::get('formations~formation')->getByCodeAnnee($this->param('formation'), $this->param('annee'));
        $semestre = jDao::get('formations~semestre')->getByFormationNum($formation->id_formation, $this->param('semestre'));
        
        
        $result = '';
        foreach(jDao::get('formations~ue_semestre_ue')->getBySemestre($semestre->id_semestre) as $ue){
            $result .= '
                <label title="'.$ue->libelle.'"class="ui-button ui-widget" for="ue'.$ue->id_ue.'">'.$ue->code_ue.'</label>
                <input id="ue'.$ue->id_ue.'" type="radio" name="ue" value="'.$ue->id_ue.'""/>
            ';
        }
        
        $rep->addContent($result);
        return $rep;
    }
    
    
    function getEpreuves() {
        $rep = $this->getResponse('htmlfragment');
        
       $epreuves = jDao::get('ue~epreuve')->getByUe($this->param('ue'));
        
        $result = '';
        $rattrapage = '';
        
        $i = 0;
        foreach($epreuves as $e){
            $checked = ($i == 0) ? 'checked="checked"' : '';
              
            if( $e->rattrapage == 0)  
                $result .= '
                    <label class="ui-button ui-widget" for="ep'.$e->id_epreuve.'">'.$e->type_epreuve.'</label>
                    <input id="ep'.$e->id_epreuve.'" type="radio" name="epreuves" value="'.$e->id_epreuve.'" '.$checked.'/>
                ';
            else
                $rattrapage .= '
                    <label class="ui-button ui-widget" for="ep'.$e->id_epreuve.'">'.$e->type_epreuve.'</label>
                    <input id="ep'.$e->id_epreuve.'" type="radio" name="epreuves" value="'.$e->id_epreuve.'" '.$checked.'/>
                ';
            $i++;
        }
        if( !empty($rattrapage)){
            $rattrapage = '<span> Rattrapage : ' . $rattrapage . '</span>';
            $result .= $rattrapage;
        }
        
        $rep->addContent($result);
        return $rep;
    }
    
}
