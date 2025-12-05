@if(session('success') || session('error') || session('warning') || session('info') || session('alert'))
    <div class="alert-container">
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
                <button class="alert-close" onclick="this.parentElement.style.display='none'">&times;</button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
                <button class="alert-close" onclick="this.parentElement.style.display='none'">&times;</button>
            </div>
        @endif
        
        @if(session('warning'))
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                {{ session('warning') }}
                <button class="alert-close" onclick="this.parentElement.style.display='none'">&times;</button>
            </div>
        @endif
        
        @if(session('info'))
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                {{ session('info') }}
                <button class="alert-close" onclick="this.parentElement.style.display='none'">&times;</button>
            </div>
        @endif
        
        @if(session('alert'))
            <div class="alert alert-{{ session('alert')['type'] ?? 'info' }}">
                @if(isset(session('alert')['type']))
                    @switch(session('alert')['type'])
                        @case('success')
                            <i class="fas fa-check-circle"></i>
                            @break
                        @case('error')
                            <i class="fas fa-exclamation-circle"></i>
                            @break
                        @case('warning')
                            <i class="fas fa-exclamation-triangle"></i>
                            @break
                        @default
                            <i class="fas fa-info-circle"></i>
                    @endswitch
                @endif
                {{ session('alert')['message'] ?? '' }}
                <button class="alert-close" onclick="this.parentElement.style.display='none'">&times;</button>
            </div>
        @endif
    </div>
@endif

<style>
    .alert-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        max-width: 400px;
    }
    
    .alert {
        padding: 15px 20px;
        margin-bottom: 10px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        animation: slideIn 0.3s ease-out;
    }
    
    .alert i {
        margin-right: 10px;
        font-size: 1.2em;
    }
    
    .alert-success {
        background: #d4edda;
        color: #155724;
        border-left: 4px solid #28a745;
    }
    
    .alert-error {
        background: #f8d7da;
        color: #721c24;
        border-left: 4px solid #dc3545;
    }
    
    .alert-warning {
        background: #fff3cd;
        color: #856404;
        border-left: 4px solid #ffc107;
    }
    
    .alert-info {
        background: #d1ecf1;
        color: #0c5460;
        border-left: 4px solid #17a2b8;
    }
    
    .alert-close {
        background: none;
        border: none;
        font-size: 1.5em;
        cursor: pointer;
        color: inherit;
        margin-left: 15px;
        opacity: 0.7;
    }
    
    .alert-close:hover {
        opacity: 1;
    }
    
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
</style>

<script>
    // Auto-close alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                alert.style.opacity = '0';
                alert.style.transform = 'translateX(100%)';
                setTimeout(function() {
                    if (alert.parentElement) {
                        alert.parentElement.removeChild(alert);
                    }
                }, 300);
            });
        }, 5000);
    });
</script>