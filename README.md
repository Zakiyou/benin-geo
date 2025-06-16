# BeninGeo


**Package Laravel pour les données géographiques du Bénin : départements et communes.**

Un package Laravel simple et efficace qui fournit l'accès complet aux données géographiques administratives du Bénin, incluant tous les départements et leurs communes respectives.

## 📋 Table des matières

- [Fonctionnalités](#-fonctionnalités)
- [Installation](#-installation)
- [Utilisation](#-utilisation)
- [Gestion des erreurs](#gestion-des-erreurs)
- [Compatibilité](#-compatibilité)
- [Contribution](#-contribution)
- [Licence](#-licence)
- [Auteur](#-auteur)

## ✨ Fonctionnalités

- 📍 **Liste complète des 12 départements du Bénin**
- 🏘️ **Accès aux 77 communes organisées par département**
- 📊 **Statistiques et comptage des entités géographiques**
- 🛡️ **Gestion robuste des erreurs avec exceptions personnalisées**
- ⚡ **Performance optimisée avec mise en cache automatique**
- 📱 **Compatible avec les dernières versions de Laravel**

## 🚀 Installation

Installez le package via Composer :

```bash
composer require zakiyou/benin-geo
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


// Obtenir toutes les communes groupées par département
$groupedCommunes = $geo->allCommunesGrouped();
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

##  Gestion des erreurs

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


## 📋 Compatibilité

 PHP     | Laravel      
---------|-----------------
 >= 8.1  | 10.x, 11.x, 12.x

- **Illuminate Support** : ^10.0 \| ^11.0 \| ^12.0

## 🤝 Contribution

Les contributions sont les bienvenues !

## 📄 Licence

Ce package est sous licence **MIT**. Voir le fichier LICENSE pour plus de détails.

## 👨‍💻 Auteur

**Zakiyou BABABODI**
- Email : zakiyou.bababodi@gmail.com
- GitHub : [@zakiyou](https://github.com/zakiyou)

## 🙏 Remerciements

- Merci à la communauté Laravel pour l'inspiration
- Données géographiques officielles de la République du Bénin
- Tous les contributeurs qui ont aidé à améliorer ce package

---
