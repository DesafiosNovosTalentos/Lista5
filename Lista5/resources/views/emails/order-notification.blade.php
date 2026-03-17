<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: #f4f7f6;
            color: #333333;
            margin: 0;
            padding: 20px;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .email-header {
            background-color: #003566;
            color: #ffffff;
            padding: 20px;
            text-align: center;
            border-bottom: 3px solid #001d3d;
        }
        .email-header h2 {
            margin: 0;
            font-size: 24px;
            font-weight: normal;
        }
        .email-body {
            padding: 30px;
            line-height: 1.6;
            font-size: 16px;
        }
        .order-box {
            background-color: #f8f9fa;
            border-left: 4px solid #ffc300;
            padding: 15px 20px;
            margin: 25px 0;
            border-radius: 0 4px 4px 0;
        }
        .order-box p {
            margin: 8px 0;
        }
        .email-footer {
            background-color: #003566;
            color: #ffffff;
            font-size: 13px;
            text-align: center;
            padding: 20px;
            border-top: 1px solid #e0e0e0;
        }
        .email-footer p {
            margin: 0;
        }
    </style>
</head>
<body>

    <div class="email-container">
        <div class="email-header">
            <h2>Notificação de Pedido</h2>
        </div>
        
        <div class="email-body">
            <p>{{ $notificationMessage }}</p>
            
            <div class="order-box">
                <p><strong>Produto:</strong> {{ $order->getProductName() }}</p>
                <p><strong>Quantidade:</strong> {{ $order->getAmount() }}</p>
                <p><strong>Status:</strong> {{ strtolower($order->getStatus()->name) }}</p>
            </div>
        </div>
        
        <div class="email-footer">
            <p>Enviado por Gustavo Motta - Programa de Novos Talentos Imply</p>
        </div>
    </div>

</body>
</html>