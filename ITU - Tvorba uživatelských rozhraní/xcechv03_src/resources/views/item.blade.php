{{-- item.blade.php --}}
{{----}}
{{-- autor: Jan Procházka (xproch0g) --}}
{{----}}

<x-layout>
    <x-slot name="content">
        <div class="pt-40 text-center text-white flex w-10/12 m-auto">
            <div class="w-6/12 border-r">
                <div class="py-6 text-2xl w-full text-center overflow-auto">
                    <b>Edit item</b>
                </div>
                <x-auth-validation-errors class="mb-4" :errors="$errors"/>
                <form method="POST" action="/update_item/{{ $item->id }}">
                    @csrf
                    <div class="mx-auto between overflow-auto">
                        <div class="flex flex-col sm:gap-5 flex-wrap p-4">
                            <div class="flex items-center justify-center">
                                <b class="text-right w-1/2 pr-10">Product name:</b>
                                <div class="w-1/2 flex">
                                    <x-input class="2xl:w-1/2" value="{{ $item->product }}" placeholder="Product name" type="text" id="name" name="name" required></x-input>
                                </div>

                            </div>
                            <div class="flex items-center justify-center">
                                <b class="text-right w-1/2 pr-10">Category:</b>
                                <div class="w-1/2 flex">
                                    <select class="2xl:w-1/2 bg-white text-black rounded shadow-sm border-white focus:ring focus:ring-blue-200 focus:ring-opacity-50" name="category">
                                        @foreach($categories as $category)
                                            <option @if($category->id == $item->category_id) selected="selected" @endif value="{{$category->id}}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="flex items-center justify-center">
                                <b class="text-right w-1/2 pr-10">Company name:</b>
                                <div class="w-1/2 flex">
                                    <x-input class="2xl:w-1/2" value="{{ $item->company }}" placeholder="Company name" type="text" id="company" name="company" required></x-input>
                                </div>
                            </div>
                            <div class="flex items-center justify-center">
                                <b class="text-right w-1/2 pr-10">Count:</b>
                                <div class="w-1/2 flex">
                                    <x-input class="2xl:w-1/2" value="{{ $item->num_of_items }}" placeholder="Number of items" type="number" step="1" id="num_of_items" name="num_of_items" required></x-input>
                                </div>
                            </div>
                            <div class="flex items-center justify-center">
                                <b class="text-right w-1/2 pr-10">Weight [kg]:</b>
                                <div class="w-1/2 flex">
                                    <x-input class="2xl:w-1/2" value="{{ $item->weight_kg }}" placeholder="Weight" type="number" step="0.01" id="weight" name="weight" required></x-input>
                                </div>
                            </div>
                            <div class="flex items-center justify-center">
                                <b class="text-right w-1/2 pr-10">Price [$]:</b>
                                <div class="w-1/2 flex">
                                    <x-input class="2xl:w-1/2" value="{{ $item->price }}" placeholder="price" type="number" step="0.01" id="weight" name="price" required></x-input>
                                </div>
                            </div>
                            <!--<div class="text-left col-sm-2 px-2 overflow-auto">
                                <b>Todo</b><br>Nějaké nápady?
                            </div>-->
                        </div>
                    </div>

                    <x-button class="h-12 mt-4 border">
                        {{ __('Save changes') }}
                    </x-button>
                </form>
            </div>

            <div class="ml-16">
                <form method="POST" action="/add_to_cart/{{ $item->id }}" class="flex items-center flex-wrap sm:gap-5 justify-around align-center">
                    @csrf
                    <div class="py-6 text-2xl w-full text-center overflow-auto">
                        <b>Add item to list</b>
                    </div>

                    <div>

                    <div class="flex flex-col justify-between mb-4">
                    <x-light_label class="text-lg h-12 leading-10" for="name">Number of items:</x-light_label>
                    <x-input class="placeholder-gray-800" max="{{ $item->num_o_items }}" :value="old('order_number_items')" placeholder="0" type="number" id="order_number_items" name="order_number_items"></x-input>
                    </div>


                        <x-button class="h-12 border">
                            {{ __('Add to List') }}
                        </x-button>
                    </div>
                </form>


                <div class="border-t border-white mx-auto w-1/2 between mt-10 overflow-auto">
                    <div class="col-sm-3 py-6 text-2xl w-full text-center overflow-auto">
                        <b>Delete item</b>
                    </div>
                    <a href="/delete_item/{{$item->id}}">
                        <x-blue_input hidden value="{{ auth()->user()?->id }}" id="user" name="user"></x-blue_input>
                        <x-not_submit_button class="h-12 border rounded-xl text-lg mb-2">
                            {{ __('Delete Item') }}
                        </x-not_submit_button>
                    </a>
                </div>


                <div class="border-t border-white mx-auto w-1/2 between mt-8 overflow-auto">

                    <div class="col-sm-3 py-6 text-2xl w-full text-center overflow-auto">
                        <b>Show item history</b>
                    </div>
                    <a href="/history/{{ $item->id }}">
                        <x-button class="h-12 border mb-2">
                            {{ __('Item History') }}
                        </x-button>
                    </a>
                </div>

            </div>
        </div>
    </x-slot>
</x-layout>
