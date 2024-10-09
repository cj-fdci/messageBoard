<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Error 500 - Internal Server Error</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f4f4f4;
        }
        h1 {
            color: #d9534f;
        }
        pre {
            background-color: #f9f9f9;
            border: 1px solid #e1e1e1;
            padding: 15px;
            overflow: auto;
        }
        .container {
            margin: 0 auto;
            max-width: 800px;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .trace {
            color: #333;
            font-size: 12px;
            margin-top: 20px;
        }
        .error-message {
            color: #c9302c;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Internal Server Error</h1>

        <p>Oops! Something went wrong. Below is the error detail for debugging purposes.</p>

        <?php if (Configure::read('debug')): ?>
            <div class="error-message">
                <h2>Error Message:</h2>
                <p><?php echo h($message); ?></p>
            </div>

            <h2>Exception Details</h2>
            <pre><?php echo h($error->getMessage()); ?></pre>

            <h2>Stack Trace:</h2>
            <pre class="trace"><?php echo h($error->getTraceAsString()); ?></pre>

            <h2>File:</h2>
            <pre><?php echo h($error->getFile()); ?></pre>

            <h2>Line:</h2>
            <pre><?php echo h($error->getLine()); ?></pre>
        <?php else: ?>
            <p>An internal error occurred, and we are working to fix it. Please try again later.</p>
        <?php endif; ?>

        <hr />
        <p><small>CakePHP Framework</small></p>
    </div>
</body>
</html>
