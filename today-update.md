# Bilan des travaux du jour

## Ajouts et modifications

- Transformation du bouton "Investir" sur la page VIP en action d’activation réelle :
  - nouveau route `POST /dashboard/vip-plans/{vipPlan}/invest`
  - création d’une `Investment` active lorsque le solde disponible est suffisant
  - décrément du `wallet_balance` utilisateur au moment de l’investissement
  - validation du solde disponible et message d’erreur si insuffisant

- Ajout du suivi de parrainage côté client :
  - nouvelle page `dashboard/team`
  - vue client affichant les filleuls directs avec leur nom, téléphone et date d’inscription
  - lien vers cette page depuis le dashboard client

- Ajout du suivi de parrainage côté admin :
  - affichage des 3 premiers parrains directs de la semaine sur le dashboard admin
  - sélection basée sur les filleuls créés durant les 7 derniers jours

## Fichiers créés / modifiés

- `routes/web.php`
- `app/Http/Controllers/Client/DashboardController.php`
- `app/Http/Controllers/Admin/DashboardController.php`
- `resources/views/client/team.blade.php`
- `resources/views/client/dashboard.blade.php`
- `resources/views/admin/dashboard.blade.php`
- `resources/views/client/vip_plans.blade.php`

## Vérification

- syntaxe PHP vérifiée pour les contrôleurs modifiés
- nouvelle route `client.team` enregistrée correctement
