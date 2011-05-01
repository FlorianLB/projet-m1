<?php


class testUtilsFormule extends UnitTestCase {
 
    function testParseFormuleUe() {
        jClasses::inc('utils~Formule');
        
        //Test1
        $this->assertIdentical(Formule::parseFormuleUe(78), null);
       
       
        //Test2
        $formule = '2PA1 + 3 PA2 + EvC';
        $resultat_attendu = array(
            array('2PA1', '3 PA2', ' EvC'),
            array('2', '3', '1'),
            array('PA1', 'PA2', 'EvC')
        );
        $this->assertIdentical(Formule::parseFormuleUe($formule), $resultat_attendu);
        
        jLog::dump($resultat_attendu, 'Attendu');
        jLog::dump(Formule::parseFormuleUe($formule), 'Obtenu');
        
        
    
        //Test3
        $formule = ' PA + 2*EvC + 1* TP';
        $resultat_attendu = array(
            array(' PA', '2*EvC', '1* TP'),
            array('1', '2', '1'),
            array('PA', 'EvC', 'TP')
        );
        $this->assertIdentical(Formule::parseFormuleUe($formule), $resultat_attendu);

    

        
        //Test3
        $formule = ' SUP(PA1, 2PA2 ,evc)';
        $resultat_attendu = array(
            array(1=> 'PA1', 2=>'2PA2', 3=>'evc'),
            array(1=>'1',2=> '2', 3=>'1'),
            array(1=>'PA1',2=> 'PA2',3=> 'evc')
        );
        $this->assertIdentical(Formule::parseFormuleUe($formule), $resultat_attendu);
    
    
    
    }
}