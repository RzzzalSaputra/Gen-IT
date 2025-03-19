
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Preview{{ isset($filename) ? ' - ' . $filename : '' }}</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
            font-family: Arial, sans-serif;
            background-color: #1a1e23;
        }
        iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
        .fallback-message {
            padding: 20px;
            text-align: center;
            font-family: Arial, sans-serif;
        }
        .view-options {
            padding: 15px;
            background: #2d3748;
            text-align: center;
            border-bottom: 1px solid #4a5568;
        }
        .view-options a {
            margin: 0 10px;
            text-decoration: none;
            color: #63b3ed;
            font-weight: bold;
        }
        .cant-preview {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            background-color: #1f2937;
        }
        .cant-preview h1 {
            color: #f3f4f6;
            margin-bottom: 20px;
            font-size: 28px;
        }
        .cant-preview p {
            color: #9ca3af;
            margin-bottom: 30px;
            font-size: 16px;
        }
        .download-btn {
            background-color: #2563eb;
            color: white;
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .download-btn:hover {
            background-color: #1d4ed8;
        }
        .download-icon {
            width: 20px;
            height: 20px;
        }
    </style>
</head>
<body>
    @if(isset($fallbackMode) && $fallbackMode)
        <div class="cant-preview">
            <h1>CAN'T PREVIEW FILE</h1>
            <p>This document format cannot be previewed in the browser.</p>
            <a href="{{ $docUrl }}" download="{{ $filename }}" class="download-btn">
                <svg class="download-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                DOWNLOAD INSTEAD
            </a>
        </div>
    @else
        <iframe src="{{ route('preview', ['id' => $id]) }}" frameborder="0"></iframe>
    @endif
</body>
</html>