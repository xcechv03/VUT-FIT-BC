{{-- create_item.blade.php --}}
{{----}}
{{-- autor: Vojtěch Orava (xorava02) --}}
{{----}}

<x-layout>
    <x-slot name="content">
        <div class="mt-40 mx-auto justify-items-center justify-center text-center flex w-full">
            <div class="text-white w-7/12 px-12">
                <div class="col-sm-3 py-6 text-2xl w-full text-center overflow-auto">
                    <b>Add item</b>
                </div>

                <x-auth-validation-errors class="mb-4" :errors="$errors"/>
                <form method="POST" action="/create_item/create">
                    @csrf
                    <div class="flex flex-col sm:gap-5 flex-wrap p-4">
                        <div class="flex items-center justify-center">
                            <x-light_label class="text-right text-lg w-1/2 font-bold pr-10" for="category" :value="__('Category:')" />
                            <div class="w-1/2 flex">
                                <select class="2xl:w-1/2 bg-white text-black rounded shadow-sm border-white focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                        name="category" id="category">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{$category->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="flex items-center justify-center">
                            <x-light_label class="text-right text-lg w-1/2 font-bold pr-10" for="name" :value="__('Product Name:')" />
                            <div class="w-1/2 flex">
                                <x-input id="name" class="2xl:w-1/2 placeholder-gray-800" placeholder="Product name" type="text" name="name" :value="old('name')" required autofocus />
                            </div>

                        </div>
                        <div class="flex items-center justify-center">
                            <x-light_label for="company" class="text-right text-lg w-1/2 font-bold pr-10" :value="__('Company name:')" />
                            <div class="w-1/2 flex">
                                <x-input id="company" class="2xl:w-1/2 placeholder-gray-800" placeholder="Company name" type="text" name="company" :value="old('company')" required />
                            </div>
                        </div>
                        <div class="flex items-center justify-center">
                            <x-light_label for="num_of_items" class="text-right text-lg w-1/2 font-bold pr-10" :value="__('Number of items:')" />
                            <div class="w-1/2 flex">
                                <x-input id="num_of_items" class="2xl:w-1/2 placeholder-gray-800" placeholder="Number of items" type="number" name="num_of_items" :value="old('num_of_items')" required />
                            </div>
                        </div>
                        <div class="flex items-center justify-center">
                            <x-light_label for="weight" class="text-right text-lg w-1/2 font-bold pr-10" :value="__('Weight:')" />
                            <div class="w-1/2 flex">
                                <x-input id="weight" class="2xl:w-1/2 placeholder-gray-800" placeholder="kg" type="number" step="0.01" name="weight" :value="old('weight')" required />
                            </div>
                        </div>
                        <div class="flex items-center justify-center">
                            <x-light_label for="price" class="text-right text-lg w-1/2 font-bold pr-10" :value="__('Price:')" />
                            <div class="w-1/2 flex">
                                <x-input id="price" class="2xl:w-1/2 placeholder-gray-800" placeholder="$" type="number" step="0.01" name="price" :value="old('price')" required />
                            </div>
                        </div>
                    </div>

                    <x-button class="h-12 mt-4 border">
                        {{ __('Add Item to warehouse') }}
                    </x-button>
                </form>

            </div>
        </div>
    </x-slot>
</x-layout>
