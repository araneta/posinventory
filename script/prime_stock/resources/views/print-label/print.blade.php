<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barcode Sheet</title>
    <style>
        .barcode-sheet {
            width: {{ $sheetWidth }};
            height: {{ $sheetHeight }};
            display: block;
            border: 1px solid #CCC;
            margin: 10px auto;
            padding: 0.1in 0 0 0.1in;
            page-break-after: always;
        }

        .barcode-sheet .style40 {
            width: {{ $labelWidth }};
            height: {{ $labelHeight }};
            margin: 0 0.07in;
            padding-top: 0.05in;
        }

        .barcode-sheet .barcode-item {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            text-align: center;
            border: 1px dotted #CCC;
            font-size: 12px;
            line-height: 14px;
            text-transform: uppercase;
            float: left;
        }
        p{
            margin: 0;
        }
    </style>
</head>
<body>
@for($i = 1; $i<=$totalPage; $i++)
    @php
        $q = $mainQuantity - ($i-1)*$quantityPerPage;
        if($q > $quantityPerPage){
            $quantity = $quantityPerPage;
        }else{
            $quantity = $q;
        }
    @endphp
    <div class="barcode-sheet">
        @for($j=0; $j<$quantity; $j++)
            <div class="barcode-item style40">
                <p>{{ $productName }}</p>
                <p>{{ $productPrice }}</p>
                {!! \Milon\Barcode\Facades\DNS1DFacade::getBarcodeHTML($productCode, 'C39', 1, 30) !!}
                <p>{{ $productCode }}</p>
            </div>
        @endfor
    </div>
@endfor

</body>
</html>
