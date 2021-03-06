<?php

namespace btsappli\StagesBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * StageRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class StageRepository extends EntityRepository
{
    public function getEntrepriseTuteurStage($id)
	{
	    // appel du gestionnaire d'entité avec une méthode spécifique au repository
		$gestionnaireEntite = $this -> _em;
		
		// écriture de la requête personnalisée
		$requete = $gestionnaireEntite->createQuery('SELECT e, t
												     FROM btsappliStagesBundle:Stage s
													 JOIN s.entreprise e
													 JOIN s.tuteur t
													 WHERE s.id = :id');
		
		// On définit la valeur du paramètre id de la requête
		$requete -> setParameter('id', $id);
		
		// On exécute la requête et on renvoie les résultats
		return $requete->getResult();
	}
}
