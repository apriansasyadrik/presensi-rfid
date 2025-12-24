<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wa_queue_model extends CI_Model {

    /**
     * Get pending queue items
     */
    public function get_pending_queue($limit = 10) {
        return $this->db
            ->where('status', 'pending')
            ->or_where('status', 'retry')
            ->where('retry_count <', 3)
            ->order_by('created_at', 'ASC')
            ->limit($limit)
            ->get('wa_queue')
            ->result();
    }

    /**
     * Update queue status
     */
    public function update_status($id, $status, $response = null) {
        $data = [
            'status' => $status,
            'processed_at' => date('Y-m-d H:i:s')
        ];
        
        if ($response) {
            $data['response'] = $response;
        }
        
        return $this->db->where('id', $id)->update('wa_queue', $data);
    }

    /**
     * Increment retry count
     */
    public function increment_retry($id) {
        $this->db->set('retry_count', 'retry_count + 1', FALSE);
        $this->db->set('status', 'retry');
        $this->db->where('id', $id);
        return $this->db->update('wa_queue');
    }

    /**
     * Count pending notifications
     */
    public function count_pending() {
        return $this->db
            ->where_in('status', ['pending', 'retry'])
            ->where('retry_count <', 3)
            ->count_all_results('wa_queue');
    }

    /**
     * Get queue statistics
     */
    public function get_queue_stats() {
        $stats = [
            'pending' => $this->db->where('status', 'pending')->count_all_results('wa_queue'),
            'processing' => $this->db->where('status', 'processing')->count_all_results('wa_queue'),
            'sent' => $this->db->where('status', 'sent')->count_all_results('wa_queue'),
            'failed' => $this->db->where('status', 'failed')->count_all_results('wa_queue'),
            'total' => $this->db->count_all('wa_queue')
        ];
        
        return $stats;
    }

    /**
     * Cleanup old processed queue entries
     */
    public function cleanup_old_queue($days = 30) {
        $date = date('Y-m-d H:i:s', strtotime("-$days days"));
        
        $this->db->where('processed_at <', $date);
        $this->db->where_in('status', ['sent', 'failed']);
        $this->db->delete('wa_queue');
        
        return $this->db->affected_rows();
    }

    /**
     * Add notification to queue
     */
    public function add_to_queue($phone, $message, $type = 'masuk') {
        $data = [
            'phone' => $phone,
            'message' => $message,
            'type' => $type,
            'status' => 'pending',
            'retry_count' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->db->insert('wa_queue', $data);
    }
}
