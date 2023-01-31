<?php
namespace Common\Base;

use Guillermoandrae\Repositories\AbstractRepository;
use Guillermoandrae\Common\Collection;

class DbRepository extends AbstractRepository
{
	protected function __construct(
		protected string|null $tableName = null
	) {
	}

	public static function getRepo(string $tableName): DbRepository
	{
		return new static($tableName);
	}

	/**
	 * Returns the model with the provided primary key.
	 *
	 * @param mixed $primaryKey The primary key of the desired model.
	 * @return \Guillermoandrae\Models\ModelInterface|null
	 */
	public function find(mixed $primaryKey): \Guillermoandrae\Models\ModelInterface|null
	{
		$obj = \ORM::forTable($this->tableName)->where('id', $primaryKey)->findOne();
		return new DbModel($obj);
	}
	
	/**
	 * Returns a collection of models within the provided range.
	 *
	 * @param int $offset The desired offset.
	 * @param int|null $limit The desired limit.
	 * @return \Guillermoandrae\Common\CollectionInterface|null
	 */
	public function findAll(int|null $offset = null, int|null $limit = null): \Guillermoandrae\Common\CollectionInterface|null
	{
		$query = \ORM::forTable($this->tableName);
		if ($offset && $limit) {

			$query = $query->offset($offset)->limit($limit);
		}

		$list = $query->findMany();
		$list = $list ?? [];

		return new Collection(array_map(fn($item) => new DbModel($item), $list));
	}
	
	/**
	 * Returns a collection of models that meet the provided criteria within
	 * the provided range.
	 *
	 * @param array $where The selection criteria.
	 * @param int $offset The desired offset.
	 * @param int|null $limit The desired limit.
	 * @return \Guillermoandrae\Common\CollectionInterface|null
	 */
	public function findWhere(array $where, int|null $offset = null, int|null $limit = null): \Guillermoandrae\Common\CollectionInterface|null
	{
		$query = \ORM::forTable($this->tableName)->where($where)->offset($offset);
		if ($offset && $limit) {

			$query = $query->offset($offset)->limit($limit);
		}

		$list = $query->findMany();
		$list = $list ?? [];

		return new Collection(array_map(fn($item) => new DbModel($item), $list));
	}
	
	/**
	 * Creates a model using the provided data and returns that model.
	 *
	 * @param array $data The data to use when creating the model.
	 * @return \Guillermoandrae\Models\ModelInterface|null
	 */
	public function create(array $data): \Guillermoandrae\Models\ModelInterface|null
	{
		$obj = \ORM::for_table($this->tableName)->create();
		$obj->set($data);
		$obj->save();
		
		return new DbModel($obj);
	}
	
	/**
	 * Updates the model associated with the provided primary key using the
	 * provided data. Returns the updated model.
	 *
	 * @param mixed $primaryKey The primary key of the desired model.
	 * @param array $data The data to use when updating the model.
	 * @return \Guillermoandrae\Models\ModelInterface|null
	 */
	public function update(mixed $primaryKey, array $data): \Guillermoandrae\Models\ModelInterface|null
	{
		$obj = \ORM::forTable($this->tableName)->where('id', $primaryKey)->findOne();
		$obj->set($data);
		$obj->save();
		
		return new DbModel($obj);
	}
	
	/**
	 * Deletes the model associated with the provided primary key.
	 *
	 * @param mixed $primaryKey The ID of the desired model.
	 * @return bool
	 */
	public function delete(mixed $primaryKey): bool
	{
		$obj = \ORM::forTable($this->tableName)->where('id', $primaryKey)->findOne();
		$obj->delete();

        return true;
	}
}
