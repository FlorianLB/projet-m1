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
    
    function index2() {
        $rep = $this->getResponse('html');

        $rep->setTitle("Import de notes");

        $form = jForms::create('import_pv');

        $tpl = new jTpl();
        $tpl->assign('form', $form);
        $tpl->assign('submitAction', 'etudiants~import:doimport_pv');

        $rep->body->assign('MAIN', $tpl->fetch('etudiants~import_apogee'));

        return $rep;
    }
    
    
    function doimport_pv(){
        $rep = $this->getResponse('redirect');
        

        $name = $_FILES['csv_pv']['tmp_name'];
       

        jClasses::inc('utils~NotePv');
        

        $csvParser = new NotePv($name);
        $nbAjout = $csvParser->parse();

        Logger::log('import_pv', $name,$nbAjout);

        jMessage::add("L'importation a reussie !");

        $rep->action = 'etudiants~import:index2';
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
        
        $factoryformation = jDao::get('formations~formation');
        $factorysemestre = jDao::get('formations~semestre');
        $factoryetu_semestre = jDao::get('etudiants~etudiants_semestre');
        $etudiant_formation_factory = jDao::get('formations~etudiants_semestre_semestre_formation');
        
        $nb = 0;
        $dt = new jDateTime();
        
        foreach($etudiants as $etu){
            //Ajouts de l'etudiant si il n'existe pas
            if(!customSQL::etudiantsExisteDeja($etu->num_etudiant)) {
                $etudiant = jDao::createRecord('etudiants~etudiants');
                
                $etudiant->num_etudiant = $etu->num_etudiant;
                $etudiant->sexe = $etu->sexe;
                $etudiant->nom = utf8_encode(strtoupper($etu->nom));
                $etudiant->prenom = utf8_encode(ucfirst(strtolower($etu->prenom)));
                $etudiant->nom_usuel = utf8_encode(strtoupper($etu->nom_usuel));
                
                $etudiant->adresse = utf8_encode($etu->adresse);
                $etudiant->code_postal = $etu->code_postal;
                $etudiant->ville =  utf8_encode($etu->ville);
                $etudiant->email = utf8_encode($etu->email);
                $etudiant->telephone = $etu->telephone;
                
                //transformation de la date en format francais en format anglais
                $dt->setFromString($etu->date_naissance, jDateTime::LANG_DFORMAT);
                $etudiant->date_naissance = $dt->toString(jDateTime::DB_DFORMAT);
                
                $factory->insert($etudiant);
                
                //Creations des futur entree de la bd
                $etudiant_semestre = jDao::createRecord('etudiants~etudiants_semestre');
                
                $etudiant_semestre->num_etudiant = $etu->num_etudiant;
                //Recuperation de l'id de la formation a partir de son code puis recuperation des id des semestres
                $idform = $factoryformation->getLastFormationByCode(utf8_encode($etu->formation));
                foreach($idform as $idformee)
                    $liste_semestre = $factorysemestre->getByFormation($idformee->id_formation);
                //Creation et insertion dans la table etudiant_semestre
                foreach($liste_semestre as $semestre){
                    $etudiant_semestre->id_semestre = $semestre->id_semestre;
                    $etudiant_semestre->statut = "ENC";
                    $factoryetu_semestre->insert($etudiant_semestre);
                }
                
            }else{

                //Recuperation des derniers formations de l'etudiants
                $etu_sem_for = $etudiant_formation_factory->getLastFormationByEtudiant($etu->num_etudiant);
                $i=0;
                $countENC=0;
                $countAJO=0;
                $countADM=0;
                foreach($etu_sem_for as $instance ){
                    switch($instance->statut){
                        case "ENC":
                        case "DET":$countENC++;break;
                        case "AJO":$countAJO++;break;
                        case "ADM":$countADM++;break;
                        case "AJC":
                            $countADM++;
                            //TODO import note et inscription semsetre suivant pris en charge par ADM
                            $liste_semestre = $factorysemestre->getByFormation($factoryformation->getOneLastFormationByCode($instance->code_formation)->id_formation);
                            foreach($liste_semestre as $semestre){
                                if($semestre->num_semestre == $instance->num_semestre){
                                    //Creations des futur entree de la bd
                                    $etudiant_semestre = jDao::createRecord('etudiants~etudiants_semestre');
                                    $etudiant_semestre->num_etudiant = $etu->num_etudiant;
                                    $etudiant_semestre->id_semestre = $semestre->id_semestre;
                                    $etudiant_semestre->statut = "DET";
                                    $factoryetu_semestre->insert($etudiant_semestre);
                                }
                            }
                            //TODO Calcul de la moyenne puis ajouts de la dispense si necessaire
                            $moyenne=Moyenne::calcAllMoyenne($instance->id_semestre,$instance->num_etudiant);
                            foreach($moyenne as $key => $moy){
                                //Si moyenne superieur a 10 on importe les notes dans la nouvelle ue ? et dispense
                                if($moy>10){
                                    //TODO IMPORT NOTE
                                    $dispense_factory=Jdao::get('etudiants~dispense');
                                    $dispense = jDao::createRecord('etudiants~dispense');
                                    $dispense->num_etudiant = $instance->num_etudiant;
                                    $dispense->id_semestre = $etudiant_semestre->id_semestre;
                                    $dispense->valide = TRUE;
                                    $dispense->commentaire = "Ue deja valide l'année d'avant";
                                    //$dispense->endette ?
                                    //Verifie qu'on prends bien la bonne ue
                                    $ue_factory=Jdao::get('ue~ue');
                                    $ue=$ue_factory->get($key);
                                    $dispense->id_ue =$ue_factory->getLastUe($ue->code_ue);
                                }
                            }
                        break;
                        default:break;
                        
                    }
                    $i++;
                    
                    
                }
                if($i==$countENC){
                    //TODO RIEN
                }else if ($i==$countAJO){
                    //Creations des futur entree de la bd
                    $etudiant_semestre = jDao::createRecord('etudiants~etudiants_semestre');                    
                    $etudiant_semestre->num_etudiant = $etu->num_etudiant;
                    //TODO Garder ses notes Ou Pas en cas de Ajourne
                    $idform = $factoryformation->getLastFormationByCode(utf8_encode($etu->formation));
                    foreach($idform as $idformee)
                        $liste_semestre = $factorysemestre->getByFormation($idformee->id_formation);
                    //Creation et insertion dans la table etudiant_semestre
                    foreach($liste_semestre as $semestre){
                        $etudiant_semestre->id_semestre = $semestre->id_semestre;
                        $etudiant_semestre->statut = "ENC";
                        $factoryetu_semestre->insert($etudiant_semestre);
                    }
                    
                }else if ($i==$countADM){
                    //Creations des futur entree de la bd
                    $etudiant_semestre = jDao::createRecord('etudiants~etudiants_semestre');                    
                    $etudiant_semestre->num_etudiant = $etu->num_etudiant;
                    //Passage a l'année suivante
                    $idform = $factoryformation->getLastFormationByCode(utf8_encode($etu->formation));
                    foreach($idform as $idformee)
                        $liste_semestre = $factorysemestre->getByFormation($idformee->id_formation);
                    //Creation et insertion dans la table etudiant_semestre
                    foreach($liste_semestre as $semestre){
                        $etudiant_semestre->id_semestre = $semestre->id_semestre;
                        $etudiant_semestre->statut = "ENC";
                        $factoryetu_semestre->insert($etudiant_semestre);
                    }
                    
                }
                
            }
                
                
                // teste pour la gestion des dates
               /* if($nb > 10 && $nb < 20){
                    jLog::dump($etu);
                    jLog::dump($etudiant);
                }*/
                $nb++;
        }

        Logger::log('import_apogee', $realname,$nb);

        jMessage::add("L'importation a reussie !");

        $rep->action = 'etudiants~import:index';
        return $rep;
    }
    

    
    
}

