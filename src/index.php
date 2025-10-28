<?php
session_start();
if (isset($_POST['nouvelle_partie'])) {
    session_destroy();
    session_start();
}
$trouverMot = array('SEMILLANT', 'COLLINAIRE', 'DAMASQUINE', 'CHASUBLE', 'HIEMALE', 'EXHAUSTEUR', 'PERCLUS', 'PETRICHOR', 'IMMARCESCIBLE', 'CALLIPYGE', 'OBJURGATION', 'DYSTOPIE', 'PENDRILLON', 'ASSUETUDE', 'VERBATIM', 'BERGAMASQUE', 'ANONCHALIR', 'COMPENDIEUX');
if (!isset($_SESSION['mot_secret'])) {
    $_SESSION['mot_secret'] = $trouverMot[array_rand($trouverMot)];

    $longueur = strlen($_SESSION['mot_secret']);
    $_SESSION['lettres_trouvees'] = array_fill(0, $longueur, '_');
    $_SESSION['lettres_trouvees'][0] = $_SESSION['mot_secret'][0];
    $_SESSION['lettres_trouvees'][$longueur - 1] = $_SESSION['mot_secret'][$longueur - 1];
    $_SESSION['lettres_utilisees'] = [];
    $_SESSION['erreurs'] = 0;
}
$mot_choisi = $trouverMot[array_rand($trouverMot)];

if (isset($_POST['lettre'])) {
    $lettre = strtoupper($_POST['lettre']); 
    $lettre = trim($lettre);

    if (in_array($lettre, $_SESSION['lettres_utilisees'])) {
        $_SESSION['erreurs']++;
    } else {
        $_SESSION['lettres_utilisees'][] = $lettre;
        $mot = $_SESSION['mot_secret'];
        $trouve = false;
        
        for ($i = 0; $i < strlen($mot); $i++) {
            if ($mot[$i] === $lettre) {
                $_SESSION['lettres_trouvees'][$i] = $lettre;
                $trouve = true;
            }
        }

         if (!$trouve) {
            $_SESSION['erreurs']++;
        }
    }
}
$win = !in_array('_', $_SESSION['lettres_trouvees']);
$lose = $_SESSION['erreurs'] >= 6;

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="output.css">
    <script src="script.js"></script>
    <title>Pendu</title>
</head>

<body class="bg-gray-800 flex justify-center items-center" data-erreurs="<?php echo $_SESSION['erreurs']; ?>">
    <div class="w-175 h-200 bg-white rounded-2xl flex justify-center items-center flex-col mt-6 gap-7 shadow-md shadow-white">
        <h1 class="text-4xl text-center font-semibold text-gray-800 text-shadow-gray-800/50 text-shadow-md block mb-5">Jeu du pendu</h1>
        <p class="text-gray-800 font-semibold">Lettres utilisées : 
            <?php 
            if (!empty($_SESSION['lettres_utilisees'])) {
                echo implode(', ', $_SESSION['lettres_utilisees']); 
            } else {
                echo "Aucune";
            }
            ?>
        </p>
        <div id="mot-affiche" class="font-semibold text-xl">
            <?php echo implode(' ', $_SESSION['lettres_trouvees']); ?>
        </div>

        <form id="form-lettre" method="POST" action="" <?php if ($win || $lose) echo 'style="display:none;"'; ?>>
            <input type="text" id="input-lettre" name="lettre" maxlength="1" placeholder="Entrez une lettre ici..." class="w-40 h-5 ps-2 p-3 border-2 rounded border-gray-800"><br>
            <button type="submit" class="w-20 h-7 bg-gray-800 rounded text-white font-semibold block mt-1.5 m-auto">Essayer</button>
        </form>
        <?php if ($win || $lose): ?>
            <form method="POST" action="" class="mt-4">
                <button type="submit" name="nouvelle_partie" class="bg-gray-800 hover:cursor-pointer text-white px-6 py-3 rounded font-semibold">Nouvelle Partie</button>
            </form>
        <?php endif; ?>
        <?php if ($win): ?>
            <div class=" p-4 bg-green-100 border-2 border-green-500 rounded">
                <h2 class="text-2xl font-bold text-gray-800 text-center">Gagné!</h2>
                <p class="font-semibold text-center">Le mot était : <?php echo $_SESSION['mot_secret']; ?></p>
            </div>
        <?php elseif ($lose): ?>
            <div class="p-4 bg-red-100 border-2 border-red-500 rounded">
                <h2 class="text-2xl font-bold text-gray-800 text-center">BOUHOU LOSER!!!</h2>
                <p class="font-semibold text-center">Le mot était : <?php echo $_SESSION['mot_secret']; ?></p>
            </div>
        <?php endif; ?>
        <div class="w-inherit h-inherit p-7 px-12 bg-gray-800 rounded-2xl shadow-lg shadow-gray-800">
                <svg id="pendu" width="250" height="250">
                    <!--Potence du pendu -->
                    <line  y1="230" x2="250" y2="230" stroke="white" stroke-width="4"/>
                    <line x1="175" y1="20" x2="175" y2="230" stroke="white" stroke-width="4"/>
                    <line x1="75" y1="20" x2="230" y2="20" stroke="white" stroke-width="4"/>
                    <line x1="75" y1="18" x2="75" y2="50" stroke="white" stroke-width="4"/>
                    <line x1="175" y1="50" x2="150" y2="20" stroke="white" stroke-width="4"/>
                    <line x1="175" y1="70" x2="220" y2="20" stroke="white" stroke-width="4"/>
                    
                    <!--Corps du pendu -->
                    <circle id="tete" cx="75" cy="70" r="20" stroke="white" stroke-width="3" fill="none" style="display:none;"/>
                    <line id="corps" x1="75" y1="90" x2="75" y2="150" stroke="white" stroke-width="3" style="display:none;"/>
                    <line id="bras-gauche" x1="75" y1="110" x2="45" y2="130" stroke="white" stroke-width="3" style="display:none;"/>
                    <line id="bras-droit" x1="75" y1="110" x2="105" y2="130" stroke="white" stroke-width="3" style="display:none;"/>
                    <line id="jambe-gauche" x1="75" y1="150" x2="55" y2="190" stroke="white" stroke-width="3" style="display:none;"/>
                    <line id="jambe-droite" x1="75" y1="150" x2="95" y2="190" stroke="white" stroke-width="3" style="display:none;"/>
                </svg>
            </div>
    </div>

</body>

</html>