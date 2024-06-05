<?php
namespace Baton\T4g\Entity;

use PDO;

class TeamStudentsRepository extends BaseRepository {
//     public function getByName(string $name) {
//         $names = explode(" ", $name);
//         return $this->createQueryBuilder("p")
//                     ->where("p.playerFirstName = :firstName")
//                     ->andWhere("p.playerLastName = :lastName")
//                     ->setParameters(new ArrayCollection(array(new Parameter("firstName", $names[0]), new Parameter("lastName", $names[1]))))
//                     ->getQuery()->getResult();
//     }

    public function deleteForTeam(int $teamId) {
        return $this->getEntityManager()->getConnection()->executeStatement(sql: "DELETE FROM baton_team_students WHERE team_id = ?", params: [$teamId], types: [PDO::PARAM_INT]);
    }
}