<?php
// src/Security/TokenInterface.php

namespace App\Security;

interface TokenInterface
{
    /**
     * Récupère le token associé à l'utilisateur.
     *
     * @return string Le token.
     */
    public function getToken(): string;

    /**
     * Définit un token pour l'utilisateur.
     *
     * @param string $token Le token à définir.
     */
    public function setToken(string $token): void;

    /**
     * Vérifie si le token est valide.
     *
     * @return bool True si le token est valide, sinon false.
     */
    public function isValid(): bool;

    /**
     * Supprime ou réinitialise le token.
     */
    public function resetToken(): void;
}
