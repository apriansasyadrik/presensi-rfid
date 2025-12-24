<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * WA Queue Processor
 * Background processor for WhatsApp notification queue
 * Can be run via cron job or CLI
 */
class Wa_queue_processor extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Wa_queue_model');
        $this->load->library('curl');
        
        // Only allow CLI or specific access
        if (!is_cli() && !$this->input->get('key') === 'process_queue_2024') {
            show_error('Access denied. This processor should be run via CLI or with valid key.', 403);
        }
    }

    /**
     * Process pending notifications in queue
     * Run this via cron: php index.php wa_queue_processor process
     */
    public function process() {
        $batch_size = 10; // Process 10 at a time to avoid overload
        $pending = $this->Wa_queue_model->get_pending_queue($batch_size);
        
        $processed = 0;
        $failed = 0;
        
        echo "Starting WA Queue Processor...\n";
        echo "Found " . count($pending) . " pending notifications\n\n";
        
        foreach ($pending as $item) {
            echo "Processing ID: " . $item->id . " - " . $item->phone . "\n";
            
            // Mark as processing
            $this->Wa_queue_model->update_status($item->id, 'processing');
            
            // Send via API
            $result = $this->send_wa_notification($item);
            
            if ($result['success']) {
                $this->Wa_queue_model->update_status($item->id, 'sent', $result['response']);
                echo "✓ Sent successfully\n";
                $processed++;
            } else {
                // Increment retry count
                $retry_count = $item->retry_count + 1;
                
                if ($retry_count >= 3) {
                    // Mark as failed after 3 retries
                    $this->Wa_queue_model->update_status($item->id, 'failed', $result['error']);
                    echo "✗ Failed after 3 retries: " . $result['error'] . "\n";
                    $failed++;
                } else {
                    // Schedule retry
                    $this->Wa_queue_model->increment_retry($item->id);
                    echo "⟳ Retry scheduled (attempt " . ($retry_count + 1) . ")\n";
                }
            }
            
            echo "\n";
            
            // Small delay to avoid rate limiting
            usleep(500000); // 0.5 second delay
        }
        
        echo "\nProcessing Complete!\n";
        echo "Processed: $processed\n";
        echo "Failed: $failed\n";
        echo "Remaining: " . ($this->Wa_queue_model->count_pending()) . "\n";
    }

    /**
     * Send WA notification via API
     */
    private function send_wa_notification($queue_item) {
        // Get WA config
        $config = $this->db->get_where('wa_config', ['id' => 1])->row();
        
        if (!$config || !$config->is_active) {
            return [
                'success' => false,
                'error' => 'WA configuration not active'
            ];
        }
        
        // Prepare API request
        $data = [
            'api_key' => $config->api_key,
            'sender' => $config->sender,
            'number' => $queue_item->phone,
            'message' => $queue_item->message
        ];
        
        // Send request
        try {
            $ch = curl_init($config->api_url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json'
            ]);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            
            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            
            curl_close($ch);
            
            if ($http_code == 200) {
                return [
                    'success' => true,
                    'response' => $response
                ];
            } else {
                return [
                    'success' => false,
                    'error' => 'HTTP ' . $http_code . ': ' . $response
                ];
            }
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Clean old processed queue entries (older than 30 days)
     */
    public function cleanup() {
        echo "Cleaning up old queue entries...\n";
        
        $deleted = $this->Wa_queue_model->cleanup_old_queue(30);
        
        echo "Deleted $deleted old entries\n";
    }

    /**
     * Get queue statistics
     */
    public function stats() {
        $stats = $this->Wa_queue_model->get_queue_stats();
        
        echo "\n=== WA Queue Statistics ===\n";
        echo "Pending: " . $stats['pending'] . "\n";
        echo "Processing: " . $stats['processing'] . "\n";
        echo "Sent: " . $stats['sent'] . "\n";
        echo "Failed: " . $stats['failed'] . "\n";
        echo "Total: " . $stats['total'] . "\n";
        echo "===========================\n\n";
    }
}
