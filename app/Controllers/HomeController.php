<?php
declare(strict_types=1);
/**
 * Touche pas au klaxon — covoit-mvc/app/Controllers/HomeController.php
 *
 * @package App.Controllers
 * @author Bilal
 */

namespace App\Controllers;

use App\Models\TripModel;
/**
 * Contrôleur de la page d’accueil : liste des prochains trajets avec places disponibles.
 */

final class HomeController extends BaseController
{
/**
 * Action d’index / affichage.
 * @return void
 */
    public function index(): void
    {
        $model = new \App\Models\TripModel();
        $trips = $model->findUpcomingAvailableWithAuthor(10);
        $this->render('home', ['trips' => $trips], 'Accueil');
    }
}
