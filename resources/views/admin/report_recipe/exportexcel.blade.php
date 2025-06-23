<!DOCTYPE html>
<html>

<head>
</head>

<body>
    <h1></h1>
    <h1></h1>
    <h1></h1>
    <table class="table">
        <tr>
            <th>PLU</th>
            <th>Item Name</th>
            <th>Cost</th>
            <th>Tax / Discount</th>
            <th>Regular</th>
            <th>PC</th>
            <th>Profit</th>
            @foreach ($price_levels as $price)
                @if ($price->type == 1)
                    <th>{{ $price->level }}</th>
                    <th>PC</th>
                    <th>Profit</th>
                @endif
                @if ($price->type == 2)
                    <th>{{ $price->level }}</th>
                    <th>PC</th>
                    <th>Profit</th>
                @endif
                @if ($price->type == 3)
                    <th>{{ $price->level }}</th>
                    <th>PC</th>
                    <th>Profit</th>
                @endif
            @endforeach
        </tr>
        @foreach ($recipes as $index => $recipe)
            <tr>
                <td style="background-color: #f2f2f2;">{{ $recipe->plu }}</td>
                <td style="background-color: #f2f2f2;">{{ $recipe->name }}</td>
                <td style="background-color: #f2f2f2;"> SGD {{ number_format($recipe->cost, 2, '.', '') }}
                </td>
                <td style="background-color: #f2f2f2;"> {{ number_format($recipe->tax, 2, '.', '') }} %
                </td>
                @if ($recipe->sales->count() > 1)
                    @foreach ($recipe->sales as $sale)
                        @php
                            $price_level = \App\Models\PriceLevel::where('id', $sale->price_level_id)->get();
                        @endphp
                        @if ($price_level[0]->type == 0)
                            <td style="background-color: #f2f2f2;"> SGD
                                {{ number_format($sale->price, 2, '.', '') }}</td>
                            <td style="background-color: #f2f2f2;">
                                @if ($sale->pure_cost > 0)
                                    {{ $sale->pure_cost }} %
                                @else
                                    -
                                @endif
                            </td>
                            <td style="background-color: #f2f2f2;"> SGD
                                {{ number_format($sale->price - $recipe->cost, 2, '.', '') }}
                            </td>
                        @elseif($price_level[0]->type == 1)
                            <td style="background-color: #f2f2f2;"> SGD
                                {{ number_format($sale->price, 2, '.', '') }}</td>
                            <td style="background-color: #f2f2f2;">
                                @if ($sale->pure_cost > 0)
                                    {{ $sale->pure_cost }} %
                                @else
                                    -
                                @endif
                            </td>
                            <td style="background-color: #f2f2f2;"> SGD
                                {{ number_format($sale->price - $recipe->cost, 2, '.', '') }}
                            </td>
                        @elseif($price_level[0]->type == 2)
                            <td style="background-color: #f2f2f2;"> SGD
                                {{ number_format($sale->price, 2, '.', '') }}</td>
                            <td style="background-color: #f2f2f2;">
                                @if ($sale->pure_cost > 0)
                                    {{ $sale->pure_cost }} %
                                @else
                                    -
                                @endif
                            </td>
                            <td style="background-color: #f2f2f2;"> SGD
                                {{ number_format($sale->price - $recipe->cost, 2, '.', '') }}
                            </td>
                        @elseif($price_level[0]->type == 3)
                            <td style="background-color: #f2f2f2;"> SGD
                                {{ number_format($sale->price, 2, '.', '') }}</td>
                            <td style="background-color: #f2f2f2;">
                                @if ($sale->pure_cost > 0)
                                    {{ $sale->pure_cost }} %
                                @else
                                    -
                                @endif
                            </td>
                            <td style="background-color: #f2f2f2;"> SGD
                                {{ number_format($sale->price - $recipe->cost, 2, '.', '') }}
                            </td>
                        @endif
                    @endforeach
                @else
                    @foreach ($recipe->sales as $sale)
                        <td style="background-color: #f2f2f2;"> SGD
                            {{ number_format($sale->price, 2, '.', '') }}</td>
                        <td style="background-color: #f2f2f2;">
                            @if ($sale->pure_cost > 0)
                                {{ $sale->pure_cost }} %
                            @else
                                -
                            @endif
                        </td>
                        <td style="background-color: #f2f2f2;"> SGD
                            {{ number_format($sale->price - $recipe->cost, 2, '.', '') }}
                        </td>
                        @foreach ($price_levels as $price)
                            @if ($price->type == 1)
                                <td style="background-color: #f2f2f2;"> SGD 0.00 </td>
                                <td style="background-color: #f2f2f2;"> - </td>
                                <td style="background-color: #f2f2f2;"> SGD
                                    {{ number_format(0 - $recipe->cost, 2, '.', '') }}</td>
                            @endif
                            @if ($price->type == 2)
                                <td style="background-color: #f2f2f2;"> SGD 0.00 </td>
                                <td style="background-color: #f2f2f2;"> - </td>
                                <td style="background-color: #f2f2f2;"> SGD
                                    {{ number_format(0 - $recipe->cost, 2, '.', '') }}</td>
                            @endif
                            @if ($price->type == 3)
                                <td style="background-color: #f2f2f2;"> SGD 0.00 </td>
                                <td style="background-color: #f2f2f2;"> - </td>
                                <td style="background-color: #f2f2f2;"> SGD
                                    {{ number_format(0 - $recipe->cost, 2, '.', '') }}</td>
                            @endif
                        @endforeach
                    @endforeach
                @endif
            </tr>
            @foreach ($recipe->ingredients as $index => $ingredient)
                <tr>
                    <td>
                        <span style="justify-content: center; display: flex;">
                            {{ $index == 0 ? 'Ingredient(s):' : '' }}
                        </span>
                    </td>
                    <td>
                        {{ $ingredient->item->name }} - {{ $ingredient->qty }}
                        {{ $ingredient->uom_text }}
                    </td>
                    <td>SGD {{ $ingredient->cost }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    @foreach ($price_levels as $price)
                        @if ($price->type != 0)
                            <td></td>
                            <td></td>
                            <td></td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        @endforeach
    </table>
</body>

</html>
