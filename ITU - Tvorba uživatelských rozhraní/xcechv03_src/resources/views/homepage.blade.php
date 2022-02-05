{{-- homepage.blade.php --}}
{{----}}
{{-- autor: Vojtěch Orava (xorava02) --}}
{{----}}

<x-layout>
    <x-slot name="content">

        <div class="mt-40 mx-auto justify-items-center justify-center text-center flex w-full">

            <div class="text-white w-7/12 px-12">
                <a href="/create_item" class="py-2 text-xl px-3 text-normal font-bold hover:text-white rounded-lg hover:bg-blue-700 shadow" >Add Item </a>
                <form method="GET" method="/" class="sm:gap-5 justify-center mb-8 mt-1">
                    <div class="flex mb-6">
                        <input placeholder="🔎 SEARCH"
                               value="{{ request('search') }}"
                               type="text"
                               name="search"
                               class="flex-auto md: p-3 mr-6 bg-blue-800 focus:bg-blue-900 border-8 border-white rounded-xl placeholder-white text-xl md:text-2xl font-bold outline-none shadow"/>
                        <x-button class="border-8 bg-blue-800">
                            Search
                        </x-button>
                    </div>

                    <div class="flex flex-row items-center text-center justify-around whitespace-nowrap">
                        <x-light_label class="pb-2 text-lg w-1/3" for="category">Category</x-light_label>
                        <div class="flex flex-row w-1/3 text-center">
                            <x-light_label class="pb-2 text-lg text-center w-full" id="priceLabel" for="price">Min. price</x-light_label>
                        </div>
                        <x-light_label class="pb-2 text-lg w-1/3" for="weight">Min. weight</x-light_label>
                    </div>
                    <div class="flex flex-row items-center justify-around align-center whitespace-nowrap">
                        <select class="rounded text-black w-1/3 mx-8 2xl:mx-12" name="category" id="category">
                            <option value="{{ null }}">All</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>

                        <input value="0" type="range" oninput="document.getElementById('priceLabel').innerHTML = 'Min. price: ' + this.value" id="price" class="w-1/3 mx-8 2xl:mx-12" name="price" min="0" max="2000">
                        <input value="0" type="number" id="weight" name="weight" class="rounded w-1/3 mx-8 2xl:mx-12 text-black" min="0">
                    </div>
                </form>


                <div class="flex flex-col border-8 border-white rounded-t-xl pr-1.5 shadow-2xl bg-blue-800">
                    <div class="flex py-2 px-2.5 mx-4 mb-2 justify-between whitespace-nowrap drop-shadow-lg text-xl 2xl:text-2xl">
                        <div class="p-2 mr-2 text-right col-sm-1">
                            <b>Count</b>
                        </div>
                        <div class="p-2 text-left col-sm-2">
                            <b>Product</b>
                        </div>
                        <div class="p-2 text-left col-sm-2 xl:col-sm-3">
                            <b>Category</b>
                        </div>
                        <div class="p-2 text-left col-sm-2 xl:col-sm-3">
                            <b>Company</b>
                        </div>
                        <div class="p-2 text-left col-sm-1">
                            <b>Weight</b>
                        </div>
                        <div class="p-2 text-right col-sm-1">
                            <b>Price</b>
                        </div>
                    </div>
                </div>
                <div id="items" class="flex flex-col max-h-96 overflow-y-auto shadow-md border-l-8 border-r-2 border-b-8 border-white rounded-b-xl shadow-xl px-4">

                </div>
            </div>


            <div class="bg-white pt-10 rounded-xl flex shadow">
                <div class="text-4xl text-blue-900 flex flex-col h-full justify-between">
                    <div class="mb-auto pt-2">
                        <b class="pb-2 px-16 border-b-8 rounded-md border-blue-900 ">LIST</b>
                        <div id="list" class="grid grid-cols-5 mt-10">

                        </div>
                    </div>

                    <div class="mb-2">
                        <a href="/print" class="mx-8">

                            <x-blue_button class="py-4 mb-2 h-8">
                                {{ __('Print') }}
                            </x-blue_button>
                        </a>
                        <a href="/pay" class="mx-8">
                            <x-blue_button class="py-4 mb-2 h-8">
                                {{ __('Pay') }}
                            </x-blue_button>
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </x-slot>
</x-layout>
