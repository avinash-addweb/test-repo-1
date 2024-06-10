<?php

namespace App\Services;

class BaseService
{
    public $object = null;
    public $query = null;

    /**
     * Retrieves the Eloquent class object based on the current instance object.
     *
     * @return void
     */
    private function getEloquentObj(): void
    {
        $name        = get_class($this->object);
        $this->query = $name::query();
    }

    /**
     * Create a new record or insert multiple records into the database.
     *
     * @param  array  $data  An array of data to be created or inserted.
     *
     * @return mixed The result of the create operation if a single record is created,
     *               or the result of the insert operation if multiple records are inserted.
     */
    public function create(array $data = []): mixed
    {
        if( ! isset($data[0])) {
            return $this->object->create($data);
        }

        return $this->object->insert($data);
    }

    /**
     * Find a record in the database by its ID.
     *
     * @param  int  $id  The ID of the record to find.
     *
     * @return mixed The found record or null if not found.
     */
    public function find($id): mixed
    {
        $this->getEloquentObj();

        return $this->query->where('id', $id)->first();
    }


}