<?php

namespace App\Security;

use App\Entity\Post;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PostVoter extends Voter
{
	// these strings are just invented: you can use anything
	const VIEW = 'view';
	const EDIT = 'edit';

	protected function supports(string $attribute, mixed $subject): bool
	{
		// if the attribute isn't one we support, return false
		if (!in_array($attribute, [self::VIEW, self::EDIT])) {
			return false;
		}

		// only vote on `Post` objects
		if (!$subject instanceof Post) {
			return false;
		}

		return true;
	}

	protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
	{
		$user = $token->getUser();

		if (!$user instanceof User) {
			return false;
		}

		/** @var Post $post */
		$post = $subject;

		return match ($attribute) {
			self::VIEW => $this->canView($post, $user),
			self::EDIT => $this->canEdit($post, $user),
			default => throw new \LogicException('This code should not be reached!')
		};
	}

	private function canView(Post $post, User $user): bool
	{
		// if they can edit, they can view
		if ($this->canEdit($post, $user)) {
			return true;
		}

		// the Post object could have, for example, a method `isPrivate()`
		return true;
	}

	private function canEdit(Post $post, User $user): bool
	{
		// this assumes that the Post object has a `getOwner()` method
		return $user === $post->getUser();
	}
}
