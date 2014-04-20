<?php

namespace Club\BookingBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * PlanRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PlanRepository extends EntityRepository
{
    public function getActive()
    {
        $date = new \DateTime();
        $date->modify('-7 day');

        return $this->createQueryBuilder('p')
            ->where('p.updated_at > :date')
            ->orWhere('p.end > :now')
            ->setParameter('date', $date)
            ->setParameter('now', new \DateTime())
            ->getQuery()
            ->getResult()
            ;
    }

    public function getQuery(\DateTime $date)
    {
        $ends_on = clone $date;
        $ends_on->modify('-1 day');

        $qb = $this->createQueryBuilder('p')
            ->select('p,pr')
            ->join('p.fields', 'f')
            ->join('p.plan_repeats', 'pr')
            ->where('(p.repeating = false) OR (pr.ends_type <> :ends_type) OR (pr.ends_type = :ends_type AND pr.ends_on > :ends_on)')
            ->setParameter('ends_type', 'on')
            ->setParameter('ends_on', $ends_on);

        return $qb;
    }

    public function getICSByLocation(\Club\UserBundle\Entity\Location $location, \DateTime $date)
    {
        $plans = $this->getQuery($date)
            ->andWhere('f.location = :location')
            ->setParameter('location', $location->getId())
            ->getQuery()
            ->getResult();

        return $this->getIcsFromPlans($plans);
    }

    public function getICSByField(\Club\BookingBundle\Entity\Field $field, \DateTime $date)
    {
        $plans = $this->getQuery($date)
            ->andWhere('f.id = :field')
            ->setParameter('field', $field->getId())
            ->getQuery()
            ->getResult();

        return $this->getIcsFromPlans($plans);
    }

    public function getIcsFromPlans($plans)
    {
        $ics = <<<EOF
BEGIN:VCALENDAR
VERSION:2.0

EOF;

        foreach ($plans as $plan) {
            if ($plan->getRepeating()) {
                foreach ($plan->getPlanRepeats() as $repeat) {
                    $ics .= $this->addFreq($repeat);
                }

            } else {
                $ics .= $this->addEvent($plan);
            }
        }

        $ics .= <<<EOF
END:VCALENDAR
EOF;

        return $ics;
    }

    public function getBetweenByField(\Club\BookingBundle\Entity\Field $field, \DateTime $start, \DateTime $end)
    {
        $ics = $this->getICSByField($field, $start);

        return $this->getPlansFromIcs($ics, $start, $end);
    }

    public function getPlansFromIcs($ics, \DateTime $start, \DateTime $end)
    {
        $calendar = \Sabre\VObject\Reader::read($ics);
        $calendar->expand($start, $end);

        $plans = array();
        if (count($calendar->VEVENT)) {
            foreach ($calendar->VEVENT as $event) {

                preg_match("/^(\d+)_/", $event->UID, $o);
                $plan_id = $o[1];
                $plan = $this->_em->find('ClubBookingBundle:Plan', $plan_id);

                $s = $plan->getStart();
                $s->setDate(
                    $event->DTSTART->getDateTime()->format('Y'),
                    $event->DTSTART->getDateTime()->format('m'),
                    $event->DTSTART->getDateTime()->format('d')
                );
                $e = $plan->getEnd();
                $e->setDate(
                    $event->DTEND->getDateTime()->format('Y'),
                    $event->DTEND->getDateTime()->format('m'),
                    $event->DTEND->getDateTime()->format('d')
                );

                $plan->setStart($s);
                $plan->setEnd($e);

                $plans[] = $plan;
            }
        }

        return $plans;
    }

    public function getBetweenByLocation(\Club\UserBundle\Entity\Location $location, \DateTime $start, \DateTime $end)
    {
        $ics = $this->getICSByLocation($location, $start);

        return $this->getPlansFromIcs($ics, $start, $end);
    }

    private function addFreq(\Club\BookingBundle\Entity\PlanRepeat $repeat)
    {
        $plan = $repeat->getPlan();

        $exception = '';
        foreach ($plan->getPlanExceptions() as $e) {
            $exception .= 'EXDATE:'.$e->getExcludeDate()->format('Ymd\THis').PHP_EOL;
        }

        $ics = <<<EOF
BEGIN:VEVENT
UID:{$repeat->getIcsUid()}
DTSTAMP:{$plan->getCreatedAt()->format('Ymd\THis')}
DTSTART:{$plan->getStart()->format('Ymd\THis')}
DTEND:{$plan->getEnd()->format('Ymd\THis')}
SUMMARY:{$plan->getName()}
RRULE:{$repeat->getIcsFreq()}
{$exception}
END:VEVENT

EOF;

        return $ics;
    }

    private function addEvent(\Club\BookingBundle\Entity\Plan $plan)
    {
        $ics = <<<EOF
BEGIN:VEVENT
UID:{$plan->getIcsUid()}
DTSTAMP:{$plan->getCreatedAt()->format('Ymd\THis')}
DTSTART:{$plan->getStart()->format('Ymd\THis')}
DTEND:{$plan->getEnd()->format('Ymd\THis')}
SUMMARY:{$plan->getName()}
END:VEVENT

EOF;

        return $ics;
    }
}
