<!DOCTYPE html>
<html>
<body>
    <h2>Notificação de Pedido</h2>
    <p>{{ $message }}</p>
    <p><strong>Produto:</strong> {{ $order->getProductName() }}</p>
    <p><strong>Status:</strong> {{ $order->getStatus()->value }}</p>
</body>
</html>