<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Your Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: #f5f5f5;
            min-height: 100vh;
            padding: 20px 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .lookup-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 500px;
            width: 100%;
            margin: 0 auto;
            border: 1px solid #e0e0e0;
        }
        
        .lookup-header {
            background: white;
            padding: 30px 30px 20px;
            text-align: center;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .company-logo {
            max-width: 180px;
            height: auto;
            margin: 0 auto 20px;
            display: block;
        }
        
        .lookup-header h1 {
            font-size: 24px;
            font-weight: 600;
            margin: 0 0 8px 0;
            color: #333;
        }
        
        .lookup-header p {
            margin: 0;
            font-size: 14px;
            color: #666;
        }
        
        .lookup-body {
            padding: 40px 30px;
        }
        
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
            font-size: 14px;
        }
        
        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 16px;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .btn-track {
            background: #4f46e5;
            border: none;
            border-radius: 4px;
            padding: 12px;
            font-size: 16px;
            font-weight: 600;
            color: white;
            width: 100%;
            margin-top: 20px;
            transition: all 0.2s;
        }
        
        .btn-track:hover {
            background: #4338ca;
            color: white;
        }
        
        .alert {
            border: none;
            border-radius: 10px;
            padding: 15px 20px;
            margin-bottom: 25px;
        }
        
        .alert-danger {
            background-color: #fee;
            color: #c33;
        }
        
        .help-text {
            margin-top: 8px;
            font-size: 13px;
            color: #666;
        }
        
        .example-box {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 15px;
            margin-top: 20px;
            text-align: center;
        }
        
        .example-box small {
            display: block;
            color: #666;
            margin-bottom: 8px;
            font-weight: 500;
            font-size: 13px;
        }
        
        .example-ref {
            font-family: 'Courier New', monospace;
            font-size: 16px;
            font-weight: 600;
            color: #4f46e5;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="lookup-card">
            <div class="lookup-header">
                <img src="https://removalplus365.com/wp-content/uploads/2021/12/Removal-365-1000-x-1000-px-2.png" 
                     alt="Company Logo" 
                     class="company-logo"
                     onerror="this.style.display='none'">
                <h1>Track Your Booking</h1>
                <p>Enter your booking reference number to view details</p>
            </div>
            
            <div class="lookup-body">
                @if(session('error'))
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        {{ session('error') }}
                    </div>
                @endif
                
                <form method="POST" action="{{ route('public.booking.search') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="booking_reference" class="form-label">
                            <i class="bi bi-tag me-1"></i> Booking Reference Number
                        </label>
                        <input 
                            type="text" 
                            name="booking_reference" 
                            id="booking_reference" 
                            class="form-control @error('booking_reference') is-invalid @enderror" 
                            placeholder="e.g., TBR-001000"
                            value="{{ old('booking_reference') }}"
                            required
                            autofocus
                            style="text-transform: uppercase;">
                        @error('booking_reference')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="help-text">
                            <i class="bi bi-info-circle me-1"></i>
                            You can find this reference number in your booking confirmation SMS or email
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-track">
                        <i class="bi bi-search me-2"></i> Track My Booking
                    </button>
                </form>
                
                <div class="example-box">
                    <small><i class="bi bi-lightbulb me-1"></i> Example Format</small>
                    <div class="example-ref">TBR-001000</div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Auto uppercase input
        document.getElementById('booking_reference').addEventListener('input', function(e) {
            e.target.value = e.target.value.toUpperCase();
        });
    </script>
</body>
</html>

