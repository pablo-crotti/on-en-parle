<!DOCTYPE html>
<html>
<head>
    <title>Informations de connexion</title>
</head>
<body>
    <h1>Informations de connexion</h1>

    <p>Bienvenue, {{ $name }} !</p>

    <p>Voici vos informations de connexion :</p>

    <ul>
        <li><strong>Nom d'utilisateur :</strong> {{ $username }}</li>
        <li><strong>Mot de passe :</strong> {{ $password }}</li>
    </ul>

    <p>Vous pouvez vous connecter en utilisant ces informations.</p>

    <p>Merci !</p>
</body>
</html>
