{flubar}

<div id="header">
    <div class="container">
    
        <a href="{$j_basepath}"><img id="ogre" src="{$j_themepath}img/ogre.png" alt="Ogre" width="111" height="33"/></a>
    
        <ul id="menu">
            <li><a href="{$j_basepath}">Accueil</a></li>
            <li><a href="{jurl 'etudiants~etudiants:index'}">Etudiants</a></li>
            <li><a href="#">Examens</a></li>
            <li><a href="#">Paramètres</a></li>
        </ul>
        
        <div class="log">
            <span>
                {$authLogin} | <a href="{jurl 'jauth~login:out'}">Deconnexion</a>
            </span>
        </div>
        
    </div>
</div>
    
    <div id="sub-header">
        <div class="container">
            
        </div>
    </div>

    <div id="page">
        <div class="container">
            
            <div id="main" class="pannel">
                
                <div class="header">{$title}</div>
                
                {$MAIN}
                
            </div>
        </div>
    </div>