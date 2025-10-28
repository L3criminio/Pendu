window.onload = function() {
    
    let nbErreursTexte = document.body.getAttribute('data-erreurs');
    let nbErreurs = parseInt(nbErreursTexte);
    let parties = ['tete', 'corps', 'bras-gauche', 'bras-droit', 'jambe-gauche', 'jambe-droite'];
    
    for (let i = 0; i < parties.length; i++) {
        let element = document.getElementById(parties[i]);
        
        if (i < nbErreurs) {
            element.style.display = 'block';
        } else {
            element.style.display = 'none';
        }
    }
};