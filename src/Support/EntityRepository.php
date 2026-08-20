<?php

namespace Bocapro\QuickbooksConnector\Support;

use Bocapro\QuickbooksConnector\Exceptions\QuickbooksException;

/**
 * Thin CRUD wrapper around a single QuickBooks Online entity type.
 *
 * Concrete modules (invoices, payments, ...) extend this and only declare
 * the entity name and its SDK facade class.
 */
abstract class EntityRepository
{
    public function __construct(protected QuickbooksConnection $connection) {}

    /**
     * The QuickBooks entity name, e.g. "Invoice", "Payment", "CreditMemo".
     */
    abstract protected function entity(): string;

    /**
     * The SDK facade class used to build/update the entity object,
     * e.g. \QuickBooksOnline\API\Facades\Invoice::class.
     *
     * @return class-string
     */
    abstract protected function facade(): string;

    /**
     * Fetch a single entity by its QuickBooks id.
     */
    public function find(string|int $id): object
    {
        $result = $this->connection->dataService()->FindById($this->entity(), $id);

        $this->throwOnError();

        return $result;
    }

    /**
     * Run a QBO SQL-style query, e.g. "WHERE Balance > '0'".
     *
     * @return array<int, object>
     */
    public function query(string $where = '', int $startPosition = 1, int $maxResults = 100): array
    {
        $sql = "SELECT * FROM {$this->entity()} {$where} STARTPOSITION {$startPosition} MAXRESULTS {$maxResults}";

        $result = $this->connection->dataService()->Query($sql);

        $this->throwOnError();

        return $result ?? [];
    }

    /**
     * Return every record, transparently paging through the API.
     *
     * @return array<int, object>
     */
    public function all(string $where = ''): array
    {
        $records = [];
        $start = 1;
        $page = 100;

        do {
            $batch = $this->query($where, $start, $page);
            $records = array_merge($records, $batch);
            $start += $page;
        } while (count($batch) === $page);

        return $records;
    }

    /**
     * Create a new entity from an array of attributes.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): object
    {
        $object = $this->facade()::create($attributes);

        $result = $this->connection->dataService()->Add($object);

        $this->throwOnError();

        return $result;
    }

    /**
     * Update an existing entity. Pass the entity fetched via find() plus
     * the fields to change.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function update(object $entity, array $attributes): object
    {
        $object = $this->facade()::update($entity, $attributes);

        $result = $this->connection->dataService()->Update($object);

        $this->throwOnError();

        return $result;
    }

    public function delete(object $entity): void
    {
        $this->connection->dataService()->Delete($entity);

        $this->throwOnError();
    }

    protected function throwOnError(): void
    {
        $error = $this->connection->dataService()->getLastError();

        if ($error !== null) {
            throw QuickbooksException::fromSdkError($error);
        }
    }
}
