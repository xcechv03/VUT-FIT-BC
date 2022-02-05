{{-- invoice.blade.php --}}
{{----}}
{{-- autor: Tomáš Čechvala (xcechv03) --}}
{{----}}

<x-invoice_layout>
    <x-slot name="content">


        @php $totalPrice = 0; @endphp
        <div class="mx-auto text-center">

            <section class="text-gray-600 body-font flex flex-col h-screen justify-between">
                <div class="container px-5 pt-24 mx-auto mb-auto">
                    <div class="flex flex-col text-center w-full mb-20">
                        <h1 class="sm:text-4xl text-3xl font-medium title-font mb-2 text-gray-900">Invoice</h1>
                        <p class="lg:w-2/3 mx-auto leading-relaxed text-base">Recap of your order:</p>
                    </div>
                    <div class="lg:w-2/3 w-full mx-auto overflow-auto">
                        <table class="table-auto w-full text-left whitespace-no-wrap mb-8">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">Product</th>
                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Count</th>
                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Category</th>
                                    <th class="px-3 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Weight</th>
                                    <th class="px-4 w-10 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tr rounded-br">Price</th>

                                </tr>
                            </thead>
                            <tbody>
                            @foreach($carts as $cart)
                                <tr>
                                    @php
                                        $item = \App\Models\Item::find($cart->product_id);
                                        $totalPrice += $item->price;
                                    @endphp
                                    <td class="px-4 py-3">{{$item->product}}</td>
                                    <td class="px-4 py-3">{{$cart->number_of_items}}x</td>
                                    <td class="px-4 py-3">{{ $item->category?->name }}</td>
                                    <td class="px-4 py-3">{{ $item->weight_kg }} kg</td>
                                    <td class="px-4 py-3 text-lg text-gray-900">${{ $item->price }}</td>

                                </tr>
                            @endforeach
                            </tbody>

                        </table>
                        <div class="pt-4 border-t m-auto w-6/12 border-gray-800"><b>Total price: ${{ $totalPrice }}</b></div>
                    </div>
                </div>
                <footer class = "pb-4 flex flex-row justify-around w-6/12 mx-auto">
                    <div>
                        <br><br><br>
                        @php
                            echo date('d.m.Y H:i:s');
                        @endphp
                        <br>Payment is due within 14 days.
                    </div>
                    <div>
                        Company Inc.<br>
                        Božetěchova 1<br>
                        Brno, 612 00<br>
                        <br>
                        Stamp and sign
                    </div>
                </footer>
            </section>
    </x-slot>
</x-invoice_layout>
