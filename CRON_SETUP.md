# Cron Job Configuration for RFID Attendance System

This document explains how to setup automated tasks for the attendance system.

## WA Queue Processor

The WA Queue Processor sends pending WhatsApp notifications in the background.

### Setup Cron Job

Add to your crontab (`crontab -e`):

```bash
# Process WA queue every 5 minutes
*/5 * * * * cd /path/to/presensi-rfid && php index.php wa_queue_processor process >> /var/log/wa_queue.log 2>&1

# Clean old queue entries daily at 2 AM
0 2 * * * cd /path/to/presensi-rfid && php index.php wa_queue_processor cleanup >> /var/log/wa_queue_cleanup.log 2>&1
```

### Manual Execution

You can also run the processor manually:

```bash
# Process queue
php index.php wa_queue_processor process

# View queue statistics
php index.php wa_queue_processor stats

# Clean old entries
php index.php wa_queue_processor cleanup
```

### Web-based Execution (with key)

Access via browser:
```
http://yourdomain.com/wa_queue_processor/process?key=process_queue_2024
```

**Security Note:** Change the access key in `application/controllers/Wa_queue_processor.php`

## Auto Notification for Absent Students

Send notification to homeroom teachers for absent students at 09:00 AM:

```bash
# Check absences at 9 AM and notify wali kelas
0 9 * * 1-6 cd /path/to/presensi-rfid && php index.php admin/dashboard/notify_absent_students >> /var/log/absent_notify.log 2>&1
```

## Database Backup

Automated daily backup:

```bash
# Backup database daily at 1 AM
0 1 * * * mysqldump -u username -p'password' presensi_rfid > /backup/presensi_$(date +\%Y\%m\%d).sql 2>&1
```

## Session Cleanup

Clean expired sessions:

```bash
# Clean sessions daily at 3 AM
0 3 * * * find /path/to/presensi-rfid/application/sessions -type f -mtime +1 -delete 2>&1
```

## Attendance Report Generation

Generate monthly reports automatically:

```bash
# Generate monthly reports on 1st of each month at 8 AM
0 8 1 * * cd /path/to/presensi-rfid && php index.php admin/laporan_siswa/generate_monthly >> /var/log/monthly_report.log 2>&1
```

## Log Rotation

Setup log rotation for cron logs:

Create `/etc/logrotate.d/presensi-rfid`:

```
/var/log/wa_queue*.log {
    daily
    missingok
    rotate 30
    compress
    notifempty
    create 0640 www-data www-data
}

/var/log/absent_notify.log {
    weekly
    missingok
    rotate 12
    compress
    notifempty
    create 0640 www-data www-data
}
```

## Monitoring

### Check Cron Status

```bash
# View cron logs
tail -f /var/log/wa_queue.log

# Check if cron is running
ps aux | grep cron

# View crontab
crontab -l
```

### Queue Statistics

Check queue health regularly:

```bash
php index.php wa_queue_processor stats
```

Expected output:
```
=== WA Queue Statistics ===
Pending: 5
Processing: 0
Sent: 150
Failed: 2
Total: 157
===========================
```

## Troubleshooting

### Queue Not Processing

1. Check cron is running: `sudo service cron status`
2. Check PHP CLI path: `which php`
3. Check permissions: `ls -l application/controllers/Wa_queue_processor.php`
4. Check logs: `tail -f /var/log/wa_queue.log`

### High Failure Rate

1. Verify WA API configuration in admin panel
2. Check API key validity
3. Test API connection manually
4. Review error messages in `wa_queue` table

### Performance Issues

1. Adjust batch size in `Wa_queue_processor.php` (default: 10)
2. Increase cron frequency if queue grows too large
3. Add delay between requests to avoid rate limiting

## Best Practices

1. **Monitor logs regularly** - Check for errors and performance issues
2. **Test before production** - Run processor manually first
3. **Set up alerts** - Email admin if failure rate > 10%
4. **Backup database** - Before major promotions or data imports
5. **Update API keys** - Rotate keys periodically for security
6. **Clean old data** - Run cleanup monthly to keep database lean

## Example Full Crontab

```bash
# WA Queue Processor - Every 5 minutes
*/5 * * * * cd /var/www/html/presensi-rfid && php index.php wa_queue_processor process >> /var/log/wa_queue.log 2>&1

# Queue Cleanup - Daily at 2 AM
0 2 * * * cd /var/www/html/presensi-rfid && php index.php wa_queue_processor cleanup >> /var/log/wa_queue_cleanup.log 2>&1

# Absent Student Notification - Weekdays at 9 AM
0 9 * * 1-6 cd /var/www/html/presensi-rfid && php index.php admin/dashboard/notify_absent_students >> /var/log/absent_notify.log 2>&1

# Database Backup - Daily at 1 AM
0 1 * * * mysqldump -u root -p'password' presensi_rfid > /backup/presensi_$(date +\%Y\%m\%d).sql 2>&1

# Session Cleanup - Daily at 3 AM
0 3 * * * find /var/www/html/presensi-rfid/application/sessions -type f -mtime +1 -delete 2>&1

# Log Queue Stats - Every hour
0 * * * * cd /var/www/html/presensi-rfid && php index.php wa_queue_processor stats >> /var/log/wa_queue_stats.log 2>&1
```
