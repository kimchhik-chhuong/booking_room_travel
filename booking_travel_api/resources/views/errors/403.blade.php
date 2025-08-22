<!DOCTYPE html>
<html>
<head>
    <title>403 Access Denied</title>
    <style>
        body { 
            font-family: 'Nunito', sans-serif;
            padding: 50px;
            text-align: center;
            background-color: #f8f9fa;
        }
        .error-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        h1 {
            color: #e74c3c;
            margin-bottom: 20px;
        }
        .error-details {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            text-align: left;
            font-family: monospace;
            white-space: pre-wrap;
            max-height: 300px;
            overflow-y: auto;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 5px;
        }
        .btn:hover {
            background: #2980b9;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>403 - Access Denied</h1>
        <p>You don't have permission to access this page.</p>
        
        @if(isset($message))
            <div class="error-details">
                <strong>Error Details:</strong><br>
                {{ $message }}
                
                @if(isset($exception) && config('app.debug'))
                    <hr>
                    <strong>Debug Information:</strong><br>
                    {{ $exception->getMessage() }}<br>
                    <br>
                    <strong>In File:</strong> {{ $exception->getFile() }}:{{ $exception->getLine() }}
                @endif
            </div>
        @endif
        
        <div>
            <a href="{{ url()->previous() }}" class="btn">Go Back</a>
            <a href="{{ url('/') }}" class="btn">Go to Homepage</a>
            
            @auth
                <a href="{{ route('profile.edit') }}" class="btn">Your Profile</a>
            @else
                <a href="{{ route('login') }}" class="btn">Login</a>
            @endauth
        </div>
    </div>
</body>
</html>
