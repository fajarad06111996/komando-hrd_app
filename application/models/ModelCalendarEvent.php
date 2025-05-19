<?php 
defined('BASEPATH') or exit('No direct script access allowed');

class ModelCalendarEvent extends CI_Model
{
    public function getDataCalendar($year_calendar)
    {
        $result = $this->db->query("SELECT event_date AS event_date,
                                            MONTH(event_date) AS event_month,
                                            event_name AS event_name
                                    FROM calendar_event_master 
                                    WHERE YEAR(event_date) = '" . $year_calendar . "'
                                    ORDER BY event_date ASC")->result_array();
        return $result;
    }
}
?>