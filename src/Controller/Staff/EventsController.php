<?php
declare(strict_types=1);

namespace App\Controller\Staff;

use App\Controller\AppController;
use Cake\I18n\Time;
use Cake\ORM\Query;
use Cake\Core\Configure;

/**
 * Events Controller
 *
 * @property \App\Model\Table\EventsTable $events
 * @method \App\Model\Entity\event[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class EventsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Events->find('all')
        ->contain([
            'Staffs',
            'EventSchedules'
        ])
        ->where([
            'Events.staff_id' => $this->Authentication->getIdentity()->id,
            'Events.is_deleted' => 0
        ])
        ->order([
            'Events.start_datetime' => 'desc'
        ]);
        $events = $this->paginate($query);

        // イベントタイプ
        $eventType = Configure::read("EVENT_TYPE");

        $this->set(compact('events', 'eventType'));
    }

    /**
     * View method
     *
     * @param string|null $id event id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $event = $this->Events->find('all')
        ->contain([
            'Staffs',
            'EventSchedules.EventEntries.Users',
            'EventSchedules' =>['sort' => ['EventSchedules.event_date' => 'asc', 'EventSchedules.start_time' => 'asc', 'EventSchedules.end_time' => 'asc']]
        ])
        ->where([
            'Events.staff_id' => $this->Authentication->getIdentity()->id,
            'Events.id' => $id,
            'Events.is_deleted' => 0
        ])
        ->first();

        if (!$event) {
            return $this->redirect(['action' => 'index']);
        }

        // イベントタイプ
        $eventType = Configure::read("EVENT_TYPE");

        $this->set(compact('event', 'eventType'));
    }
}
