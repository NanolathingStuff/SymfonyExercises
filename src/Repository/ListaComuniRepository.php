<?php

namespace App\Repository;

use App\Entity\ListaComuni;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ListaComuni|null find($id, $lockMode = null, $lockVersion = null)
 * @method ListaComuni|null findOneBy(array $criteria, array $orderBy = null)
 * @method ListaComuni[]    findAll()
 * @method ListaComuni[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ListaComuniRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ListaComuni::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(ListaComuni $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(ListaComuni $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * return ???
     */
    public function LoadDataFromFile(string $file) {
        $entityManager = $this->getEntityManager();
        ///home/nanolathingstuff/demo_project/src/files/listacomuni.csv
        $query = $entityManager->createQuery(
            'LOAD DATA 
            INFILE :file 
            INTO TABLE listacomuni 
            FIELDS TERMINATED BY "," 
            ENCLOSED BY '.'"'.' 
            LINES TERMINATED BY '.'\n'.' 
            IGNORE 1 ROWS;'
        )->setParameter('price', $file);

        return $query->getResult();
    }
    /**
     * return int
     */
    public function NumberOfRows(){
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT count(*)
            FROM App\Entity\ListaComuni'
        );

        // returns an array of  objects
        return $query->getResult();
    }
    /**
     * @return ListaComuni[]
     */
    /*public function findCityCode(string $province, string $city): array{
        // automatically knows to select ListaComuni
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT cod_fisco
            FROM lista_comuni
            WHERE provincia = :province
            AND comune = :city'
        )->setParameter('province', strtoupper($province))->setParameter('city', ucwords($city));

        // returns an array of  objects
        return $query->getResult();

    }*/
}
