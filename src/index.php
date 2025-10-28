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

<body class="bg-gray-800 flex justify-center items-center min-h-screen" data-erreurs="<?php echo $_SESSION['erreurs']; ?>">
    <div class="w-175 bg-white rounded-2xl flex justify-center items-center flex-col mt-6 mb-10 gap-7 shadow-xl shadow-gray-800 p-8">
        <h1 class="text-5xl text-center font-bold text-gray-800 mb-2" style="letter-spacing: 2px; text-shadow: 2px 2px 4px rgba(0,0,0,0.1);"> JEU DU PENDU </h1>
        <div class="w-full h-1 bg-linear-to-r from-transparent via-gray-800 to-transparent rounded-full mb-3"></div>
        <div class="w-full bg-gray-100 rounded-2xl p-5 border-2 border-gray-300">
            <p class="text-gray-800 font-semibold text-center mb-3">
                <span class="text-lg"> Lettres utilisées :</span>
            </p>
            <div class="flex flex-wrap justify-center gap-2 min-h-10">
                <?php
                if (!empty($_SESSION['lettres_utilisees'])) {
                    foreach ($_SESSION['lettres_utilisees'] as $lettre) {
                        echo '<span class="inline-block bg-gray-800 text-white px-3 py-1 rounded-lg font-semibold text-sm shadow-md">' . $lettre . '</span>';
                    }
                } else {
                    echo '<span class="text-gray-400 italic">Aucune</span>';
                }
                ?>
            </div>
        </div>

        <div id="mot-affiche" class="font-bold text-4xl tracking-wider bg-gray-800 text-white px-8 py-5 rounded-2xl shadow-lg" style="letter-spacing: 8px;">
            <?php echo implode(' ', $_SESSION['lettres_trouvees']); ?>
        </div>

        <form id="form-lettre" method="POST" action="" <?php if ($win || $lose) echo 'style="display:none;"'; ?> class="w-full flex flex-col items-center gap-4">
            <input type="text" id="input-lettre" name="lettre" maxlength="1" placeholder="Entrez une lettre..."
                   class="w-100 text-center text-2xl font-bold ps-2 p-4 border-4 rounded-2xl border-gray-800 focus:border-blue-600 focus:outline-none shadow-md uppercase"
                   style="transition: all 0.3s ease; text-transform: uppercase;">
            <button type="submit" class="w-50 bg-gray-800 hover:bg-gray-700 text-white px-8 py-4 rounded-2xl font-bold text-lg shadow-lg"
                    style="transition: all 0.3s ease; transform: translateY(0);"
                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 15px 30px rgba(0,0,0,0.3)';"
                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='';">
                ✅ Essayer
            </button>
        </form>
        <?php if ($win || $lose): ?>
            <form method="POST" action="" class="mt-4">
                <button type="submit" name="nouvelle_partie"
                        class="bg-gray-800 hover:bg-gray-700 text-white px-10 py-4 rounded-2xl font-bold text-lg shadow-xl"
                        style="transition: all 0.3s ease; transform: translateY(0);"
                        onmouseover="this.style.transform='translateY(-3px) scale(1.05)'; this.style.boxShadow='0 20px 40px rgba(0,0,0,0.4)';"
                        onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.boxShadow='';">
                     Nouvelle Partie
                </button>
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
        <div class="w-inherit h-inherit p-8 px-12 bg-gray-800 rounded-2xl shadow-2xl border-4 border-gray-700" style="box-shadow: 0 25px 50px rgba(0,0,0,0.5), inset 0 2px 4px rgba(255,255,255,0.1);">
                <svg id="pendu" width="250" height="250" style="filter: drop-shadow(2px 2px 4px rgba(0,0,0,0.3));">
                    <!--Potence du pendu -->
                    <line y1="230" x2="250" y2="230" stroke="#f0f0f0" stroke-width="5"/>
                    <line x1="175" y1="20" x2="175" y2="230" stroke="#f0f0f0" stroke-width="5"/>
                    <line x1="75" y1="20" x2="230" y2="20" stroke="#f0f0f0" stroke-width="5"/>
                    <line x1="75" y1="18" x2="75" y2="50" stroke="#f0f0f0" stroke-width="5"/>
                    <line x1="175" y1="50" x2="150" y2="20" stroke="#f0f0f0" stroke-width="4"/>
                    <line x1="175" y1="70" x2="220" y2="20" stroke="#f0f0f0" stroke-width="4"/>

                    <!--Corps du pendu -->
                    <circle id="tete" cx="75" cy="70" r="20" stroke="white" stroke-width="4" fill="none"/>
                    <line id="corps" x1="75" y1="90" x2="75" y2="150" stroke="white" stroke-width="4" style="display:none;"/>
                    <line id="bras-gauche" x1="75" y1="110" x2="45" y2="130" stroke="white" stroke-width="4" style="display:none;"/>
                    <line id="bras-droit" x1="75" y1="110" x2="105" y2="130" stroke="white" stroke-width="4" style="display:none;"/>
                    <line id="jambe-gauche" x1="75" y1="150" x2="55" y2="190" stroke="white" stroke-width="4" style="display:none;"/>
                    <line id="jambe-droite" x1="75" y1="150" x2="95" y2="190" stroke="white" stroke-width="4" style="display:none;"/>
                </svg>
            </div>
    </div>

</body>

</html>