<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
// use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class UserVoter extends Voter
{

  // const FILL_CAND = 'CANDIDAT_FILL';
  // const FILL_RECR = 'RECRUITER_FILL';

	const EDIT = 'USER_EDIT';
	const DELETE = 'USER_DELETE';

	// private $security;
	
	public function __construct(
		private Security $security,
	) {
       $this->security = $security;
	}

	/**
	 * Determines if the attribute and subject are supported by this voter.
	 *
	 * @param string $attribute
	 * @param mixed $user The subject to secure, e.g. an object the user wants to access or any other PHP type
	 * @return bool
	 */
	protected function supports(string $attribute, $user): bool {
		if(!in_array($attribute, [self::EDIT, self::DELETE])){
		   	return false;
		}
		if(!$user instanceof User){
       return false;
		}
		return true;

		// pour faire sur une seule  ligne:
		// return in_array($attribute, [self::EDIT, self::DELETE]) && $user instanceof User;
	}
	
	/**
	 * Perform a single access check operation on a given attribute, subject and token.
	 * It is safe to assume that $attribute and $subject already passed the "supports()" method check.
	 *
	 * @param string $attribute
	 * @param mixed $user
	 * @param TokenInterface $token
	 * @return bool
	 */
	protected function voteOnAttribute(string $attribute, mixed $user, TokenInterface $token): bool {
		$user = $token->getUser();

		// on recup user a partir du token
		if(!$user instanceof UserInterface) return false;
	 
	 // on verifie si user est admin
	 if($this->security->isGranted('ROLE_ADMIN')) return true;
	//  if($this->security->isGranted('ROLE_SUPER_ADMIN')) return true;
   // on verif les permissions
	 switch($attribute){
		  case self::EDIT:
				// on verif si user peut editer
				return $this->canEdit();
			      break;
			case self::DELETE:
							// on verif si user peut SUPPRIMER
							return $this->canDelete();
						break;			
	 }
	}
	  

	private function canEdit(){
		return $this->security->isGranted('ROLE_ADMIN');
	}
	private function canDelete(){
		return $this->security->isGranted('ROLE_SUPER_ADMIN');
	}
}