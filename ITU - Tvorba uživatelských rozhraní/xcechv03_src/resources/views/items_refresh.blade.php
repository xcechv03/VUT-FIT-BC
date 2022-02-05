{{-- items_refresh.blade.php --}}
{{----}}
{{-- autor: Vojtěch Orava (xorava02) --}}
{{----}}

@foreach( $items as $item )
    <a href="/item/{{ $item->id }}" class="hover:text-white hover:bg-blue-800 rounded-xl px-2">
        @if ($loop->last)
        <div class="flex py-2 mx-2 mb-2 justify-between whitespace-nowrap drop-shadow-lg">
        @else
        <div class="flex py-2 mx-2 mb-2 justify-between whitespace-nowrap border-b border-white drop-shadow-lg">
        @endif
            <div class="p-2 mr-2 text-right col-sm-1">
                <b>{{ $item->num_of_items }} x</b>
            </div>
            <div class="p-2 text-left col-sm-2">
                <b>{{ $item->product }}</b>
            </div>
            <div class="p-2 text-left col-sm-2 xl:col-sm-3">
                <b>{{ is_null($item->category_id)?'':$item->category->name  }}</b>
            </div>
            <div class="p-2 text-left col-sm-2 xl:col-sm-3">
                <b>{{ $item->company }}</b>
            </div>
            <div class="p-2 text-right col-sm-1">
                <b>{{ $item->weight_kg }} kg</b>
            </div>
            <div class="p-2 text-right col-sm-1">
                <b>${{ $item->price }}</b>
            </div>
        </div>
    </a>
@endforeach
