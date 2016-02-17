    <?php


    //Permet de se connecter avec l'email au lieu de l'identifiant


    namespace FOS\UserBundle\Security;

    class EmailUserProvider extends UserProvider
    {
        /**
        * {@inheritDoc}
        */
        protected function findUser($username)
        {
            return $this->userManager->findUserByUsernameOrEmail($username);
        }
    }
