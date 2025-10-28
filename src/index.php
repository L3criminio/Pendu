<?php

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

<body class="bg-gray-800 flex justify-center items-center">
    <div class="w-175 h-125 bg-white rounded-2xl flex justify-center items-center flex-col">
        <h1 class="text-4xl text-center font-semibold text-gray-800 align-top">Jeu du pendu</h1>
        <div id="mot-affiche">
            _ _ _ _ _
        </div>

        <form id="form-lettre">
            <input type="text" id="input-lettre" maxlength="1" placeholder="Entrez une lettre ici...">
            <button type="submit">Essayer</button>
        </form>
    </div>

</body>

</html>