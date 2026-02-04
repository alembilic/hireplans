# Quil Webhooks Logging System

Comprehensive logging and monitoring system for Quil AI meeting webhooks.

## Quick Start

### View Logs

```bash
# View last 50 lines (default)
php artisan quil:logs

# View last 100 lines
php artisan quil:logs --lines=100

# Monitor in real-time
php artisan quil:logs --tail

# Filter by keyword
php artisan quil:logs --filter="error"

# Show only errors
php artisan quil:logs --level=error

# Today's logs only
php artisan quil:logs --today
```

## Log Files Location

```
storage/logs/quil-webhooks.log              # Current log file
storage/logs/quil-webhooks-2026-02-04.log   # Daily rotation
```

## What Gets Logged

### Request Level (Middleware)
✅ Request ID for correlation  
✅ Timestamp (ISO 8601)  
✅ HTTP method, URL, IP, User Agent  
✅ Request headers (sanitized)  
✅ Payload size  
✅ Response status & body  
✅ Processing time (ms)  
✅ Performance warnings (>2000ms)  

### Processing Level (Controller)
✅ Webhook receipt confirmation  
✅ Payload validation (success/failure)  
✅ Duplicate detection  
✅ Phone number matching attempts  
✅ Database operations  
✅ Activity log creation  
✅ Final status & metrics  
✅ Complete error details  

## Log Levels

- **INFO**: Normal webhook processing (receipt, success, status changes)
- **DEBUG**: Detailed step-by-step processing (validation, matching attempts)
- **WARNING**: Issues that don't prevent processing (no phone match, slow performance, duplicates)
- **ERROR**: Processing failures (validation errors, exceptions, database failures)

## Example Log Entries

### Successful Processing
```
[2026-02-04 14:30:15] INFO: === INCOMING QUIL WEBHOOK ===
{"request_id":"quil_65f8a3b2c1d4e","event_id":"evt_123abc"}

[2026-02-04 14:30:15] INFO: Phone match found!
{"user_id":42,"user_name":"John Doe","candidate_id":15}

[2026-02-04 14:30:16] INFO: ✓ Webhook processing completed successfully
{"quil_meeting_id":89,"processing_time_ms":857.32}
```

### Failed Processing
```
[2026-02-04 14:30:15] ERROR: ✗ Webhook validation failed
{"event_id":"evt_123abc","errors":{"data.meeting.name":["required field"]}}

[2026-02-04 14:30:15] ERROR: ✗ Webhook processing failed
{"error_message":"Database connection timeout","processing_time_ms":5234.12}
```

### No Phone Match
```
[2026-02-04 14:30:15] WARNING: No phone number match found
{"participants":["+1-555-0123","+1-555-0456"],"attempts_made":2}
```

## Troubleshooting Common Issues

### Webhook Not Arriving
```bash
# Check if any webhooks are being received
php artisan quil:logs --filter="INCOMING" --tail
```

### Validation Errors
```bash
# Show validation failures
php artisan quil:logs --level=error --filter="validation"
```

### Phone Matching Issues
```bash
# See all phone matching attempts
php artisan quil:logs --filter="phone match" --today
```

### Performance Problems
```bash
# Find slow webhooks
php artisan quil:logs --filter="Slow webhook"
```

### Duplicate Webhooks
```bash
# Check idempotency
php artisan quil:logs --filter="Duplicate"
```

## Log Rotation

- **Frequency**: Daily at midnight
- **Retention**: 30 days
- **Automatic**: No manual cleanup needed
- **Old Logs**: Automatically deleted after 30 days

## Performance Metrics

Each webhook logs:
- **Processing Time**: Total time in milliseconds
- **Phone Matching Attempts**: Number of numbers tried
- **Assets Available**: Which meeting assets were included
- **Match Status**: matched/unmatched/received

## Security Features

### Automatically Redacted
- `Authorization` headers
- `X-API-Key` headers  
- `Cookie` data
- Any sensitive authentication tokens

### Large Payload Handling
Payloads >50KB are automatically truncated to prevent log bloat while preserving key information.

## Integration with Monitoring Tools

### Laravel Telescope
Logs appear in Telescope's log viewer with full context.

### Log Aggregation Services
Structured JSON format works with:
- Loggly
- Papertrail
- Splunk
- ELK Stack
- AWS CloudWatch

## Environment Configuration

Add to `.env` for custom configuration:

```env
# Log level (debug, info, warning, error)
LOG_LEVEL=debug

# Log retention days
LOG_DAILY_DAYS=30
```

## Development Tips

### Local Development
```bash
# Keep logs visible while testing
php artisan quil:logs --tail
```

### Testing Webhooks
```bash
# Watch logs in real-time
php artisan quil:logs --tail --filter="Processing"
```

### Debugging
```bash
# Show detailed processing steps
php artisan quil:logs --level=debug --lines=200
```

## Log Analysis

### Count Successful Webhooks
```bash
grep "✓ Webhook processing completed" storage/logs/quil-webhooks.log | wc -l
```

### Count Failed Webhooks
```bash
grep "✗ Webhook processing failed" storage/logs/quil-webhooks.log | wc -l
```

### Average Processing Time
```bash
grep "processing_time_ms" storage/logs/quil-webhooks.log | 
  jq '.processing_time_ms' | 
  awk '{sum+=$1; count++} END {print sum/count}'
```

### Most Common Errors
```bash
grep "ERROR" storage/logs/quil-webhooks.log | 
  grep -o '"error_message":"[^"]*"' | 
  sort | uniq -c | sort -rn
```

## Support & Documentation

- **Full Documentation**: See `features.txt` > "Quil AI Meeting Notes Integration"
- **Controller**: `app/Http/Controllers/Api/QuilWebhookController.php`
- **Middleware**: `app/Http/Middleware/LogQuilWebhookRequests.php`
- **Configuration**: `config/logging.php`
- **Routes**: `routes/web.php` (webhook endpoint)

## Command Summary

| Command | Description |
|---------|-------------|
| `php artisan quil:logs` | View last 50 lines |
| `php artisan quil:logs --lines=N` | View last N lines |
| `php artisan quil:logs --tail` | Monitor in real-time |
| `php artisan quil:logs --filter=KEYWORD` | Filter by keyword |
| `php artisan quil:logs --level=LEVEL` | Filter by log level |
| `php artisan quil:logs --today` | Today's logs only |

## Performance Expectations

- **Normal Processing**: 200-1000ms
- **Slow Warning**: >2000ms
- **Typical Payload**: 5-50KB
- **Large Payload**: >50KB (auto-truncated in logs)

## Need Help?

Check logs first:
```bash
php artisan quil:logs --level=error --lines=100
```

Still stuck? Check:
1. Is the webhook URL correct in Quil dashboard?
2. Are there validation errors in logs?
3. Is the database accessible?
4. Are phone numbers in correct format?

---

**Last Updated**: February 2026  
**Version**: 1.0
