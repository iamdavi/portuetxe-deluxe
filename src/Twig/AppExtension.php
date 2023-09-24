<?php

namespace App\Twig;

use DateTime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
	public function getFilters(): array
	{
		return [
			new TwigFilter('diffTime', [$this, 'getDiffTime']),
		];
	}

	public function getDiffTime(mixed $date): string
	{
		$now = new DateTime();
		$diff = $date->diff($now);
		list(
			$month,
			$days,
			$hours,
			$mins,
			$sec
		) = explode(',', $diff->format('%m,%d,%h,%i,%s'));

		if ($month > 0) {
			return $month . 'mes.';
		} else if ($days > 0) {
			return $days . 'd.';
		} else if ($hours > 0) {
			return $hours . 'h.';
		} else if ($mins > 0) {
			return $mins . 'min.';
		} else {
			return $sec . 's.';
		}
	}
}
