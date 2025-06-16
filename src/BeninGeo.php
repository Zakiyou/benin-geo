<?php

namespace Zakiyou\BeninGeo;

use Exception;

class DepartmentNotFoundException extends Exception {}

class BeninGeo
{
    private array $departments;
    private array $communes;

    public function __construct()
    {
        $this->departments = include __DIR__ . '/data/departments.php';
        $this->communes = include __DIR__ . '/data/communes.php';

        if (!is_array($this->departments) || !is_array($this->communes)) {
            throw new Exception("Les fichiers de données ne sont pas valides.");
        }

        // Normaliser les départements en minuscules pour gérer l'insensibilité à la casse
        $this->departments = array_map('strtolower', $this->departments);

        // Normaliser les clés des communes en minuscules
        $communesNormalized = [];
        foreach ($this->communes as $dep => $coms) {
            $communesNormalized[strtolower($dep)] = $coms;
        }
        $this->communes = $communesNormalized;
    }

    /**
     * Retourne la liste des départements (avec première lettre en majuscule)
     * 
     * @return array
     */
    public function departments(): array
    {
        return array_map(fn($dep) => ucfirst($dep), $this->departments);
    }

    /**
     * Retourne la liste des communes d'un département donné (insensible à la casse)
     * 
     * @param string $department
     * @return array
     * @throws DepartmentNotFoundException
     */
    public function communes(string $department): array
    {
        $depKey = strtolower($department);

        if (!in_array($depKey, $this->departments)) {
            throw new DepartmentNotFoundException("Le département '{$department}' n'existe pas.");
        }

        return $this->communes[$depKey] ?? [];
    }

    /**
     * Retourne le nombre de départements
     * 
     * @return int
     */
    public function countDepartments(): int
    {
        return count($this->departments);
    }

    /**
     * Retourne le nombre de communes d'un département donné (insensible à la casse)
     * 
     * @param string $department
     * @return int
     * @throws DepartmentNotFoundException
     */
    public function countCommunes(string $department): int
    {
        // Utilise la méthode communes() qui lance l'exception si besoin
        $communes = $this->communes($department);
        return count($communes);
    }

    /**
     * Retourne le nombre total de communes dans tout le Bénin
     * 
     * @return int
     */
    public function countTotalCommunes(): int
    {
        $total = 0;
        foreach ($this->communes as $communes) {
            $total += count($communes);
        }
        return $total;
    }
}
