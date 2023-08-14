<x-visit>
    <ul>
        @foreach($books as $book)
            @foreach($book->categories as $category)
                <li>
                    <h3>{{$category->name}}</h3>
                    @foreach($category->dishes as $dish)
                        @include('visit.dish-item', $dish)
                    @endforeach
                </li>
            @endforeach
        @endforeach
    </ul>
</x-visit>

