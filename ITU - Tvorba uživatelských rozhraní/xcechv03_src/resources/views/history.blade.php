{{-- history.blade.php --}}
{{----}}
{{-- autor: Tomáš Čechvala (xcechv03) --}}
{{----}}

<x-layout>
    <x-slot name="content">
        <div class="mt-40 mx-auto justify-items-center justify-center text-center flex w-full">
            <div class="text-white w-7/12 px-12">
                <div class="col-sm-3 py-6 text-2xl w-full text-center overflow-auto">
                    <b>Item history</b>
                </div>

                <div class="flex flex-col mt-10">
                    <div class="flex py-2 px-2.5 mx-4 mb-2 justify-between whitespace-nowrap drop-shadow-lg text-xl 2xl:text-2xl">
                        <div class="p-2 mr-2 text-left col-sm-1">
                            <b>History ID</b>
                        </div>
                        <div class="p-2 text-left col-sm-2">
                            <b>Username</b>
                        </div>
                        <div class="p-2 text-left col-sm-3">
                            <b>Change type</b>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col max-h-96 overflow-y-auto shadow-md border-4 border-white rounded-xl text-lg">
                @php
                    $count = 0;
                    $i = 0;
                @endphp
                @foreach($histories as $history)
                    @if ($history->item_id === $item->id)
                        @php
                            $count++;
                        @endphp
                    @endif
                @endforeach
                @foreach($histories as $history)
                    @if ($history->item_id === $item->id)
                        @php
                            $i++;
                            if($count == $i){ echo '<div class="flex py-2 px-2.5 mx-4 mb-2 justify-between whitespace-nowrap drop-shadow-lg">';}
                            else{ echo '<div class="flex py-2 px-2.5 mx-4 mb-2 justify-between whitespace-nowrap border-b-4 border-white drop-shadow-lg">';}
                        @endphp
                            <div class="p-2 mr-2 text-left col-sm-1">
                                <b>{{ $history->id }}</b>
                            </div>
                            <div class="p-2 text-left col-sm-2">
                                <b>
                                    {{ $history->user?->name ? $history->user?->name : 'Anonym'}}
                                </b>
                            </div>
                            <div class="p-2 text-left col-sm-3">
                                <b>@php if ($history->change_type == 1) { echo "Add item"; }
                                    elseif ($history->change_type == 2) { echo "Edit"; }
                                    elseif ($history->change_type == 3) { echo "Sell item"; }
                                    else echo "Unknown";
                                    @endphp
                                </b>
                            </div>
                        </div>
                    @endif
                @endforeach
                </div>

                <a class="text-xl" href="/item/{{ $item->id }}">
                    <x-button class="h-12 mt-10">
                        Back
                    </x-button>
                </a>
            </div>
        </div>
    </x-slot>
</x-layout>
