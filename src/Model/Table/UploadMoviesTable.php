<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UploadMovies Model
 *
 * @method \App\Model\Entity\UploadMovie newEmptyEntity()
 * @method \App\Model\Entity\UploadMovie newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\UploadMovie[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UploadMovie get($primaryKey, $options = [])
 * @method \App\Model\Entity\UploadMovie findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\UploadMovie patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UploadMovie[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\UploadMovie|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UploadMovie saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UploadMovie[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\UploadMovie[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\UploadMovie[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\UploadMovie[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UploadMoviesTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('upload_movies');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('name')
            ->maxLength('name', 100)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('image_url')
            ->maxLength('image_url', 100)
            ->allowEmptyFile('image_url');

        $validator
            ->scalar('movie_url')
            ->maxLength('movie_url', 100)
            ->requirePresence('movie_url', 'create')
            ->notEmptyString('movie_url');

        $validator
            ->scalar('note')
            ->allowEmptyString('note');

        $validator
            ->notEmptyString('is_deleted');

        return $validator;
    }
}
