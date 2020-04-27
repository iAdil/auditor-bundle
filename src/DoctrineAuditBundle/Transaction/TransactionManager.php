<?php

namespace DH\DoctrineAuditBundle\Transaction;

use DH\DoctrineAuditBundle\Configuration;
use DH\DoctrineAuditBundle\Model\Transaction;
use Doctrine\ORM\EntityManagerInterface;

class TransactionManager
{
    public const OPERATION_TYPE_INSERT = 'insert';
    public const OPERATION_TYPE_UPDATE = 'update';
    public const OPERATION_TYPE_REMOVE = 'remove';
    public const OPERATION_TYPE_ASSOCIATE = 'associate';
    public const OPERATION_TYPE_DISSOCIATE = 'dissociate';

    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @var TransactionProcessor
     */
    private $processor;

    /**
     * @var TransactionHydrator
     */
    private $hydrator;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;

        $this->processor = new TransactionProcessor($configuration);
        $this->hydrator = new TransactionHydrator($configuration);
    }

    public function getConfiguration(): Configuration
    {
        return $this->configuration;
    }

    public function populate(Transaction $transaction): void
    {
        $this->hydrator->hydrate($transaction);
    }

    public function process(Transaction $transaction): void
    {
        $this->processor->process($transaction);
    }

    /**
     * @param EntityManagerInterface $em
     *
     * @return EntityManagerInterface
     */
    public function selectStorageSpace(EntityManagerInterface $em): EntityManagerInterface
    {
        return $this->configuration->getEntityManager() ?? $em;
    }
}
