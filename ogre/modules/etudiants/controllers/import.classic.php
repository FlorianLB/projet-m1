<?php


class importCtrl extends jController {
    /**
    *
    */
    function index() {
        $rep = $this->getResponse('html');

        $rep->setTitle("Import d'étudiants");

        $form = jForms::create('import_apogee');

        $tpl = new jTpl();
        $tpl->assign('form', $form);
        $tpl->assign('submitAction', 'etudiants~import:doimport');

        $rep->body->assign('MAIN', $tpl->fetch('etudiants~import_apogee'));

        return $rep;
    }
    
    
    function doimport(){
        $rep = $this->getResponse('redirect');
        
        $form = jForms::fill('import_apogee');
        
        $realname = $_FILES['csv_apogee']['name'];
        
        $name = date('dMY') . '-' . time() . '.csv' ;
        
        if( !$form->saveFile('csv_apogee', JELIX_APP_VAR_PATH.'uploads/csv_apogee/', $name)) {
            jMessage::add("L'importation a echouée...");
            $rep->action = 'etudiants~import:index';
            return $rep;
        }

        jForms::destroy('import_apogee');


        jClasses::inc('utils~etudiantsApogee');
        jClasses::inc('utils~customSQL');

        $csvParser = new etudiantsApogee(JELIX_APP_VAR_PATH.'uploads/csv_apogee/'.$name);
        $etudiants = $csvParser->parse();

        $factory = jDao::get('etudiants~etudiants');
        $nb = 0;
        $dt = new jDateTime();
        
        foreach($etudiants as $etu){
            if(!customSQL::etudiantsExisteDeja($etu->num_etudiant)) {
                $etudiant = jDao::createRecord('etudiants~etudiants');
                
                $etudiant->num_etudiant = $etu->num_etudiant;
                $etudiant->sexe = $etu->sexe;
                $etudiant->nom = utf8_encode($etu->nom);
                $etudiant->prenom = utf8_encode($etu->prenom);
                $etudiant->nom_usuel = utf8_encode($etu->nom_usuel);
                
                //transformation de la date en format francais en format anglais
                $dt->setFromString($etu->date_naissance, jDateTime::LANG_DFORMAT);
                $etudiant->date_naissance = $dt->toString(jDateTime::DB_DFORMAT);
                
                $factory->insert($etudiant);
                
                // teste pour la gestion des dates
                if($nb > 10 && $nb < 20){
                    jLog::dump($etu);
                    jLog::dump($etudiant);
                }
                $nb++;
            }
        }

        //Logger::log('import_apogee', $realname,$nb);

        jMessage::add("L'importation a reussie !");

        $rep->action = 'etudiants~import:index';
        return $rep;
    }
    

    
    
}

