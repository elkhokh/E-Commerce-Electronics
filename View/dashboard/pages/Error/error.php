<?php



$log_file = "Config/log.log";
$log_content = file_get_contents($log_file);


$log_lines = explode("\n", $log_content);
?>

<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">
                <i class="fas fa-exclamation-triangle"></i> Error Log
            </h4>
        </div>
        <div class="card-body">
            <?php if (empty($log_lines[0])): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> No errors found in the log file.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th style="width: 20%">Date & Time</th>
                                <th style="width: 70%">Error Message</th>
                                <th style="width: 10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($log_lines as $line): 
                                if (empty(trim($line))) continue;
                                

                                $parts = explode(']', $line, 2);
                                if (count($parts) == 2):
                                    $date = trim(str_replace('[', '', $parts[0]));
                                    $message = trim($parts[1]);
                            ?>
                                <tr>
                                    <td>
                                        <span class="badge badge-secondary">
                                            <i class="fas fa-clock"></i> <?php echo htmlspecialchars($date); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="error-message">
                                            <?php echo htmlspecialchars($message); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary" onclick="copyToClipboard('<?php echo addslashes($message); ?>')">
                                            <i class="fas fa-copy"></i> Copy
                                        </button>
                                    </td>
                                </tr>
                            <?php endif; endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.card {
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
    border: none;
}

.error-message {
    font-family: monospace;
    white-space: pre-wrap;
    word-break: break-word;
}

.badge {
    font-size: 0.9em;
    padding: 8px 12px;
}

.table th {
    font-weight: 600;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.alert {
    margin-bottom: 0;
}
</style>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('Error message copied to clipboard!');
    }).catch(function(err) {
        console.error('Could not copy text: ', err);
    });
}
</script>
