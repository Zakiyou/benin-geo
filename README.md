# BeninGeo 🇧🇯

[![Latest Stable Version](https://img.shields.io/packagist/v/zakiyou/benin-geo.svg)](https://packagist.org/packages/zakiyou/benin-geo)
[![Total Downloads](https://img.shields.io/packagist/dt/zakiyou/benin-geo.svg)](https://packagist.org/packages/zakiyou/benin-geo)
[![License](https://img.shields.io/packagist/l/zakiyou/benin-geo.svg)](https://packagist.org/packages/zakiyou/benin-geo)
[![PHP Version Require](https://img.shields.io/packagist/php-v/zakiyou/benin-geo.svg)](https://packagist.org/packages/zakiyou/benin-geo)

**Package Laravel pour les données géographiques du Bénin : départements et communes.**

Un package Laravel simple et efficace qui fournit l'accès complet aux données géographiques administratives du Bénin, incluant tous les départements et leurs communes respectives.

## 📋 Table des matières

- [Fonctionnalités](#-fonctionnalités)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Utilisation](#-utilisation)
- [API Reference](#-api-reference)
- [Exemples avancés](#-exemples-avancés)
- [Gestion des erreurs](#-gestion-des-erreurs)
- [Tests](#-tests)
- [Compatibilité](#-compatibilité)
- [Contribution](#-contribution)
- [Licence](#-licence
- [Auteur](#-auteur)

## ✨ Fonctionnalités

- 📍 **Liste complète des 12 départements du Bénin**
- 🏘️ **Accès aux 77 communes organisées par département**
- 📊 **Statistiques et comptage des entités géographiques**
- 🛡️ **Gestion robuste des erreurs avec exceptions personnalisées**
- ⚡ **Performance optimisée avec mise en cache automatique**
- 🔧 **API simple et intuitive**
- 📱 **Compatible avec les dernières versions de Laravel**

## 🚀 Installation

Installez le package via Composer :

```bash
composer require zakiyou/benin-geo
```

## ⚙️ Configuration

### Laravel 5.5+
Laravel détecte automatiquement le package grâce à l'**auto-discovery**. Aucune configuration supplémentaire n'est requise.

### Laravel < 5.5
Pour les versions antérieures de Laravel, ajoutez manuellement le service provider dans `config/app.php` :

```php
'providers' => [
    // ...
    Zakiyou\BeninGeo\BeninGeoServiceProvider::class,
],
```

## 📖 Utilisation

### Utilisation de base

```php
<?php

use Zakiyou\BeninGeo\BeninGeo;

$geo = new BeninGeo();

// Récupérer tous les départements
$departments = $geo->departments();

// Récupérer les communes d'un département spécifique
$communes = $geo->communes('Atlantique');

// Compter les départements
$totalDepartments = $geo->countDepartments();

// Compter les communes d'un département
$countCommunes = $geo->countCommunes('Atlantique');

// Obtenir le nombre total de communes au Bénin
$totalCommunes = $geo->countTotalCommunes();
```

### Utilisation avec l'injection de dépendances

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Controller;
use Zakiyou\BeninGeo\BeninGeo;

class LocationController extends Controller
{
    public function index(BeninGeo $geo)
    {
        $departments = $geo->departments();
        
        return view('locations.index', compact('departments'));
    }
    
    public function communes($department, BeninGeo $geo)
    {
        $communes = $geo->communes($department);
        
        return response()->json($communes);
    }
}
```

## 📚 API Reference

### `departments(): array`
Retourne la liste complète des 12 départements du Bénin.

**Exemple de retour :**
```php
[
    'Alibori',
    'Atacora',
    'Atlantique',
    'Borgou',
    'Collines',
    'Couffo',
    'Donga',
    'Littoral',
    'Mono',
    'Ouémé',
    'Plateau',
    'Zou'
]
```

### `communes(string $department): array`
Retourne la liste des communes pour un département donné.

**Paramètres :**
- `$department` (string) : Nom du département

**Exemple :**
```php
$communes = $geo->communes('Atlantique');
// Retourne : ['Abomey-Calavi', 'Allada', 'Kpomassè', 'Ouidah', 'Sô-Ava', 'Toffo', 'Tori-Bossito', 'Zè']
```

### `countDepartments(): int`
Retourne le nombre total de départements (12).

### `countCommunes(string $department): int`
Retourne le nombre de communes dans un département spécifique.

**Paramètres :**
- `$department` (string) : Nom du département

### `countTotalCommunes(): int`
Retourne le nombre total de communes au Bénin (77).

## 🎯 Exemples avancés

### Création d'un formulaire de sélection en cascade

```php
<?php

// Controller
public function getLocationData(BeninGeo $geo)
{
    $departments = $geo->departments();
    $locationData = [];
    
    foreach ($departments as $department) {
        $locationData[$department] = [
            'communes' => $geo->communes($department),
            'count' => $geo->countCommunes($department)
        ];
    }
    
    return response()->json($locationData);
}
```

### Validation des données géographiques

```php
<?php

use Illuminate\Http\Request;
use Zakiyou\BeninGeo\BeninGeo;
use Zakiyou\BeninGeo\DepartmentNotFoundException;

public function validateLocation(Request $request, BeninGeo $geo)
{
    $department = $request->input('department');
    $commune = $request->input('commune');
    
    try {
        $communes = $geo->communes($department);
        
        if (!in_array($commune, $communes)) {
            return response()->json([
                'error' => 'La commune spécifiée n\'existe pas dans ce département.'
            ], 400);
        }
        
        return response()->json(['valid' => true]);
        
    } catch (DepartmentNotFoundException $e) {
        return response()->json([
            'error' => 'Département invalide : ' . $e->getMessage()
        ], 400);
    }
}
```

### Statistiques géographiques

```php
<?php

public function getStatistics(BeninGeo $geo)
{
    $statistics = [
        'total_departments' => $geo->countDepartments(),
        'total_communes' => $geo->countTotalCommunes(),
        'departments_details' => []
    ];
    
    foreach ($geo->departments() as $department) {
        $statistics['departments_details'][$department] = [
            'communes_count' => $geo->countCommunes($department),
            'communes_list' => $geo->communes($department)
        ];
    }
    
    return response()->json($statistics);
}
```

## 🛡️ Gestion des erreurs

Le package utilise une exception personnalisée pour gérer les départements inexistants :

```php
<?php

use Zakiyou\BeninGeo\BeninGeo;
use Zakiyou\BeninGeo\DepartmentNotFoundException;

$geo = new BeninGeo();

try {
    $communes = $geo->communes('DépartementInexistant');
} catch (DepartmentNotFoundException $e) {
    // Gestion de l'erreur
    echo "Erreur : " . $e->getMessage();
    // Log de l'erreur
    \Log::error('Département non trouvé: ' . $e->getMessage());
}
```

### Messages d'erreur disponibles

- `DepartmentNotFoundException` : Lancée quand un département demandé n'existe pas
- Le message d'erreur inclut la liste des départements valides disponibles

## 🧪 Tests

Pour exécuter les tests du package :

```bash
composer test
```

### Structure des tests
```
tests/
├── Unit/
│   ├── BeninGeoTest.php
│   └── ExceptionTest.php
└── Feature/
    └── IntegrationTest.php
```

## 📋 Compatibilité

| Version | PHP | Laravel | Status |
|---------|-----|---------|--------|
| 1.x | >= 8.0 | 10.x, 11.x, 12.x | ✅ Supporté |
| 1.x | >= 8.1 | 10.x, 11.x, 12.x | ✅ Recommandé |

### Dépendances
- **PHP** : >= 8.0
- **Laravel** : ^10.0 \| ^11.0 \| ^12.0
- **Illuminate Support** : ^10.0 \| ^11.0 \| ^12.0

## 🤝 Contribution

Les contributions sont les bienvenues ! Voici comment contribuer :

1. **Fork** le projet
2. Créez votre branche de fonctionnalité (`git checkout -b feature/AmazingFeature`)
3. **Committez** vos changements (`git commit -m 'Add some AmazingFeature'`)
4. **Push** vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrez une **Pull Request**

### Guidelines de contribution
- Suivez les standards de codage PSR-12
- Ajoutez des tests pour toute nouvelle fonctionnalité
- Mettez à jour la documentation si nécessaire
- Assurez-vous que tous les tests passent

## 📋 Roadmap

- [ ] Ajout des arrondissements pour chaque commune
- [ ] Support des coordonnées géographiques
- [ ] API pour les codes postaux
- [ ] Interface en ligne pour explorer les données
- [ ] Export des données en différents formats (JSON, CSV, XML)

## 📄 Licence

Ce package est sous licence **MIT**. Voir le fichier [LICENSE](LICENSE) pour plus de détails.

## 👨‍💻 Auteur

**Zakiyou BABABODI**
- Email : ton.email@example.com
- GitHub : [@zakiyou](https://github.com/zakiyou)
- LinkedIn : [Zakiyou BABABODI](https://linkedin.com/in/zakiyou-bababodi)

## 🙏 Remerciements

- Merci à la communauté Laravel pour l'inspiration
- Données géographiques officielles de la République du Bénin
- Tous les contributeurs qui ont aidé à améliorer ce package

---

<div align="center">
  <p>Fait avec ❤️ pour la communauté des développeurs béninois</p>
  <p>
    <a href="https://github.com/zakiyou/benin-geo/issues">🐛 Signaler un bug</a> •
    <a href="https://github.com/zakiyou/benin-geo/discussions">💬 Discussions</a> •
    <a href="https://packagist.org/packages/zakiyou/benin-geo">📦 Packagist</a>
  </p>
</div>