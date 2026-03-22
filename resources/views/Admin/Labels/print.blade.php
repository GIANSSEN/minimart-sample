<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Labels</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 10px;
        }
        
        .label-container {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }
        
        .label {
            border: 1px solid #ccc;
            padding: 5px;
            text-align: center;
            page-break-inside: avoid;
        }
        
        .label.small {
            width: 2in;
            height: 1in;
        }
        
        .label.medium {
            width: 3in;
            height: 2in;
        }
        
        .label.large {
            width: 4in;
            height: 3in;
        }
        
        .product-name {
            font-weight: bold;
            font-size: 12px;
            margin: 2px 0;
        }
        
        .price {
            font-size: 14px;
            color: #000;
            font-weight: bold;
        }
        
        .product-code {
            font-size: 8px;
            color: #666;
        }
        
        @media print {
            .label {
                border: none;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="label-container">
        @foreach ($products as $product)
            <div class="label {{ $request->label_size }}">
                <div class="product-name">{{ $product->product_name }}</div>
                
                @if ($request->show_price)
                    <div class="price">₱{{ number_format($product->selling_price, 2) }}</div>
                @endif
                
                <div class="product-code">{{ $product->product_code }}</div>
            </div>
        @endforeach
    </div>
</body>
</html>
