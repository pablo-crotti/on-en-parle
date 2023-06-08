<!DOCTYPE html>
<html>
<head>
    <title>Nouveau message de contact</title>
</head>
<body>
    <h2>Vous avez reçu un nouveau message de contact :</h2>
    <p><strong>Nom :</strong> {{ $data['nom'] }}</p>
    <p><strong>Email :</strong> {{ $data['email'] }}</p>
    <p><strong>Avis :</strong> {{ $data['avis'] }}</p>
</body>
</html>