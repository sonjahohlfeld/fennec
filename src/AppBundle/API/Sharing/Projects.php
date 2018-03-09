<?php

namespace AppBundle\API\Sharing;


use AppBundle\Entity\User\FennecUser;
use AppBundle\Entity\User\Permissions;
use AppBundle\Entity\User\Project;
use AppBundle\Service\DBVersion;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

class Projects
{
    private $manager;

    /**
     * Projects constructor.
     * @param $dbversion
     */
    public function __construct(DBVersion $dbversion)
    {
        $this->manager = $dbversion->getUserEntityManager();
    }

    public function execute($email, $projectId, $action){
        $user = $this->manager->getRepository(FennecUser::class)->findOneBy(array(
            'email' => $email
        ));
        $valid = $this->isValid($email, $user);
        if($valid === true){
            $project = $this->manager->getRepository(Project::class)->findOneBy(array(
                'webuserDataId' => $projectId
            ));
            $permission = $this->manager->getRepository(Permissions::class)->findOneBy(array(
                'webuser' => $user->getId(),
                'webuserData' => $projectId
            ));
            if($permission !== null){
                $this->manager->remove($permission);
            }
            $permission = new Permissions();
            $permission->setWebuser($user);
            $permission->setWebuserData($project);
            $permission->setPermission($action);
            $this->manager->persist($permission);
            $this->manager->flush();
        }
        return $response = [
            'error' => ($valid === true ? false : true),
            'message' => ($valid === true ? 'The permission '.$action.' was setted to user '.$user->getUsername().' successfully.' : $valid)
        ];
    }

    private function isValid($email, $user){
        $validator = Validation::createValidator();
        $violations = $validator->validate($email, array(
            new Email()
        ));
        if(count($violations) > 0){
            return (string) $violations;
        }
        if($user === null){
             return 'There exists no user for the email: '.$email;
        }
        return true;
    }
}