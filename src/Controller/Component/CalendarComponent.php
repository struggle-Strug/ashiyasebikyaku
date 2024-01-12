<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Chronos\Chronos;
use Cake\ORM\TableRegistry;

class CalendarComponent extends Component
{
    /**
     *
     * @param string $month
     * @return array $daysOfMonth
     *
     */
    public function getDaysOfMonth($month = null)
    {
        $date = Chronos::now()->startOfMonth();
        if (!empty($month)) {
            $date = Chronos::createFromFormat('Y-m-d', $month . '-01');
        }

        $start = $date->day;
        $end = $date->endOfMonth()->day;

        $daysOfMonth = [];
        for ($i = $start; $i <= $end; $i++) {
            $daysOfMonth[] = $date;
            $date = $date->addDay();
        }
        return $daysOfMonth;
    }

    /**
     *
     * @param string $date
     * @param string $startDate
     * @return array $daysOfWeek
     *
     */
    public function getDaysOfWeek(string $date = null, string $startDate = null)
    {
        if (!empty($startDate) && preg_match('/^20[0-9]{2}\-[0-9]{1,2}\-[0-9]{1,2}$/', $startDate) ) {
            // 開始日が指定されている週
            $time = Chronos::createFromFormat('Y-m-d', $startDate);
        } else if (!empty($date) && preg_match('/^20[0-9]{2}\-[0-9]{1,2}\-[0-9]{1,2}$/', $date) ) {
            // 指定日が含まれる週
            $date = Chronos::createFromFormat('Y-m-d', $date);
            if ($date->isSunday()) {
                $time = $date->startOfWeek()->addDays(6);
            } else {
                $time = $date->startOfWeek()->subDays(1);
            }
        } else {
            // 指定なし
            $time = Chronos::today();
        }

        $daysOfWeek = [];
        for($i = 0; $i < 7; $i++) {
            $daysOfWeek[] = $time;
            $time = $time->addDay();
        }
        return $daysOfWeek;
    }

    /**
     * 祝祭日取得
     *
     * @return array $holidaysArray
     *
     */
    public function getHolidays()
    {
        $holidaysTable = TableRegistry::getTableLocator()->get('Holidays');
        $holidays = $holidaysTable->find('all');
        // $holidays->where(['date >=' => Chronos::now()->startOfMonth()]);
        $holidays->where(['OR' => ['Holidays.is_deleted IS NULL', 'Holidays.is_deleted =' => '']]);
        $holidays->order(['date' => 'ASC']);

        $holidaysArray = [];
        foreach ($holidays as $holiday) {
            $holidaysArray[$holiday->date->format('Y-m-d')] = $holiday->name;
        }

        return $holidaysArray;
    }
}
