<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CourseMovies Model
 *
 * @property \App\Model\Table\CoursesTable&\Cake\ORM\Association\BelongsTo $Courses
 *
 * @method \App\Model\Entity\CourseMovie newEmptyEntity()
 * @method \App\Model\Entity\CourseMovie newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\CourseMovie[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CourseMovie get($primaryKey, $options = [])
 * @method \App\Model\Entity\CourseMovie findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\CourseMovie patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CourseMovie[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\CourseMovie|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CourseMovie saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CourseMovie[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\CourseMovie[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\CourseMovie[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\CourseMovie[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CourseMoviesTable extends Table
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

        $this->setTable('course_movies');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Courses', [
            'foreignKey' => 'course_id',
        ]);
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
            ->integer('sort')
            ->requirePresence('sort', 'create')
            ->notEmptyString('sort');

        $validator
            ->integer('course_id')
            ->allowEmptyString('course_id');

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
            ->scalar('info_movie_url')
            ->maxLength('info_movie_url', 100)
            ->allowEmptyString('info_movie_url');

        $validator
            ->scalar('note')
            ->allowEmptyString('note');

        $validator
            ->notEmptyString('is_deleted');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn('course_id', 'Courses'), ['errorField' => 'course_id']);

        return $rules;
    }
}
